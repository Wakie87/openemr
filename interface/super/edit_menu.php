<?php

require_once("../globals.php");
require_once($GLOBALS['fileroot']."/library/menu.class.php");
require_once($GLOBALS['fileroot']."/library/acl.inc");

?>

<!doctype html>

<html lang="en">
	
	<head>
		<meta charset="utf-8">

		<title>Menu Manager</title>
			
		<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery-ui-1.8.16.custom.css"/>
		<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/menu.css"/>
		
		<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/menu/jquery-1.6.4.min.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/menu/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/menu/jquery-ui.nestedSortable.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/menu/jquery.blockUI.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/menu/jquery.query.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/menu/menu.js"></script>
		
	</head>
	
	<body>

		<header>
			<h1>OpenEMR Menu Manager</h1>
		</header>
		<br/>
		
		<section id="menu_manager">
			<?php
			$menu 				= new menu;
			
			if (isset($_GET["menu_selector"])) {
				$actual_menu 	= $_GET["menu_selector"];
			} else {
				$actual_menu 	= "main_menu";
			}
			
			$menus 				= $menu->get_menus();								// Gets all menus in database
			$menu_details 		= $menu->get_menuDetails($actual_menu);				// Gets current menu details (name, id, safe_name)
			$menu_items 		= $menu->get_menuItems($menu_details["menu_id"]);	// Gets all database saved menu items for current menu
			?>
			
					<?php
		print "<pre>";
		//print_r($_POST['s']);
		print "</pre>";
		
		?>
			
			<!-- These three hidden inputs are very important 
			**** 
			**** current_menu_name 			-> Holds the current menu name
			**** current_menu_safe_name 	-> Holds the current menu safe_name
			**** current_menu_serialized 	-> Holds the current menu original database serialization of menu records. Used to compare to changes and activating save button
			-->
			<input type="hidden" id="current_menu_name" name="current_menu_namee" value="<?php echo $menu_details["menu_name"]; ?>" />
			<input type="hidden" id="current_menu_safe_name" name="current_menu_safe_namee" value="<?php echo $menu_details["safe_name"]; ?>" />
			<input type="hidden" id="current_menu_serialized" name="current_menu_serialized" value="" />
			
			<!-- Actual menu information, menu selector and nem menu button -->
			<div class="main_options">
				<div class="current_menu">
					<div class="cname"><?php echo $menu_details["menu_name"]; ?></div>
					<div class="csname"><?php echo $menu_details["safe_name"]; ?></div>
				</div>
				
				<a id="create_menu" class="add_menu" href="#">Add menu</a>
				<form class="change_menu" id="change_menu" name="change_menu" method="get" action="">
					<label for="menu_selector">Select Menu&nbsp;&nbsp;</label>
					<select name="menu_selector" id="menu_selector">
						<?php
							while ($row = mysql_fetch_assoc($menus)) {
								if ($actual_menu == $row["safe_name"]) {
									echo '<option selected value="'.$row["safe_name"].'">'.$row["menu_name"].'</option>';
								} else {
									echo '<option value="'.$row["safe_name"].'">'.$row["menu_name"].'</option>';
								}
							}
						?>
					</select>
				</form>
				<br clear="all" />
			</div>
			
			<!-- Actual menu options -->
			<div class="current_menu_options">
				<a id="add_item" class="add_item" href="#">Add Item</a>
				<a id="save_menu" class="save_menu" href="include/menu.processor.php" style="display: none;" >Save Menu</a>
				<a id="edit_menu" class="edit_menu" href="">Edit Menu</a>
				<form class="preview_menu_form" id="preview_menu_form" name="preview_menu_form" method="post" action="menu_preview.php" target="_blank">
					<input type="hidden" id="current_menu_nr_items" name="current_menu_nr_items" value="" />
					<a id="preview_menu" class="preview_menu" href="#">Preview Menu</a>
				</form>
				<?php
					if ($actual_menu != "main_menu") {
						echo '<a id="remove_menu" class="remove_menu" href="#'.$menu_details["safe_name"].'">Remove Menu</a>';
					}
				?>
				<br clear="all" />
			</div>
			
			<!-- Notifications container. All start with display: none. JQuery handles show/hide/msg/type -->
			<div id="notification_container">
				<div id="notify_green" class="notify_green" style="display: none;">
					<div class="cross">x</div>
					<div class="content">There are no items for this menu yet. Please add new items now!</div>
					<br clear="all" />
				</div>
				
				<div id="notify_red" class="notify_red" style="display: none;">
					<div class="cross">x</div>
					<div class="content">There are no items for this menu yet. Please add new items now!</div>
					<br clear="all" />
				</div>
				
				<div id="notify_yellow" class="notify_yellow" style="display: none;">
					<div class="cross">x</div>
					<div class="content">There are no items for this menu yet. Please add new items now!</div>
					<br clear="all" />
				</div>
				
				<div id="notify_blue" class="notify_blue" style="display: none;">
					<div class="cross">x</div>
					<div class="content">There are no items for this menu yet. Please add new items now!</div>
					<br clear="all" />
				</div>
			</div>
			
			<?php
			// Checks is current menu has items ?
			if ($menu_items == false ) {
				?>
				<script>
					enableNotification("notify_yellow", "There are no items for this menu yet. Please add new items now!", 10000);
				</script>
				
				<div class="titles">
					<div class="handle">Handle</div>
					<div class="title">Title</div>
					<!-- <div class="class">Class</div> -->
					<div class="content">Content</div>
					<div class="type">Type</div>
					<div class="tasks">Tasks</div>
					<br clear="all" />
				</div>
				
				<!-- This <ol> is required even when menu has no items to allow JQuery insert new items -->
				<ol class="sortable"></ol>
				<?php
			} else {
				?>
				<div class="titles">
					<div class="handle">Handle</div>
					<div class="title">Title</div>
					<!-- <div class="class">Class</div> -->
					<div class="content">Content</div>
					<div class="type">Type</div>
					<div class="tasks">Tasks</div>
					<br clear="all" />
				</div>
				
				<ol class="sortable">
				<?php
				// bootstrap loop
				$result 	= '';
				$currDepth 	= 0;  // -1 to get the outer <ul>
				$x			= 1;
				
				while (!empty($menu_items)) {
					$currNode = array_shift($menu_items);
					
					// Level down?
					if ($currNode['depth'] > $currDepth) {
						// Yes, open new <ol>
						$result .= '<ol>';
					} else if ($x > 1) {
						// No, closes previous <li> then
						$result .= '</li>';
					}
					
					
					// Level up?
					if ($currNode['depth'] < $currDepth) {
						// Yes, close x number of open <ol> and respective <li>
						$result .= str_repeat('</ol></li>', $currDepth - $currNode['depth']);
					}
					
					/* Always add a new node
					**
					** Notice here that we use a span for field output
					** and a hidden input for the actual value. It might be usefull
					** for example if you want to show the article/content name on the gui
					** but the hidden input holds the article/content ID from the database
					*/
					$result .= '<li id="list_'.$x.'">	
									<div class="li_content">
										<div class="handle"></div>
										
										<span class="title">'.$currNode['title'].'&nbsp;</span>
										<input type="hidden" class="db_title" name="db_title" value="'.$currNode['title'].'" />
										
										<div class="tasks"><a class="edit edit_item_btn" href="#'.$x.'"></a><a class="remove rem_item_btn" href="#'.$x.'"></a></div>
										
										<span class="type">'.$currNode['type'].'&nbsp;</span>
										<input type="hidden" class="db_type" name="db_type" value="'.$currNode['type'].'" />
										
										<input type="hidden" class="db_class_name" name="db_class_name" value="'.$currNode['class_name'].'" />
										
										<span class="content">'.$currNode['content'].'&nbsp;</span>
										<input type="hidden" class="db_content" name="db_content" value="'.$currNode['content'].'" />
										
										<input type="hidden" class="db_code" name="db_code" value="'.$currNode['code'].'" />
										
										<input type="hidden" class="db_pdValue" name="db_pdValue" value="'.$currNode['pdValue'].'" />
										
										<input type="hidden" class="db_frame" name="db_frame" value="'.$currNode['frame'].'" />
										
										<input type="hidden" class="db_published" name="db_published" value="'.$currNode['published'].'" />
										
										<input type="hidden" class="db_sAccess" name="db_sAccess" value="'.$currNode['sAccess'].'" />
										
										<input type="hidden" class="db_lAccess" name="db_lAccess" value="'.$currNode['lAccess'].'" />
										
																				
										<br clear="all" />
									</div>';
									

					
					// Adjust current depth
					$currDepth = $currNode['depth'];
					
					// Are we finished?
					if (empty($menu_items)) {
						// Yes, close the open <ol> and <li>
						$result .= str_repeat('</ol></li>', $currDepth + 1);
					}
					
					$x++;
					
				}
				echo $result;
				
				?>
				</ol>
				<?php
			}
			?>
				
			<br/>
			<br/>
			
			<!-- Create New Menu JQuery UI Modal Form -->
			<div id="create_menu-form" title="Create a new menu">
				<p class="validateTips">All form fields are required.</p>
				<form id="create_menu_form">
				<fieldset>
					<label for="name">Menu Name</label>
					<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
					<br clear="all" />
					<label for="safe_name">Menu Safe Name</label>
					<input type="text" name="safe_name" id="safe_name" class="text ui-widget-content ui-corner-all" />
					<br clear="all" />
				</fieldset>
				</form>
			</div>
			
			<!-- Create New Menu ITEM JQuery UI Modal Form -->
			<div id="create_menu_item-form" title="Create a new (<?php echo $menu_details["menu_name"]; ?>) item">
				<p class="validateTips">All form fields are required.</p>
				<form id="create_menu_form">
				<fieldset>
					<label for="parent">Parent</label>
					<div id="parent_container"><?php echo $menu->get_menuItems_NestedSetInput($menu_details["menu_id"]); ?></div>
					<br clear="all" />
					<br/>
					
					<label for="title">Title</label>
					<input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all" />
					<br clear="all" />
					<br/>
					
					<label for="class_name">Class name</label>
					<input type="text" name="class_name" id="class_name" class="text ui-widget-content ui-corner-all" />
					<br clear="all" />
					<br/>
					
					<label for="type">Type</label>
					<select name="type" id="type">
						<option value="url">Url</option>
						<option value="separator">Separator</option>
						<option value="component">Component</option>
						<option value="content">Content</option>
					</select>
					<br clear="all" />
					<br/>
					
					<div id="url_input_holder">
						<label for="url">Url</label>
						<input type="text" name="url" id="url" class="text ui-widget-content ui-corner-all" />
						<br clear="all" />
						<br/>
					</div>
					
					<div id="component_input_holder" style="display: none;">
						<label for="component">Component</label>
						<?php echo $menu->getComponents("component", "component", "select ui-widget-content ui-corner-all"); ?>
						<br clear="all" />
						<br/>
					</div>
					
					<div id="component_id_input_holder" style="display: none;">
						<label for="component_id">Component Id</label>
						<input type="text" name="component_id" id="component_id" class="text ui-widget-content ui-corner-all" />
						<br clear="all" />
						<br/>
					</div>
					
					<div id="content_input_holder" style="display: none;">
						<label for="content">Content</label>
						<?php echo $menu->getContents("content", "content", "select ui-widget-content ui-corner-all"); ?>
						<br clear="all" />
					</div>
					
				</fieldset>
				</form>
			</div>
			
			<!-- Edit Menu JQuery UI Modal Form -->
			<div id="edit_menu-form" title="Edit - <?php echo $menu_details["menu_name"]; ?>">
				<?php
					if ($actual_menu == "main_menu") {
						?>
						<p class="warning">This is the main menu. Safe name cannot be changed.</p>
						<?php
					}
				?>
				<p class="validateTips">All form fields are required.</p>
				<form id="create_menu_form">
				<fieldset>
					<label for="name_edit">Menu Name</label>
					<input type="text" name="name_edit" id="name_edit" class="text ui-widget-content ui-corner-all" value="<?php echo $menu_details["menu_name"]; ?>" />
					<br clear="all" />
					<label for="safe_name_edit">Menu Safe Name</label>
					<input type="text" name="safe_name_edit" id="safe_name_edit" class="text ui-widget-content ui-corner-all" value="<?php echo $menu_details["safe_name"]; ?>" />
					<br clear="all" />
				</fieldset>
				</form>
			</div>
			
			<!-- Edit Menu ITEM JQuery UI Modal Form -->
			<div id="edit_menu_item-form" title="Edit menu item">
				<p class="validateTips">All form fields are required.</p>
				<form id="create_menu_form">
				<fieldset>
					
					<label for="title_edit">Title</label>
					<input type="text" name="title_edit" id="title_edit" class="text ui-widget-content ui-corner-all" />
					<br clear="all" />
					<br/>
					
					<label for="class_name_edit">Class name</label>
					<input type="text" name="class_name_edit" id="class_name_edit" class="text ui-widget-content ui-corner-all" />
					<br clear="all" />
					<br/>
					
					<label for="type_edit">Type</label>
					<select name="type_edit" id="type_edit">
						<option value="url">Url</option>
						<option value="separator">Separator</option>
						<option value="component">Component</option>
						<option value="content">Content</option>
					</select>
					<br clear="all" />
					<br/>
					
					<div id="url_input_holder_edit">
						<label for="url_edit">Url</label>
						<input type="text" name="url_edit" id="url_edit" class="text ui-widget-content ui-corner-all" />
						<br clear="all" />
						<br/>
					</div>
					
					<div id="component_input_holder_edit" style="display: none;">
						<label for="component_edit">Component</label>
						<?php echo $menu->getComponents("component_edit", "component_edit", "select ui-widget-content ui-corner-all"); ?>
						<br clear="all" />
						<br/>
					</div>
					
					<div id="component_id_input_holder_edit" style="display: none;">
						<label for="component_id_edit">Component Id</label>
						<input type="text" name="component_id_edit" id="component_id_edit" class="text ui-widget-content ui-corner-all" />
						<br clear="all" />
						<br/>
					</div>
					
					<div id="content_input_holder_Edit" style="display: none;">
						<label for="content_edit">Content</label>
						<?php echo $menu->getContents("content_edit", "content_edit", "select ui-widget-content ui-corner-all"); ?>
						<br clear="all" />
					</div>
					
					<div id="code_input_holder_edit">
						<label for="code_edit">Code</label>
						<input type="text" name="code_edit" id="code_edit" class="text ui-widget-content ui-corner-all" />
						<br clear="all" />
						<br/>
					</div>

				
						<label for="pdValue_edit">Group</label>
						<select name="pdValue_edit" id="pdValue_edit">
							<option value="0">Global</option>
							<option value="1">Patient Specific</option>
							<option value="2">Encounter Specific</option>
						</select>
						<br clear="all" />
						<br/>

					<label for="frame_edit">Frame</label>
						<select name="frame_edit" id="frame_edit">
							<option value="RTop">Top Frame</option>
							<option value="RBot">Bottom Frame</option>
							<option value="">Header</option>
						</select>
						<br clear="all" />
						<br/>
					

					
						<label for="published_edit">Published</label>
						<select name="published_edit" id="published_edit">
							<option value="1">Enabled</option>
							<option value="0">Disabled</option>
						</select>
						<br clear="all" />
						<br/>

					
					<label for="sAccess_edit">Section Access</label>
					<select name="sAccess_edit" id="sAccess_edit">
							<option value="">None</option>
							<option value="admin">Administration</option>
							<option value="acct">Accounting</option>
							<option value="patients">Patient Information</option>
							<option value="encounters">Encounter Information</option>
							<option value="sensitivities">Sensitivities</option>
							<option value="lists">Lists</option>
							<option value="placeholder">Placeholder</option>
							<option value="nationnotes">Nation Notes</option>
							<option value="patientportal">Patient Portal</option>
							
						</select>
						<br clear="all" />
						<br/>


					<div id="lAccess_input_holder_edit">
						<?php 
						$test = array ();
						$test = acl_get_section_acos('admin');
						print "<pre>";
						print_r($test);
						print "</pre>";
						?>
						<label for="lAccess_edit">lAccess</label>
						<input type="text" name="lAccess_edit" id="lAccess_edit" class="text ui-widget-content ui-corner-all" />
						<br clear="all" />
						<br/>
					</div>
				
					
				</fieldset>
				</form>
			</div>
			
			<!-- Delete Menu JQuery UI Confirmation Modal -->
			<div id="delete_menu-confirm" title="Delete (<?php echo $menu_details["menu_name"]; ?>) ?">
				<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This will permanently delete the entire menu from the database. Are you sure?</p>
			</div>
			
			<!-- Delete Menu ITEM JQuery UI Confirmation Modal -->
			<div id="delete_item-confirm" title="Delete this item menu?">
				<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This will delete the selected item menu. Are you sure?</p>
			</div>
			
		</section>

	</body>
</html>