<?php
require_once("../globals.php");
require_once("../../library/create_ssl_certificate.php");
require_once("../../library/sql.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/translation.inc.php");

/********************************************************************************\
 * Copyright (C) Visolve (vicareplus_engg@visolve.com)                          *
 *                                                                              *
 * This program is free software; you can redistribute it and/or                *
 * modify it under the terms of the GNU General Public License                  *
 * as published by the Free Software Foundation; either version 2               *
 * of the License, or (at your option) any later version.                       *
 *                                                                              *
 * This program is distributed in the hope that it will be useful,              *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of               *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                *
 * GNU General Public License for more details.                                 *
 *                                                                              *
 * You should have received a copy of the GNU General Public License            *
 * along with this program; if not, write to the Free Software                  *
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.  *
 ********************************************************************************/

/*
 * This page is used to setup https access to OpenEMR with client certificate authentication.
 * If enabled, the browser must connect to OpenEMR using a client SSL certificate that is
 * generated by OpenEMR.  This page is used to create the Certificate Authority and
 * Apache SSL server certificate.
 */

/* This string contains any error messages if generating
 * certificates fails.
 */
$error_msg = "";

/* This function is called when the "Save Certificate Settings" button is clicked.
 * Save the certificate settings to the file globals.php.
 * The following form inputs are used:
 *   cakey_location - The path to the CA key file
 *   cacrt_location - The path to the CA certificate file
 *   clientCertValidity_hidden - Number of days client certificates are valid.
 *   isClientAuthenticationEnabled - Enable/disable client certificate authentication.
 *
 * Save these values to the following variables in globals.php:
 *   $certificate_authority_key
 *   $certificate_authority_crt
 *   $client_certificate_valid_in_days
 *   $is_client_ssl_enabled
 *
 * If an error occurs, set $error_msg to the appropriate string,
 * which will be displayed later on below.
 */
/*function save_certificate_settings() {
    if($_POST['cakey_location']) { $Authority_key = formData('cakey_location','P',true) ; }
    if($_POST['cacrt_location']) { $Authority_crt = formData('cacrt_location','P',true); }
    if($_POST['clientCertValidity_hidden']) { $clientCertValidity = formData('clientCertValidity_hidden','P',true); }
    if($_POST['isClientAuthenticationEnabled']) { $isClientAuthenticationEnabled = formData('isClientAuthenticationEnabled','P',true); }
    
    if ($isClientAuthenticationEnabled == "Yes") {
        $isClientAuthenticationEnabled = "true";
    } else{
        $isClientAuthenticationEnabled = "false";
    }

    global $error_msg;

    if ($Authority_key != "" && !file_exists($Authority_key)) {
        $error_msg .= xl('Error: the file does not exist', 'e') . ' ' . $Authority_key . '<br>';
    }
 
    if ($Authority_crt != "" && !file_exists($Authority_crt)) {
        $error_msg .= xl('Error, the file does not exist', 'e') . ' ' . $Authority_crt . '<br>';
    }

    if ($error_msg != "") {
        return;
    }

    $Authority_key = str_replace('\\\\', '/', $Authority_key);
    $Authority_key = str_replace('\\', '/', $Authority_key);
    $Authority_crt = str_replace('\\\\', '/', $Authority_crt);
    $Authority_crt = str_replace('\\', '/', $Authority_crt);

    // Read in the globals.php file 
    $globals_file = $GLOBALS['webserver_root'] . "/interface/globals.php";
    $inputdata = file($globals_file) or die( xl('Could not read file','e')." ". $globals_file);
    $outputdata = "";

    $wrote_key = false;
    $wrote_crt = false;
    $wrote_enable = false;
    $wrote_validity = false;

    // Loop through each line in globals.php, replacing any certificate variables with the new settings.
     
    foreach ($inputdata as $line) {
        if ((strpos($line,"\$certificate_authority_key = \"")) !== false) {
            $wrote_key = true;
            $outputdata .= "\$certificate_authority_key = \"$Authority_key\";\n";
        }
        else if ((strpos($line,"\$certificate_authority_crt = \"")) !== false) {
            $wrote_crt = true;
            $outputdata .= "\$certificate_authority_crt = \"$Authority_crt\";\n";
        }
        else if ((strpos($line,"\$is_client_ssl_enabled = ")) !== false) {
            $wrote_enable = true;
            $outputdata .= "\$is_client_ssl_enabled = $isClientAuthenticationEnabled;\n";
        }
        else if ((strpos($line,"\$client_certificate_valid_in_days = \"")) !== false) {
            $wrote_validity = true;
            $outputdata .= "\$client_certificate_valid_in_days = \"$clientCertValidity\";\n";
        }
        else {
            $outputdata .= $line;
        }
    }
    if ($wrote_key === false || $wrote_crt === false || 
        $wrote_enable === false || $wrote_validity === false) {

        $outputdata .= "<?php\n";

        if ($wrote_key === false) {
            $outputdata .= "\$certificate_authority_key = \"$Authority_key\";\n";
        }
        if ($wrote_crt == false) {
            $outputdata .= "\$certificate_authority_crt = \"$Authority_crt\";\n";
        }
        if ($wrote_enable === false) {
            $outputdata .= "\$is_client_ssl_enabled = $isClientAuthenticationEnabled;\n";
        }
        if ($wrote_validity === false) {
            $outputdata .= "\$client_certificate_valid_in_days = \"$clientCertValidity\";\n";
        }
        $outputdata .= "\n?>\n";
    }

    // Write the modified globals.php back to disk 
    $fd = @fopen($globals_file, 'w');
    if ($fd === false) {
        $error_msg .= xl('Error, unable to open file', 'e') . ' ' . $globals_file;
        return;
    }
    fwrite($fd, $outputdata);
    fclose($fd);

    $GLOBALS['is_client_ssl_enabled'] = ($isClientAuthenticationEnabled == "true");
    $GLOBALS['certificate_authority_crt'] = $Authority_crt;
    $GLOBALS['certificate_authority_key'] = $Authority_key;
}*/


