<?php
/** 
* Copyright (C) 2016 Scott Wakefield <scott@npclinics.com.au>
*
* LICENSE: This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 3 
* of the License, or (at your option) any later version. 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details. 
* You should have received a copy of the GNU General Public License 
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
* 
* @package OpenEMR 
* @author Scott Wakefield <scott@npclinics.com.au>
* @link http://www.open-emr.org 
*/

$sanitize_all_escapes=true;
$fake_register_globals=false;

include_once("../../globals.php");
include_once("$srcdir/sql.inc");
include_once("$srcdir/options.inc.php");
include_once("$srcdir/immunization_helper.php");

//collect facility data
$facdata = getFacility('', -1);
$facility = "<div class='clinicAddress'>\n" . $facdata['name'] . "<br />\n" . $facdata['street'] . "<br />\n" . $facdata['city'] . ', ' . $facdata['state'] . ' ' . $facdata['postal_code'] . "</div>\n";
//collect patient data
$patdata = getPatientData($pid);
$patient = "<div class='patientAddress'>\n" . $patdata['fname'] . ' ' . $patdata['mname'] . ' ' . $patdata['lname'] . "<br />\n" . xl('Date of Birth: ', 'r') . $patdata['DOB']. "<br />\n". $patdata['street'] . "<br />\n". $patdata['city'] . ', ' . $patdata['state'] . ' ' . $patdata['postal_code'] . "</div>\n";

//collect immunizations
$res3 = getImmunizationList($pid, $_GET['sortby'], false);
$data = convertToDataArray($res3);

if ($_GET['output'] == "html") { 
  printHTML($facility, $patient, $data);
}
else {
  printPDF($facility, $patient, $data);
}

function convertToDataArray($data_array) {  
  $current = 0;
    while ($row = sqlFetchArray($data_array)) {
      //admin date
      $temp_date = new DateTime($row['administered_date']);
      $data[$current][xl('Date') . "\n" . xl('Admin')] = $temp_date->format('Y-m-d H:i'); //->format('%Y-%m-%d %H:%i');
      //Vaccine Figure out which name to use (ie. from cvx list or from the custom list)
      if ($GLOBALS['use_custom_immun_list']) {
        $vaccine_display = generate_display_field(array('data_type'=>'1','list_id'=>'immunizations'), $row['immunization_id']);
      }
      else {
        if (!empty($row['code_text_short'])) {
          $vaccine_display = htmlspecialchars( xl($row['code_text_short']), ENT_NOQUOTES);
        }
        else {
            $vaccine_display = generate_display_field(array('data_type'=>'1','list_id'=>'immunizations'), $row['immunization_id']);
        }
      }
      $data[$current][xl('Vaccine')] = $vaccine_display;
      //Amount
      if ($row['amount_administered'] > 0) {
        $data[$current][xl('Amount') . "\n" . xl('Admin')] = $row['amount_administered'] . " " . 
        generate_display_field(array('data_type'=>'1','list_id'=>'drug_units'), $row['amount_administered_unit']);
      }
      else {
        $data[$current][xl('Amount') . "\n" . xl('Admin')] = "";
      }
      //expiration date fixed by checking for empty value, smw 040214
      if (isset($row['expiration_date'])) {
        $temp_date = new DateTime($row['expiration_date']);
        $data[$current][xl('Expiration') . "\n" . xl('Date')] = $temp_date->format('Y-m-d');
      }
      else {
        $data[$current][xl('Expiration') . "\n" . xl('Date')] = '';//$temp_date->format('Y-m-d');
      }
      //Manufacturer
      $data[$current][xl('Manufacturer')] = $row['manufacturer'];
      //Lot Number
      $data[$current][xl('Lot') . "\n" . xl('Number')] = $row['lot_number'];
      //Admin By
      $data[$current][xl('Admin') . "\n" . xl('By')] = $row['administered_by'];
      //education date
      $temp_date = new DateTime($row['education_date']);
      $data[$current][xl('Patient') . "\n" . xl('Education') . "\n" . xl('Date')] = $temp_date->format('Y-m-d');    
      //Route
      $data[$current][xl('Route')] = generate_display_field(array('data_type'=>'1','list_id'=>'drug_route'), $row['route']); 
      //Admin Site
      $data[$current][xl('Admin') . "\n" . xl('Site')] = generate_display_field(array('data_type'=>'1','list_id'=>'proc_body_site'), $row['administration_site']);
      //Comments
      $data[$current][xl('Comments')] = $row['note'];
      $current ++;
    }
  return $data; 
}

