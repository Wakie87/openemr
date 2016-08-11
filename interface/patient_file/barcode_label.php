<?php
/** 
 * interface/patient_file/barcode_label.php Displaying a PDF file of Labels for printing. 
 * 
 * Program for displaying Barcode Label
 * via the popups on the left nav screen
 * 
 * Copyright (C) 2014 Terry Hill <terry@lillysystems.com> 
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
 * @author Terry Hill <terry@lillysystems.com>
 * @author Scott Wakefield <scott@npclinics.com.au> 
 * @link http://www.open-emr.org 
 *
 *
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/patient.inc");
  
//Get the data to place on labels
 $patdata = getPatientData($pid, "pubpid");

// BARCODE DATA AND TYPE
  
$code    = $patdata['pubpid']; // what is wanted as the barcode
$bartype = $GLOBALS['barcode_label_type'] ; // Get barcode type

 switch($bartype){
            case '1':
                $type     = 'S25'; // Standard 2 of 5
                break;
            case '2':
                $type     = 'I25'; // Interleaved 2 of 5
                break;
            case '3':
                $type     = 'EAN8'; // EAN 8
                break;
            case '4':
                $type     = 'EAN13'; // EAN 13
                break;
            case '5':
                $type     = 'UPCA'; // UPC-A
                break;
            case '6':
                $type     = 'CODE11'; // CODE 11
                break;
            case '7':
                $type     = 'C39'; // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
                break;
            case '8':
                $type     = 'C93'; // CODE 93 - USS-93
                break;
            case '9':
                $type     = 'C128'; // CODE 128
                break;
            case '10':
                $type     = 'CODABAR'; // CODABAR
                break;
            case '11':
                $type     = 'MSI'; // MSI (Variation of Plessey code)
                break;
        }

// PROPERTIES

$fontSize = 28;
$z        = 5;   
$y        = 120;  
$h        = 40;   
$w        = 60;
$xres     = 1;    // width of the smallest bar in user units

// ALLOCATE TCPDF RESSOURCE

$pdf = new mPDF('utf-8', 'A4', '', '', 15, 15, 16, 16, 9, 9, 'L'); // set the orentation, unit of measure and size of the page
$pdf->AddPage(); 

// BARCODE
  
//$pdf->SetFont($pdf->font,'B',$fontSize);
//$pdf->SetTextColor(0, 0, 0);

// define barcode style
// $style = array(
//     'position' => 'C',
//     'align' => 'C',
//     'stretch' => true,
//     'fitwidth' => false,
//     'cellfitalign' => 'C',
//     'border' => false,
//     'hpadding' => 'auto',
//     'vpadding' => 'auto',
//     'fgcolor' => array(0,0,0),
//     'bgcolor' => false //array(255,255,255),

// );

//$x = $pdf->GetX();

$linear = array(
    'C128A'      => array('0123456789', 'CODE 128 A'),
    'C128B'      => array('0123456789', 'CODE 128 B'),
    'C128C'      => array('0123456789', 'CODE 128 C'),
    'C128'       => array('0123456789', 'CODE 128'),
    'C39E+'      => array('0123456789', 'CODE 39 EXTENDED + CHECKSUM'),
    'C39E'       => array('0123456789', 'CODE 39 EXTENDED'),
    'C39+'       => array('0123456789', 'CODE 39 + CHECKSUM'),
    'C39'        => array('0123456789', 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9'),
    'C93'        => array('0123456789', 'CODE 93 - USS-93'),
    'CODABAR'    => array('0123456789', 'CODABAR'),
    'CODE11'     => array('0123456789', 'CODE 11'),
    'EAN13'      => array('0123456789', 'EAN 13'),
    'EAN2'       => array('12',         'EAN 2-Digits UPC-Based Extension'),
    'EAN5'       => array('12345',      'EAN 5-Digits UPC-Based Extension'),
    'EAN8'       => array('1234567',    'EAN 8'),
    'I25+'       => array('0123456789', 'Interleaved 2 of 5 + CHECKSUM'),
    'I25'        => array('0123456789', 'Interleaved 2 of 5'),
    'IMB'        => array('0123456789', 'IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200'),
    'IMBPRE'     => array('fatdfatdfatdfatdfatdfatdfatdfatdfatdfatdfatdfatdfatdfatdfatdfatdf', 'IMB pre-processed'),
    'KIX'        => array('0123456789', 'KIX (Klant index - Customer index)'),
    'MSI+'       => array('0123456789', 'MSI + CHECKSUM (modulo 11)'),
    'MSI'        => array('0123456789', 'MSI (Variation of Plessey code)'),
    'PHARMA2T'   => array('0123456789', 'PHARMACODE TWO-TRACKS'),
    'PHARMA'     => array('0123456789', 'PHARMACODE'),
    'PLANET'     => array('0123456789', 'PLANET'),
    'POSTNET'    => array('0123456789', 'POSTNET'),
    'RMS4CC'     => array('0123456789', 'RMS4CC (Royal Mail 4-state Customer Bar Code)'),
    'S25+'       => array('0123456789', 'Standard 2 of 5 + CHECKSUM'),
    'S25'        => array('0123456789', 'Standard 2 of 5'),
    'UPCA'       => array('0123456789', 'UPC-A'),
    'UPCE'       => array('0123456789', 'UPC-E'),
);

$barcode = new \Com\Tecnick\Barcode\Barcode();
//$examples = '<h3>Linear</h3>'."\n";   
//foreach ($linear as $type => $code) {
    $bobj = $barcode->getBarcodeObj($type, array('123456'), -3, -30, 'black', array(0, 0, 0, 0));
    $examples = '<h4>'.$type.'</h4><p style="font-family:monospace;">'.$bobj->getHtmlDiv().'</p>'."\n";
//}

//echo "<h3>HTML DIV</h3><p style=\"font-family:monospace;\">".$bobj->getHtmlDiv()."</p>";
//echo print_r($bobj);
//echo $examples;
//echo $bobj->getHtmlDiv();
$pdf->WriteHTML('<h4>'.$type.'</h4><p style="font-family:monospace;">'.$bobj->getHtmlDiv().'</p>');

// $bobj = $barcode->getBarcodeObj('C128', '0123456789', '', '', 'blue', array(0, 0, 0, 0));
// $bc = $bobj->getHtmlDiv();
// $pdf->WriteHTML($bc);

//$pdf->write1DBarcode($code, $type, '', '', $w, $h, 1, $style, 'N');
//$pdf->SetXY($x, (($h/2) + $fontSize + $z));
//$pdf->Cell(0, 0, $code, 0, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
$pdf->Output();

?>