/**
 * Send an http reply so that the browser downloads the given file.
 * Delete the file once the download is completed.
 * @param $filename  - The file to download.
 * @param $filetype  - The type of file.
 */
function download_file($filename, $filetype) {

    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private");
    header("Content-Type: application/" . $filetype);
    header("Content-Disposition: attachment; filename=" . basename($filename) . ";");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($filename));
    readfile($filename);
    exit;
    flush();
    @unlink($filename);
}

/* This function is called when the "Create Client Certificate" button is clicked.
 * Create and download a client certificate, given the following form inputs:
 *   client_cert_user - The username to store in the certificate
 *   client_cert_email - The email to store in the certificate
 * A temporary certificate will be written to /tmp/openemr_client_cert.p12.
 * If an error occurs, set the $error_msg (which is displayed later below).
 */
function create_client_cert() {
    global $error_msg;

    if (!$GLOBALS['is_client_ssl_enabled']) {
        $error_msg .= xl('Error, User Certificate Authentication is not enabled in OpenEMR', 'e');
        return;
    }

    if ($_POST["client_cert_user"]) {    $user = formData('client_cert_user','P',true);  }
    if ($_POST["client_cert_email"]) {    $email = formData('client_cert_email','P',true);  }
    $opensslconf = $GLOBALS['webserver_root'] . "/library/openssl.cnf";
    $serial = 0;
    $data = create_user_certificate($user, $email, $serial,
                                    $GLOBALS['certificate_authority_crt'],
                                    $GLOBALS['certificate_authority_key'],
                                    $GLOBALS['client_certificate_valid_in_days']);
    if ($data === false) {
        $error_msg .= xl('Error, unable to create client certificate.', 'e');
        return;
    }

    $filename = $GLOBALS['temporary_files_dir'] . "/openemr_client_cert.p12";
    $handle = fopen($filename, 'wt');
    fwrite($handle, $data);
    fclose($handle);

   download_file($filename, "p12");
}

/* Delete the following temporary certificate files, if they exist:
 *   /tmp/CertificateAuthority.key
 *   /tmp/CertificateAuthority.crt
 *   /tmp/Server.key
 *   /tmp/Server.crt
 *   /tmp/admin.p12
 *   /tmp/ssl.zip
 */
