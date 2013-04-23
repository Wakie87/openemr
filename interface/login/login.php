<!DOCTYPE HTML>
<?php
$ignoreAuth = true;
include_once ("../globals.php");
include_once("$srcdir/sha1.js");
include_once("$srcdir/sql.inc");
include_once("$srcdir/md5.js");
?>
<html>
	<head>
		<?php html_header_show(); ?>
		<title><?php xl ('Login','e'); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
		<!--<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">-->
		<link rel=stylesheet href="../themes/login.css" type="text/css">
		<link rel=stylesheet href="../../library/css/bootstrap.css" type="text/css">
		<script language='JavaScript' src="../../library/js/jquery.js"></script>
		<script language='JavaScript' src="../../library/js/bootstrap.js"></script>
		<script language='JavaScript'>
			//VicarePlus :: Validation function for checking the hashing algorithm used for encrypting password
			function chk_hash_fn()
			{
			var str = document.forms[0].authUser.value;
			   $.ajax({
			  url: "validateUser.php?u="+str,
			  context: document.body,
			  success: function(data){
					if(data == 0) //VicarePlus :: If the hashing algorithm is 'MD5'
					{
							document.forms[0].authPass.value=MD5(document.forms[0].clearPass.value);
							document.forms[0].authNewPass.value=SHA1(document.forms[0].clearPass.value);
					}
					else  //VicarePlus :: If the hashing algorithm is 'SHA1'
					{
							document.forms[0].authPass.value=SHA1(document.forms[0].clearPass.value);
					}
							document.forms[0].clearPass.value='';
							document.login_form.submit();
							}
					});
			}

			function imsubmitted() {
			<?php if (!empty($GLOBALS['restore_sessions'])) { ?>
			 // Delete the session cookie by setting its expiration date in the past.
			 // This forces the server to create a new session ID.
			 var olddate = new Date();
			 olddate.setFullYear(olddate.getFullYear() - 1);
			 document.cookie = '<?php echo session_name() . '=' . session_id() ?>; path=/; expires=' + olddate.toGMTString();
			<?php } ?>
			 return false; //Currently the submit action is handled by the chk_hash_fn() function itself.
			}
		</script>
	</head>

	<body class="login_body">
			<div class="wrap" <?php echo $login_body_line;?> onload="javascript:document.login_form.authUser.focus();" >
			

					
						<h2>OpenEMR Admin</h2>
						<h4><?php echo "$openemr_name" ?></h4>
						<form class="form-horizontal" method="POST" action="../main/main_screen.php?auth=login&site=<?php echo htmlspecialchars($_SESSION['site_id']); ?>" target="_top" name="login_form" onsubmit="return imsubmitted();">

							<?php
							// collect groups
								$res = sqlStatement("select distinct name from groups");
								for ($iter = 0;$row = sqlFetchArray($res);$iter++)
									$result[$iter] = $row;
								if (count($result) == 1) {
									$resvalue = $result[0]{"name"};
									echo "<input type='hidden' name='authProvider' value='$resvalue' />\n";
								}
								// collect default language id
								$res2 = sqlStatement("select * from lang_languages where lang_description = '".$GLOBALS['language_default']."'");
								for ($iter = 0;$row = sqlFetchArray($res2);$iter++)
										  $result2[$iter] = $row;
								if (count($result2) == 1) {
										  $defaultLangID = $result2[0]{"lang_id"};
										  $defaultLangName = $result2[0]{"lang_description"};
								}
								else {
										  //default to english if any problems
										  $defaultLangID = 1;
										  $defaultLangName = "English";
								}
								// set session variable to default so login information appears in default language
								$_SESSION['language_choice'] = $defaultLangID;
								// collect languages if showing language menu
								if ($GLOBALS['language_menu_login']) {
									
										// sorting order of language titles depends on language translation options.
										$mainLangID = empty($_SESSION['language_choice']) ? '1' : $_SESSION['language_choice'];
										if ($mainLangID == '1' && !empty($GLOBALS['skip_english_translation']))
										{
										  $sql = "SELECT * FROM lang_languages ORDER BY lang_description, lang_id";
									  $res3=SqlStatement($sql);
										}
										else {
										  // Use and sort by the translated language name.
										  $sql = "SELECT ll.lang_id, " .
											"IF(LENGTH(ld.definition),ld.definition,ll.lang_description) AS trans_lang_description, " .
										"ll.lang_description " .
											"FROM lang_languages AS ll " .
											"LEFT JOIN lang_constants AS lc ON lc.constant_name = ll.lang_description " .
											"LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND " .
											"ld.lang_id = '$mainLangID' " .
											"ORDER BY IF(LENGTH(ld.definition),ld.definition,ll.lang_description), ll.lang_id";
										  $res3=SqlStatement($sql);
										}
									
										for ($iter = 0;$row = sqlFetchArray($res3);$iter++)
											   $result3[$iter] = $row;
										if (count($result3) == 1) {
										   //default to english if only return one language
											   echo "<input type='hidden' name='languageChoice' value='1' />\n";
										}
										}
										else {
												echo "<input type='hidden' name='languageChoice' value='".$defaultLangID."' />\n";   
										}
							?>
								
									<?php if (count($result) != 1) { ?>
												<div class="control-group">
													<label class="control-label" for="authUser"><?php xl('Group:','e'); ?></label>
														<select name=authProvider>
															<?php
																foreach ($result as $iter) {
																	echo "<option value='".$iter{"name"}."'>".$iter{"name"}."</option>\n";
																}
															?>
														</select>
												</div>
											<?php } ?>

											<?php if (isset($_SESSION['loginfailure']) && ($_SESSION['loginfailure'] == 1)): ?>
											<div class="alert alert-error">
												<button type="button" class="close" data-dismiss="alert" href="#">&times;</button>
												<strong>Error!</strong> Incorrect Username or Password!
											</div>  
											<?php endif; ?>

											<?php if (isset($_SESSION['relogin']) && ($_SESSION['relogin'] == 1)): ?>
											<div class="alert alert-block">
												<button type="button" class="close" data-dismiss="alert" href="#">&times;</button>
												<strong><?php echo xl('Password security has recently been upgraded.'); ?><br>
												<?php echo xl('Please login again.'); ?></strong>
												<?php unset($_SESSION['relogin']); ?>
											</div>
											<?php endif; ?>
											
											
											
											
									<div class="login">	
									
										<div class="username">
										<label for="username">Username</label>
										<div class="username-input">
											<div class="control-group">
												<div class="input-prepend">
													<span class="add-on"><i class="icon-user"></i></span>
													<input type="text" autocomplete="off" id="authUser" name="authUser" placeholder=<?php xl('Username','e'); ?>>
												</div>
											</div>		
										</div>
										</div>
										
										
										<div class="password">
										<label for="password">Password</label>
										<div class="password-input">
											<div class="control-group">
												<div class="input-prepend">
													<span class="add-on"><i class="icon-lock"></i></span>
													<input type="password" autocomplete="off" id="clearPass" name="clearPass" placeholder=<?php xl('Password','e'); ?>>
												</div>	
											</div>		
										</div>		
										</div>

											<?php
											if ($GLOBALS['language_menu_login']) {
											if (count($result3) != 1) { ?>
											
										<div class="language">
										<label for="language">Language</label>	
										<div class="language-input">	
											<div class="control-group">
												<div class="input-prepend">
														<span class="add-on"><i class="icon-globe"></i></span>
														<select name=languageChoice style=" width: 204px; ">
															<?php
																	echo "<option selected='selected' value='".$defaultLangID."'>" . xl('Default','','',' -') . xl($defaultLangName,'',' ') . "</option>\n";
																	foreach ($result3 as $iter) {
																		if ($GLOBALS['language_menu_showall']) {
																				if ( !$GLOBALS['allow_debug_language'] && $iter[lang_description] == 'dummy') continue; // skip the dummy language
																				echo "<option value='".$iter['lang_id']."'>".$iter['trans_lang_description']."</option>\n";
																	}
																		else {
																		if (in_array($iter[lang_description], $GLOBALS['language_menu_show'])) {
																					if ( !$GLOBALS['allow_debug_language'] && $iter['lang_description'] == 'dummy') continue; // skip the dummy language
																			echo "<option value='".$iter['lang_id']."'>" . $iter['trans_lang_description'] . "</option>\n";
																		}
																	}
																	}
															?>
														</select>
												</div>
											</div>
										</div>
										</div>
										
									</div>	<!--login-->

									
											<?php }} ?>
											
											<div class="submit">
												
														<input type="hidden" name="authPass">
														<input type="hidden" name="authNewPass">
														<?php if (isset($GLOBALS['use_adldap_auth']) && ($GLOBALS['use_adldap_auth']== true)): ?>
														<!-- ViCareplus : As per NIST standard, the SHA1 encryption algorithm is used -->
														<button class="btn btn-danger" type="submit" onClick="javascript:this.form.authPass.value=SHA1(this.form.clearPass.value);"><?php xl('Login','e');?></button>
														<?php else: ?>
														<button class="btn btn-danger" onClick="chk_hash_fn();" type="submit"><?php xl('Login','e');?></button>
														<?php endif; ?>
												
											</div>
																								
															
									<div class="version">
										<?php echo "v$openemr_version" ?> | <a  href="../../acknowledge_license_cert.html" target="main"><?php xl('Acknowledgments, Licensing and Certification','e'); ?></a>
									</div>					
							</div>
						</form>
					</div>

	</body>
</html>