function printPDF($facility, $patient, $data) {
  $pdf = new TCPDF(L, 'mm', $GLOBALS['pdf_size'], true, 'UTF-8', false);
  // set margins
  $pdf->SetMargins($GLOBALS['pdf_left_margin'],$GLOBALS['pdf_top_margin'],$GLOBALS['pdf_right_margin']);
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetPrintHeader(false);
  // set some language dependent data:
    $lg = Array();
    $lg['a_meta_language'] = $GLOBALS['pdf_language'];
    $lg['a_meta_dir'] = $_SESSION['language_direction'] == 'rtl' ? true : false;
  // set some language-dependent strings (optional)
  $pdf->setLanguageArray($lg);
  $pdf->SetFont('dejavusans', '', 10);
  $pdf->AddPage();
  $html = $facility;
  $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  $pdf->Ln(6);
  $html = $patient;
  $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  $pdf->Ln(10);
  $html = xl('Shot Record as of:') . date('m/d/Y h:i:s a');
  $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  $pdf->Ln(6);  
  $pdf->SetFontSize(7);
  $linesPerPage=15;
  $countTotalPages = (ceil((count($data))/$linesPerPage));
  $html = '<table cellspacing="0" cellpadding="2" border="1" width="100%">';
  $html.= '<tr>'; 
    //display header
    foreach ($data[0] as $key => $value) {
    //convert end of line characters to space
      $patterns = array ('/\n/');
      $replace = array (' ');
      $key = preg_replace($patterns, $replace, $key);
      $html.="<th>".htmlspecialchars( $key, ENT_NOQUOTES)."</th>";
    }
    $html.="</tr>";
    //display shot data
    for ($j=0;$j<$linesPerPage;$j++) {
      if ($rowData = array_shift($data)) {
        $html.="<tr>";
        foreach ($rowData as $key => $value) {
          $html.="<td style='white-space:nowrap;'>";
          // output data of cell
          $html.= ($value == "") ? "&nbsp;" : htmlspecialchars($value, ENT_NOQUOTES);
          $html.= "</td>";
        }
        $html.= "</tr>";
      }
    }
    $html.= "</table><br><br><br><br>";
    $pdf->SetFontSize(10);
    $html.= "<div class='sign'>" . htmlspecialchars( xl('Signature'), ENT_NOQUOTES) . ":________________________________" . "</div><br>";
    if ($countTotalPages > 1) {
      //display page number if greater than one page
      $html.= "<div class='pageNumber'>" . htmlspecialchars( xl('Page') . " " . ($i+1) . "/" . $countTotalPages, ENT_NOQUOTES) . "</div><br>";
    }
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->Output('shot_record.pdf', 'I'); // D = Download, I = Inline

}

function printHTML($facility, $patient, $data) {

  ?>  
  //print html css
  <html>
  <head>
  <style>
    body {
      font-family: sans-serif;
      font-weight: normal;
      font-size: 10pt;
      background: white;
      color: black;
    }
    div {
      padding: 0;
      margin: 0;
    } 
    div.paddingdiv {
      width: 524pt;
      height: 668pt;
      page-break-after: always;
    }
    div.patientAddress {
      margin: 20pt 0 10pt 0;
      font-size:  10pt;
    }
    div.clinicAddress {
      text-align: center;
      width: 100%;
      font-size: 10pt;
    } 
    div.sign {
      margin: 30pt 0 0 20pt;
    }
    div.tabletitle {
      font-size: 12pt;
      text-align: center;
      width: 100%;
    } 
    table {
      margin: 0 20pt 0 20pt;
      border-collapse: collapse;
      border: 1pt solid black;
    } 
    td {
      font-size: 10pt;
      padding: 2pt 3pt 2pt 3pt;
      border-right: 1pt solid black;
      border-left: 1pt solid black;
    }
    td.odd {
      background-color: #D8D8D8;  
    } 
    th {
      font-size: 10pt;
      border: 1pt solid black;
      padding: 2pt 3pt 2pt 3pt;
    } 
    div.pageNumber {
      margin-top: 15pt;
      font-size: 8pt;
      text-align: center;
      width: 100%;
    }
  </style>
  <title><?php xl ('Shot Record','e'); ?></title>
  </head>
  <body>
  
  <?php
  $title = xl('Shot Record as of:') . date('m/d/Y');
  //plan 15 lines per page
  $linesPerPage=15;
  $countTotalPages = (ceil((count($data))/$linesPerPage));
  for ($i=0;$i<$countTotalPages;$i++) {
    echo "<div class='paddingdiv'>\n"; 
    //display facility information (Note it is already escaped)
    echo $facility;
    //display patient information (Note patient address is already escaped)
    echo $patient;
    //display table title
    echo "<div class='tabletitle'>" . htmlspecialchars( $title, ENT_NOQUOTES) . "</div>\n";
    echo "<table cellspacing='0' cellpadding='0' width='100%''>\n"; 
    //display header
    echo "<tr>\n";
    foreach ($data[0] as $key => $value) {
      //convert end of line characters to space
      $patterns = array ('/\n/');
      $replace = array (' ');
      $key = preg_replace($patterns, $replace, $key);
      echo "<th>".htmlspecialchars( $key, ENT_NOQUOTES)."</th>\n";
    }
    echo "</tr>\n";
    
    //display shot data
    for ($j=0; $j<$linesPerPage; $j++) {
      if ($rowData = array_shift($data)) {
        echo "<tr>";
        foreach ($rowData as $key => $value) {
          //shading of cells
          if ($j==0) {
            echo "<td>";
          }
          elseif ($j%2) {
            echo "<td class ='odd'>";
          }
          else {
            echo "<td>";   
          }
          // output data of cell
          echo ($value == "") ? "&nbsp;" : htmlspecialchars($value, ENT_NOQUOTES);
          echo "</td>";
        }
      echo "</tr>";
    }
  else {
  //done displaying shot data, so leave loop
      break;      
      }
  }
  
  echo "</table>\n";
  //display signature line
  echo "<div class='sign'>" . htmlspecialchars( xl('Signature'), ENT_NOQUOTES) . ":________________________________" . "</div>\n";
  if ($countTotalPages > 1) {
    //display page number if greater than one page
    echo "<div class='pageNumber'>" .  htmlspecialchars( xl('Page') . " " . ($i+1) . "/" . $countTotalPages, ENT_NOQUOTES) . "</div>\n";
  }
  echo "</div>\n";    
  }
  ?>
    
  <script language='JavaScript'>
    opener.top.printLogPrint(window);
  </script>
  </body>
  </html>
  <?php } ?>