function delete_certificates() {
    $tempDir = $GLOBALS['temporary_files_dir'];
    $files = array("CertificateAuthority.key", "CertificateAuthority.crt",
                   "Server.key", "Server.crt", "admin.p12", "ssl.zip");

    foreach ($files as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}

/**
 * Create and download the following certificates:
 * - CertificateAuthority.key
 * - CertificateAuthority.crt
 * - Server.key
 * - Server.crt
 * - admin.p12
 * The following form inputs are used:
 */
function create_and_download_certificates()
{
    global $error_msg;
    $tempDir = $GLOBALS['temporary_files_dir'];

    $zipName = $tempDir . "/ssl.zip";
    if (file_exists($zipName)) {
        unlink($zipName);
    }

    /* Retrieve the certificate name settings from the form input */
    if ($_POST["commonName"]) {    $commonName = formData('commonName','P',true);  }
    if ($_POST["emailAddress"]) {    $emailAddress = formData('emailAddress','P',true);  }
    if ($_POST["countryName"]) {    $countryName = formData('countryName','P',true);  }
    if ($_POST["stateOrProvinceName"]) {    $stateOrProvinceName = formData('stateOrProvinceName','P',true);  }
    if ($_POST["localityName"]) {    $localityName = formData('localityName','P',true);  }
    if ($_POST["organizationName"]) {    $organizationName = formData('organizationName','P',true);  }
    if ($_POST["organizationalUnitName"]) {    $organizationName = formData('organizationalUnitName','P',true);  }
    if ($_POST["clientCertValidity"]) {    $clientCertValidity = formData('clientCertValidity','P',true);  }
    

    /* Create the Certficate Authority (CA) */
    $arr = create_csr("OpenEMR CA for " . $commonName, $emailAddress, $countryName, $stateOrProvinceName,$localityName, $organizationName, $organizationalUnitName);
        
    if ($arr === false) {
        $error_msg .= xl('Error, unable to create the Certificate Authority certificate.', 'e');
        delete_certificates();
        return;
    }
    $ca_csr = $arr[0];
    $ca_key = $arr[1];
    $ca_crt = create_crt($ca_key, $ca_csr, NULL, $ca_key);
    if ($ca_crt === false) {
        $error_msg .= xl('Error, unable to create the Certificate Authority certificate.', 'e');
        delete_certificates();
        return;
    }
    openssl_pkey_export_to_file($ca_key, $tempDir . "/CertificateAuthority.key");
    openssl_x509_export_to_file($ca_crt, $tempDir . "/CertificateAuthority.crt");

    /* Create the Server certificate */
    $arr = create_csr($commonName, $emailAddress, $countryName, $stateOrProvinceName,
                      $localityName, $organizationName, $organizationalUnitName);
    if ($arr === false) {
        $error_msg .= xl('Error, unable to create the Server certificate.', 'e');
        delete_certificates();
        return;
    }
 
    $server_csr = $arr[0];
    $server_key = $arr[1];
    $server_crt = create_crt($server_key, $server_csr, $ca_crt, $ca_key);
    
    if (server_crt === false) {
        $error_msg .= xl('Error, unable to create the Server certificate.', 'e');
        delete_certificates();
        return;
    }

    openssl_pkey_export_to_file($server_key, $tempDir . "/Server.key");
    openssl_x509_export_to_file($server_crt, $tempDir . "/Server.crt");

    /* Create the client certificate for the 'admin' user */
    $serial = 0;
    $res = sqlStatement("select id from users where username='admin'");
    if ($row = $res) {
        $serial = $row['id'];
    }

    $user_cert = create_user_certificate("admin", $emailAddress, $serial,
                                         $tempDir . "/CertificateAuthority.crt",
                                         $tempDir . "/CertificateAuthority.key",
                                         $clientCertValidity);
    if ($user_cert === false) {
        $error_msg .= xl('Error, unable to create the admin.p12 certificate.', 'e');
        delete_certificates();
        return;
    }
    $adminFile = $tempDir . "/admin.p12";
    $handle = fopen($adminFile, 'w');
    fwrite($handle, $user_cert);
    fclose($handle);
	
    /* Create a zip file containing the CertificateAuthority, Server, and admin files */
   try {
    if (! (class_exists('ZipArchive'))) {
     	 $_SESSION["zip_error"]="Error, Class ZipArchive does not exist";
	 return;
    }

    $zip = new ZipArchive;
    if(!($zip)) {
      $_SESSION["zip_error"]="Error, Could not create file archive";
      return;
    }

    if ($zip->open($zipName, ZIPARCHIVE::CREATE)) {
        $files = array("CertificateAuthority.key", "CertificateAuthority.crt",
                       "Server.key", "Server.crt", "admin.p12");
        foreach ($files as $file) {
            $zip->addFile($tempDir . "/" . $file, $file);
        }
    }
    else {
        $_SESSION["zip_error"]="Error, unable to create zip file with all the certificates";
        return;
    }
    $zip->close();

    if(ini_get('zlib.output_compression')) {
        ini_set('zlib.output_compression', 'Off');
    }
   }
   catch (Exception $e) {
    $_SESSION["zip_error"]="Error, Could not create file archive";
    return;
   }

    download_file($zipName, "zip");
}



if (!acl_check('admin', 'users')) {
  exit();
}

/*if ($_POST["mode"] == "save_ssl_settings") {
  save_certificate_settings();
}*/

if ($_POST["mode"] == "create_client_certificate") {
  create_client_cert();
}
else if ($_POST["mode"] == "download_certificates") {
  create_and_download_certificates();
}

?>

<html>
  <head>
    <script language="Javascript">


    /* If Enable User Certificate Authentication is set to "Yes", check the following:
     * - The Client certificate validation period is > 0
     * - The CertificateAuthority.key path is not empty
     * - The CertificateAuthority.crt path is not empty
     */
    /*function save_click() {
        if (document.ssl_frm.isClientAuthenticationEnabled[0].checked) {
            if(document.ssl_certificate_frm.clientCertValidity.value > 0) {
                document.ssl_frm.clientCertValidity_hidden.value = document.ssl_certificate_frm.clientCertValidity.value;
            }
            else {
                alert("<?php xl('Client certificate validity should be a valid number.', 'e'); ?>");
                document.ssl_certificate_frm.clientCertValidity.focus();
                return false;
            }
            if (document.ssl_frm.cakey_location.value == "") {
                alert ("<?php xl('Certificate Authority key file location cannot be empty', 'e'); ?>");
                document.ssl_frm.cakey_location.focus();
                return false;
            }

            if (document.ssl_frm.cacrt_location.value == "") {
                alert ("<?php xl('Certificate Authority crt file location cannot be empty', 'e'); ?>");
                document.ssl_frm.cacrt_location.focus();
                return false;
            }
        }
        return true;
    }*/

    //check whether email id is valid or not
    function checkEmail(email) {
	var str=email;
	var at="@";
	var dot=".";
	var lat=str.indexOf(at);
	var lstr=str.length;
	var ldot=str.indexOf(dot);
	if (str.indexOf(at)==-1){
	   return false;
	}

	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   return false;
	}

	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
	    return false;
	}

	 if (str.indexOf(at,(lat+1))!=-1){
	    return false;
	 }

	 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
	    return false;
	 }

	 if (str.indexOf(dot,(lat+2))==-1){
	    return false;
	 }

	 if (str.indexOf(" ")!=-1){
	    return false;
	 }

	return true;
    }
    function download_click(){
        if (document.ssl_certificate_frm.commonName.value == "") {
            alert ("<?php xl('Host Name cannot be empty', 'e'); ?>");
            document.ssl_certificate_frm.commonName.focus();
            return false;
        }
    
        if (document.ssl_certificate_frm.emailAddress.value) {
	     //call checkEmail function
             if(checkEmail(document.ssl_certificate_frm.emailAddress.value) == false){
		alert ("<?php xl('Provide valid Email Address', 'e'); ?>");
		return false;
	     }
        }

        if (document.ssl_certificate_frm.countryName.value.length > 2) {
            alert ("<?php xl('Country Name should be represent in two letters. (Example: United States is US)', 'e'); ?>");
            document.ssl_certificate_frm.countryName.focus();
            return false;
        }
        if (document.ssl_certificate_frm.clientCertValidity.value < 1) {
            alert ("<?php xl('Client certificate validity should be a valid number.', 'e'); ?>");
            document.ssl_certificate_frm.clientCertValidity.focus();
            return false;
        }
    }
    function create_client_certificate_click(){

        /*if(document.ssl_frm.isClientAuthenticationEnabled[1].checked == true)
        {
	    alert ("<?php xl('User Certificate Authentication is disabled', 'e'); ?>");
            return false;
        }*/
	    
        if (document.client_cert_frm.client_cert_user.value == "") {
            alert ("<?php xl('User name or Host name cannot be empty', 'e'); ?>");
            document.ssl_certificate_frm.commonName.focus();
            return false;
        }
	if (document.client_cert_frm.client_cert_email.value) {
	     //call checkEmail function
             if(checkEmail(document.client_cert_frm.client_cert_email.value) == false){
		alert ("<?php xl('Provide valid Email Address', 'e'); ?>");
		return false;
	     }
        }
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true;
    }
    
    </script>

    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <style type="text/css">
      div.borderbox {
        margin: 5px 5px;
        padding: 5px 5px;
        border: solid 1px;
        width: 60%;
      }
    </style>

  </head>
  <body class="body_top">
  <span class='title'><b><?php xl('SSL Certificate Administration', 'e'); ?></b></span> 
  </br> </br>   
  <?php if($_SESSION["zip_error"]) { ?>
  <div>  <table align="center" >
  <tr valign="top"> <td rowspan="3"> <?php echo "<font class='redtext'>" . xl($_SESSION["zip_error"]) ?> </td> </tr>
  </table> <?php
  unset($_SESSION["zip_error"]); ?></div>
  <?php } else { ?>
  <span class='text'>
  <?php 
    if ($error_msg != "") {
      echo "<font class='redtext'>" . $error_msg . "</font><br><br>";
    }
  ?>
  <?php xl('To setup https access with client certificate authentication, do the following', 'e'); ?>
  <ul>
    <li><?php xl('Create the SSL Certificate Authority and Server certificates.', 'e'); ?>
    <li><?php xl('Configure Apache to use HTTPS.', 'e'); ?>
    <li><?php xl('Configure Apache and OpenEMR to use Client side SSL certificates.', 'e'); ?>
    <li><?php xl('Import certificate to the browser.', 'e'); ?>
    <li><?php xl('Create a Client side SSL certificate for each user or client machine.', 'e'); ?>
  </ul>
  <br>
  <?php
    if ($GLOBALS['certificate_authority_crt'] != "" && $GLOBALS['is_client_ssl_enabled']) {
      xl('OpenEMR already has a Certificate Authority configured.', 'e');
    }
  ?>
  <form method='post' name=ssl_certificate_frm action='ssl_certificates_admin.php'>
  <input type='hidden' name='mode' value='download_certificates'>
  <div class='borderbox'>
    <b><?php xl('Create the SSL Certificate Authority and Server certificates.', 'e'); ?></b><br>
    <br>
    1. <?php xl('Fill in the values below', 'e'); ?><br>
    2. <?php xl('Click Download Certificate to download the certificates in the file ssl.zip', 'e'); ?> <br>
    3. <?php xl('Extract the zip file', 'e'); echo ": ssl.zip "; ?><br></br>
    <?php xl('The zip file will contain the following items', 'e'); ?> <br>
    <ul>
      <li>Server.crt : <?php xl('The Apache SSL server certificate and public key', 'e'); ?>
      <li>Server.key : <?php xl('The corresponding private key', 'e'); ?>
      <li>CertificateAuthority.crt : <?php xl('The Certificate Authority certificate', 'e'); ?>
      <li>CertificateAuthority.key : <?php xl('The corresponding private key', 'e'); ?>
      <li>admin.p12 : <?php xl('A client certificate for the admin user', 'e'); ?>
    </ul>
        <table border=0>
      <tr class='text'>
        <td><?php xl('Host Name', 'e'); ?> *:</td>
        <td><input name='commonName' type='text' value=''></td>
        <td><?php xl('Example', 'e') ; echo ': hostname.domain.com'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('Email Address', 'e'); ?>:</td>
        <td><input name='emailAddress' type='text' value=''></td>
        <td><?php xl('Example', 'e') ; echo ': web_admin@domain.com'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('Organization Name', 'e'); ?>:</td>
        <td><input name='organizationName' type='text' value=''></td>
        <td><?php xl('Example', 'e'); echo ': My Company Ltd'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('Organizational Unit Name', 'e'); ?>:</td>
        <td><input name='organizationalUnitName' type='text' value=''></td>
        <td><?php xl('Example', 'e'); echo ': OpenEMR'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('Locality', 'e'); ?>:</td>
        <td><input name='localityName' type='text' value=''></td>
        <td><?php xl('Example', 'e') ; echo ': City'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('State Or Province', 'e'); ?>:</td>
        <td><input name='stateOrProvinceName' type='text' value=''></td>
        <td><?php xl('Example', 'e') ; echo ': California'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('Country', 'e'); ?>:</td>
        <td><input name='countryName' type='text' value='' maxlength='2'></td>
        <td><?php xl('Example', 'e'); echo ': US'; echo ' ('; xl('Should be two letters', 'e'); echo ')'; ?></td>
      </tr>
      <tr class='text'>
        <td><?php xl('Client certificate validation period', 'e'); ?>:</td>
        <td><input name='clientCertValidity' type='text' onkeypress='return isNumberKey(event)' value='365'></td>
        <td><?php xl('days', 'e'); ?></td>
      </tr>
      <tr>
        <td colspan=3 align='center'>
          <input name='sslcrt' type='submit' onclick='return download_click();' value='<?php xl('Download Certificates', 'e'); ?>'>
        </td>
      </tr>
    </table>
  </div>
  </form>
  <br>

  <div class="borderbox">
    <b><?php xl('Configure Apache to use HTTPS.', 'e'); ?></b><br>
    <br>
    <?php xl('Add new certificates to the Apache configuration file', 'e'); ?>:<br>
    <br>
    SSLEngine on<br>
    SSLCertificateFile   /path/to/Server.crt<br>
    SSLCertificateKeyFile /path/to/Server.key<br>
    SSLCACertificateFile /path/to/CertificateAuthority.crt<br>
    <br>
    <?php xl('Note','e'); ?>:
    <ul>
      <li><?php xl('To Enable only HTTPS, perform the above changes and restart Apache server. If you want to configure client side certificates also, please configure them in the next section.', 'e'); ?></br>
    <li> <?php xl('To Disable HTTPS, comment the above lines in Apache configuration file and restart Apache server.', 'e'); ?>
    <ul/>
  </div>

  <br>
  <div class="borderbox">
    <form name='ssl_frm' method='post'>
    <b><?php xl('Configure Apache to use Client side SSL certificates', 'e'); ?> </b>
    <br></br>
    <?php xl('Add following lines to the Apache configuration file', 'e'); ?>:<br>
    </br>
    SSLVerifyClient require<br>
    SSLVerifyDepth 2<br>
    SSLOptions +StdEnvVars<br>
    <!--/br> <b><?php xl('Configure Openemr to use Client side SSL certificates', 'e'); ?> </b></br>
    <input type='hidden' name='clientCertValidity_hidden' value=''>
      <input type='hidden' name='mode' value='save_ssl_settings'></br>
      <table cellpadding=0 cellspacing=0>
        <tr class='text'>
          <td><?php xl('Enable User Certificate Authentication', 'e'); ?>:</td>
          <td>
            <input name='isClientAuthenticationEnabled' type='radio' value='Yes'
             <?php if ($GLOBALS['is_client_ssl_enabled']) echo "checked"; ?> > <?php xl('Yes', 'e'); ?> 
            <input name='isClientAuthenticationEnabled' type='radio' value='No'  <?php if (!$GLOBALS['is_client_ssl_enabled']) echo "checked"; ?> > <?php xl('No', 'e'); ?>
          </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr class='text'>
          <td>CertificateAuthority.key <?php xl('file location', 'e'); ?>: </td>
          <td>
            <input type='hidden' name='hiden_cakey' />
            <input name='cakey_location' type='text' size=20 value='<?php echo $GLOBALS['certificate_authority_key'] ?>' /> (<?php xl('Provide absolute path', 'e'); ?>) 
          </td>
        </tr>
        <tr class='text'>
          <td>CertificateAuthority.crt <?php xl('file location', 'e'); ?>: </td>
          <td>
            <input type='hidden' name='hiden_cacrt' />
            <input name='cacrt_location' type=text size=20 value='<?php echo $GLOBALS['certificate_authority_crt'] ?>'/> (<?php xl('Provide absolute path', 'e'); ?>)
          </td>
        </tr>
      </table>
      </br>
      <input type='submit' value='<?php xl('Save Certificate Settings', 'e'); ?>' onclick='return save_click();'-->    
    </br> <b><?php xl('Configure Openemr to use Client side SSL certificates', 'e'); ?> </b></br>
      <input type='hidden' name='clientCertValidity_hidden' value=''>
      </br>
           
          <?php xl('Update the following variables in file', 'e'); ?>: globals.php</br></br>
	  <?php xl('To enable Client side ssl certificates', 'e'); ?></br>
	  <?php xl('Set', 'e'); ?> 'is_client_ssl_enabled' <?php xl('to', 'e'); ?> 'true' </br></br>
	  <?php xl('Provide absolute path of file', 'e'); ?> CertificateAuthority.key</br>	
 	  <?php xl('Set', 'e'); ?> 'certificate_authority_key' <?php xl('to absolute path of file', 'e'); ?> 'CertificateAuthority.key'</br></br>
	  <?php xl('Provide absolute path of file', 'e'); ?> CertificateAuthority.crt</br>
          <?php xl('Set', 'e'); ?> 'certificate_authority_crt' <?php xl('to absolute path of file', 'e'); ?> 'CertificateAuthority.crt'</br>          
     <br>
    </br><?php xl('Note','e'); ?>:
    <ul>
      <li><?php xl('To Enable Client side SSL certificates authentication, HTTPS should be enabled.', 'e'); ?>
      <li><?php xl('After performing above configurations, import the admin client certificate to the browser and restart Apache server (empty password).', 'e'); ?>
      <li><?php xl('To Disable client side SSL certificates, comment above lines in Apache configuration file and set', 'e'); ?> 'false' <?php xl('for variable', 'e'); ?> 'is_client_ssl_enabled' (globals.php) <?php xl('and restart Apache server.', 'e'); ?>
    </form>
  </div>
  <br>
  <div class="borderbox">
    <b><?php xl('Create Client side SSL certificates', 'e'); ?></b><br>
    <br>
    <?php xl('Create a client side SSL certificate for either a user or a client hostname.', 'e'); ?>
    <br>
    <?php
      if (!$GLOBALS['is_client_ssl_enabled'] ||
           $GLOBALS['certificate_authority_crt'] == "") {
        echo "<font class='redtext'>" . xl('OpenEMR must be configured to use certificates before it can create client certificates.', 'e') . "</font><br>";
     }
    ?>
    <form name='client_cert_frm' method='post' action='ssl_certificates_admin.php'>
      <input type='hidden' name='mode' value='create_client_certificate'>
      <table>
        <tr class='text'>
          <td><?php xl('User or Host name', 'e'); ?>*:</td>
          <td><input type='text' name='client_cert_user' size=20 />
        </tr>
        <tr class='text'>
          <td><?php xl('Email', 'e'); ?>:</td>
          <td><input type='text' name='client_cert_email' size=20 />
        </tr>
      </table>
      </br> <input type='submit' onclick='return create_client_certificate_click();' value='<?php xl('Create Client Certificate', 'e'); ?>'>
    </form>
  </div>
  <br>
  <br>&nbsp;
  <br>&nbsp;
  </span>
  <?php } ?>
  </body>
</html>
