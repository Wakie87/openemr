<!DOCTYPE HTML>
<?php
/**
 * main_title.php - The main titlebar, at the top of the 'concurrent' layout.
 */

include_once('../globals.php');
?>
<html>
<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
		<!--<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">-->
		<link rel=stylesheet href="../../library/css/bootstrap.css" type="text/css">
		<script language='JavaScript' src="../../library/js/jquery-1.9.1.min.js"></script>
		<script language='JavaScript' src="../../library/js/bootstrap.js"></script>

<style type="text/css">
      .hidden {
        display:none;
      }
      .visible{
        display:block;
      }
</style>

<script type="text/javascript" language="javascript">
function toencounter(rawdata) {
//This is called in the on change event of the Encounter list.
//It opens the corresponding pages.
	document.getElementById('EncounterHistory').selectedIndex=0;
	if(rawdata=='')
	 {
		 return false;
	 }
	else if(rawdata=='New Encounter')
	 {
	 	top.window.parent.left_nav.loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=')
		return true;
	 }
	else if(rawdata=='Past Encounter List')
	 {
	 	top.window.parent.left_nav.loadFrame2('pel1','RBot','patient_file/history/encounters.php')
		return true;
	 }
    var parts = rawdata.split("~");
    var enc = parts[0];
    var datestr = parts[1];
    var f = top.window.parent.left_nav.document.forms[0];
	frame = 'RBot';
    if (!f.cb_bot.checked) frame = 'RTop'; else if (!f.cb_top.checked) frame = 'RBot';

    top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
    parent.left_nav.setEncounter(datestr, enc, frame);
    parent.left_nav.setRadio(frame, 'enc');
    top.frames[frame].location.href  = '../patient_file/encounter/encounter_top.php?set_encounter=' + enc;
<?php } else { ?>
    top.Title.location.href = '../patient_file/encounter/encounter_title.php?set_encounter='   + enc;
    top.Main.location.href  = '../patient_file/encounter/patient_encounter.php?set_encounter=' + enc;
<?php } ?>
}
function showhideMenu() {
	var m = parent.document.getElementById("fsbody");
	var targetWidth = '0,*';
	if (m.cols == targetWidth) {
		m.cols = '<?php echo $GLOBALS['gbl_nav_area_width'] ?>,*';
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars( xl('Hide Menu'), ENT_QUOTES); ?>';
	} else {
		m.cols = targetWidth;
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars( xl('Show Menu'), ENT_QUOTES); ?>';
	}
}
</script>
</head>
<body class="body_title">
	<?php
		$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
	?>

	<div class="main_nav">
		<div class="title_left">
			<?php if ($GLOBALS['concurrent_layout']) { ?>
				<div class="menu_hide" style="float:left; margin: 5px 10px 5px;" >	
					<a class="btn" href='main_title.php' id='showMenuLink' style='width:73px' onclick='javascript:showhideMenu();return false;'><?php xl('Hide Menu','e'); ?></a>
					<?php if (acl_check('patients','demo','',array('write','addonly') )) { ?>
					<a class="btn" href='' id='new0' onClick=" return top.window.parent.left_nav.loadFrame2('new0','RTop','new/new.php')"><?php xl('New Patient','e'); ?></a>
					<a class="btn" href='' style="margin:0px;vertical-align:top;display:none;" id='clear_active' onClick="javascript:parent.left_nav.clearactive();return false;"><?php xl('Clear Active Patient','e'); ?></a>
					<?php } ?>
				</div>
			<?php }  ?>
		</div>
		
		<div class="title_middle">
		
			<div style='margin: 10px; float:left; display:none' id="current_patient_block">
				<span class='text'><b><?php xl('Patient: ','e'); ?></b></span><span class='title_bar_top' id="current_patient"><b><?php xl('None','e'); ?></b></span>
			</div>
			
			<div style='margin: 5px 10px 5px; float:left; display:none' id="past_encounter_block">
				<span class='title_bar_top' id="past_encounter"><b><?php xl('None','e'); ?></b></span>
			</div>
			
		
			<div style='margin: 10px; float:left; display:none' id="current_encounter_block" >
				<span class='text'><?php xl('Selected Encounter: ','e'); ?></span><span class='title_bar_top' id="current_encounter"><b><?php xl('None','e'); ?></b></span>
			</div>
	
		</div>
	
		<div class="title_right" style="float:right;">
		
			<div style="float:Left; margin: 10px;">
				<span class="text title_bar_top" title="<?php echo htmlspecialchars( xl('Authorization group') .': '.$_SESSION['authGroup'], ENT_QUOTES); ?>"><b><?php echo xl('Logged in as:','e'); ?></b><?php echo htmlspecialchars($res{"fname"}.' '.$res{"lname"},ENT_NOQUOTES); ?></span>
			</div>
			
			
			<div style="float:right;" class="btn-group">
				<div class="btn-group" style="margin: 5px 10px 5px;">
				  <a class="btn" href='main_title.php' onclick="javascript:parent.left_nav.goHome();return false;" ><?php xl('Home','e'); ?></a>
				  <a class="btn" href='http://open-emr.org/wiki/index.php/OpenEMR_4.1.1_Users_Guide' target="_blank" id="help_link" ><?php xl('Manual','e'); ?></a>
				  <a class="btn" href='../logout.php?auth=logout' target="_top" id="logout_link" onclick="top.restoreSession()" ><?php xl('Logout','e'); ?></a>
				</div>
			</div>
		</div>
	</div>	
	
	
<script type="text/javascript" language="javascript">
parent.loadedFrameCount += 1;
</script>

</body>
</html>
