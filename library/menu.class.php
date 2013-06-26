<?php
	require_once($GLOBALS['fileroot']."/interface/globals.php");
	require_once($GLOBALS['fileroot']."/library/acl.inc");
	require_once($GLOBALS['fileroot']."/custom/code_types.inc.php");
	require_once($GLOBALS['fileroot']."/library/patient.inc");
	require_once($GLOBALS['fileroot']."/library/lists.inc");
/**
*  
* This class is for managing menus.
*
*
* @package menu.class
* 
*/

class menu {
	
	
	/* We start creating the global necessary variables */
	private $tbl_menus;
	private $tbl_menu_items;
	//private $database;
	
	/**
	* Function constructor: Sets the initial vars and calls external batabase class
	* You might use your own here, be aware that we need some funciton such as query
	*
	*/
	public function __construct() {
		
		// Configure your table names
		$this->tbl_menus 		= "menus";
		$this->tbl_menu_items 	= "menu_items";
	}
	
	/**
	* Function get_menus()
	* Gets all the menus in database.
	*
	* @return sql 	Returns the sql result ready for arraing
	*
	*/
	public function get_menus() {
		$result = mysql_query('SELECT * FROM '.$this->tbl_menus.'');
		if (mysql_num_rows($result)) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	* Function get_menuDetails()
	* Funciton to get all the details from the database
	*
	* @param string 	The safe_name (it's a unique value on the table)
	*
	* @return array 	Returns an array with the details
	*
	*/
	public function get_menuDetails($safe_name) {
		$result = mysql_query("SELECT * FROM ".$this->tbl_menus." WHERE safe_name = '$safe_name'");
		while ($row = mysql_fetch_assoc($result)) {
			$menu_id 	= $row["id"];
			$menu_name 	= $row["menu_name"];
		}
		
		$details["menu_id"] 	= $menu_id;
		$details["menu_name"] 	= $menu_name;
		$details["safe_name"] 	= $safe_name;
		
		return $details;
		
	}
	
	/**
	* Function get_menuItems()
	* Function to get all the menu items from a specific menu_id
	*
	* @param integer 	The menu ID
	*
	* @return array 	Returns an array with all the items and their details
	*
	*/
	public function get_menuItems($menu_id) {
		// retrieve the left and right value of the $root node  
		$result = mysql_query("SELECT * FROM ".$this->tbl_menu_items." WHERE menu_id = '$menu_id'");
		if (mysql_num_rows($result)) {
			$right = array();
			$result = mysql_query("SELECT node.code, node.pdValue, node.frame, node.published, node.sAccess, node.lAccess, node.title, node.type, node.class_name, node.content, node.id AS id, node.lft AS lft, node.rgt AS rgt, (COUNT(parent.title) - 1) AS depth FROM ".$this->tbl_menu_items." AS node CROSS JOIN ".$this->tbl_menu_items." AS parent WHERE node.menu_id = '$menu_id' AND parent.menu_id = '$menu_id' AND node.lft BETWEEN parent.lft AND parent.rgt GROUP BY node.id ORDER BY node.lft");
			$tree = array();
			while ($row = mysql_fetch_assoc($result)) {
				$tree[] = $row;
			}
		} else {
			$tree = false;
		}
		return $tree;
	}
	
	/**
	* Function get_menuItems_NestedSetInput()
	* Function to get all the menu items from a specific menu_id and retrieve
	* as a HTML select input with idented values
	*
	* @param integer 	The menu ID
	*
	* @return string 	Returns HTML select input
	*
	*/
	public function get_menuItems_NestedSetInput($menu_id) {
		$tree = $this->get_menuItems($menu_id);
		if ($tree == false ) {
			$result = "<select name=\"parent\" id=\"parent\" >";
			$result .= '<option value="0"> - Add to root - </option>';
			$result .= '</select>';
		} else {
			// bootstrap loop
			$currDepth = 0;  // -1 to get the outer <ul>
			$result = "<select name=\"parent\" id=\"parent\" >";
			$result .= '<option value="0"> - Add to root - </option>';
			$ident = '';
			while (!empty($tree)) {
				$currNode = array_shift($tree);
				$currDepth = $currNode['depth'];
				if ($currDepth == 0) {
					$ident = '';
				} else if ($currDepth == 1) {
					$ident = '&nbsp;&nbsp;&nbsp;&nbsp;';
				} else if ($currDepth == 2) {
					$ident = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else if ($currDepth == 3) {
					$ident = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else if ($currDepth == 4) {
					$ident = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$result .= '<option value="'.$currNode['id'].'">'.$ident.$currNode['title'].'</option>';
			}
			$result .= "</select>";
		}
		$output = $result;
		return $output;
	}
	
	/**
	* Function getComponents()
	* Function to get all your components form your CMS
	* You have to create your own function so it can search for
	* components and create an HTML select input with values beig
	* your component ID and text as your component name
	*
	* @param string 	The name for the input
	* @param string 	The id for the input
	* @param string 	The class for the input
	*
	* @return string 	Returns HTML select input
	*
	*/
	public function getComponents($name, $id, $class) {
		$result = "<select name=\"$name\" id=\"$id\"  class=\"$class\" >";
		$result .= '<option value="1">Example News</option>';
		$result .= '<option value="2">Example Gallery</option>';
		$result .= "</select>";
		return $result;
	}
	
	/**
	* Function getContents()
	* Function to get all your articles form your CMS
	* You have to create your own function so it can search for
	* articles and create an HTML select input with values beig
	* your article ID and text as your article title
	*
	* @param string 	The name for the input
	* @param string 	The id for the input
	* @param string 	The class for the input
	*
	* @return string 	Returns HTML select input
	*
	*/
	public function getContents($name, $id, $class) {
		$result = "<select name=\"$name\" id=\"$id\"  class=\"$class\" >";
		$result .= '<option value="1">History</option>';
		$result .= '<option value="2">Team</option>';
		$result .= '<option value="3">Example Page</option>';
		$result .= '<option value="4">Another Page</option>';
		$result .= '<option value="5">And another one</option>';
		$result .= "</select>";
		return $result;
	}
	
	/**
	* Function add_menu()
	* Function to add a new menu to your database
	*
	* @param string 	New menu name
	* @param string 	New menu safe name
	*
	* @return string 	empty for ok or error message to be display by AJAX
	*
	*/
	public function add_menu($name, $safe_name) {
		$name 		= $this->check_input($name);
		$safe_name 	= $this->normalize_special_characters($safe_name);
		$result 	= mysql_query("SELECT * FROM ".$this->tbl_menus." WHERE safe_name = '$safe_name'");
		if (mysql_num_rows($result)) {
			return "There is an item with that safe_name already!";
		} else {
			if (mysql_query("INSERT INTO ".$this->tbl_menus." (id, menu_name, safe_name) VALUES('', '$name', '$safe_name')")) {
				return "";
			} else {
				return "Error inserting in database!";
			}
		}
	}
	
	/**
	* Function save_menu()
	* Function to save a specific menu to your database.
	* This function actually tries to understand what values has changed.
	* It can save menu name and safe_name if changed and all items if any has changed
	*
	* @param string 	The menu safe_name being the subject of changes
	* @param string 	The serialized array of all items
	* @param string 	The new menu name (might be the same though)
	* @param string 	The new menu safe_name (might be the same though)
	*
	* @return string 	empty for ok or error message to be display by AJAX
	*
	*/
	public function save_menu($menu, $serialized, $newname, $newsname) {
		
		// get menu details
		$menuDetails 	= $this->get_menuDetails($menu);
		
		// assign menu id
		$menu_id		= $menuDetails["menu_id"];
		
		// If menu name changed then update database
		if ($menuDetails["menu_name"] != $newname) {
			mysql_query("UPDATE ".$this->tbl_menus." SET menu_name = '$newname' WHERE id = $menu_id");
		}
		
		// If menu safe_name changed then update database
		if ($menuDetails["safe_name"] != $newsname) {
			mysql_query("UPDATE ".$this->tbl_menus." SET safe_name = '$newsname' WHERE id = $menu_id");
		}
		
		// Delete all items from that specific menu
		mysql_query("DELETE FROM ".$this->tbl_menu_items." WHERE menu_id = '$menu_id'");
		
		// Start adding all menu items again
		while (!empty($serialized)) {
			$currNode = array_shift($serialized);
			
			if ($currNode['depth'] == 0) {
				// its nestedSortable root, so ignore it ;)
			} else {
				
				// Because we have a root item in the nestedSortable array (serialized) we have to decrease left and right values by 1
				$lefty 		= $currNode['left'] - 1;
				$righty 	= $currNode['right'] - 1;
				$title 		= $currNode['item_title'];
				$title_safe = $this->normalize_special_characters(utf8_decode($currNode['item_title']));
				$class_name = $currNode['item_class'];
				$type 		= $currNode['item_type'];
				$content 	= $currNode['item_content'];
				$code		= $currNode['item_code'];
				$pdValue	= $currNode['item_pdValue'];
				$frame		= $currNode['item_frame'];
				$published	= $currNode['item_published'];
				$parent_id	= $currNode['parent_id'];
				$sAccess	= $currNode['item_sAccess'];
				$lAccess	= $currNode['item_lAccess'];
				$nlevel		= $currNode['depth'];

				
				mysql_query("INSERT INTO ".$this->tbl_menu_items." (id, menu_id, lft, rgt, title, title_safe, class_name, type, content, code, pdValue, frame, published, parent_id, sAccess, lAccess, nlevel) VALUES ('', '$menu_id', '$lefty', '$righty', '$title', '$title_safe', '$class_name', '$type', '$content', '$code', '$pdValue', '$frame', '$published', '$parent_id', '$sAccess', '$lAccess', '$nlevel')");
				
			}
		}
		return "";
	}
	
	/**
	* Function rem_menu()
	* Function to remove a menu and it's details from the database
	*
	* @param string 	The menu safe_name being the subject of deletion
	*
	* @return string 	empty for ok or error message to be display by AJAX
	*
	*/
	public function rem_menu($safe_name) {
		if ($safe_name != "main_menu") {
			$menuDetails 	= $this->get_menuDetails($safe_name);
			$menu_id		= $menuDetails["menu_id"];
			mysql_query("DELETE FROM ".$this->tbl_menus." WHERE id = $menu_id");
			mysql_query("DELETE FROM ".$this->tbl_menu_items." WHERE menu_id = $menu_id");
			return "";
		} else {
			return "Trying to remove Main Menu? Not possible...";
		}
	}
	
	/**
	* Function normalize_special_characters()
	* Replaces special characters with non-special equivalents
	*
	* @param  string 	The string
	*
	* @return string 	The converted string
	*
	*/
	private function normalize_special_characters($str) {
		$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
									'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
									'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
									'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
									'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' '=>'-', '/'=>'-', '\\'=>'-' );
		$str = strtr($str, $unwanted_array);
		$str = strtolower(preg_replace('/[^a-zA-Z0-9_-]/s', '', $str));
		return $str;
	}
	
	/**
	* Function check_input()
	* Escapes string for database insertion
	*
	* @param  string 	The string
	*
	* @return string 	The converted string
	*
	*/
	private function check_input($value) {
		// Stripslashes
		if (get_magic_quotes_gpc()) {
		  $value = stripslashes($value);
		}
		// MYSQL INJECTION SAFE
		if (!is_numeric($value)) {
			$value = "" . mysql_real_escape_string($value) . "";
		}
		return $value;
	}
	
	
	
	
	function get_countDepth($menu_id, $depth) {
		$result = mysql_query("SELECT node.title, node.type, node.class_name, node.content, node.id AS id, node.lft AS lft, node.rgt AS rgt, (COUNT(parent.title) - 1) AS depth FROM ".$this->tbl_menu_items." AS node CROSS JOIN ".$this->tbl_menu_items." AS parent WHERE node.menu_id = '$menu_id' AND parent.menu_id = '$menu_id' AND node.lft BETWEEN parent.lft AND parent.rgt GROUP BY node.id ORDER BY node.lft");
		$x = 0;
		while ($row = mysql_fetch_assoc($result)) {
			if ($row["depth"] == $depth) {
				$x++;
			}
		}
		return $x;
	}
	
	
	
	
	/**
	* Function get_menuItems_NestedSetInput()
	* Function to get all the menu items from a specific menu_id and retrieve
	* as a HTML select input with idented values
	*
	* @param integer 	The menu ID
	*
	* @return string 	Returns HTML select input
	*
	*/
	public function render_menu($safe_name, $ol_id, $orientation = "horizontal", $color = "") {
		
		$menuDetails = $this->get_menuDetails($safe_name);
		
		$tree = $this->get_menuItems($menuDetails["menu_id"]);
		if ($tree == false ) {
			$result = "Menu has no items available";
		} else {
			// bootstrap loop
			$result 		= '<ol id="'. $ol_id.'" class="simple_menu simple_menu_css '.$orientation.' '.$color.'">';
			
			$currDepth 		= 0;  				// Start from what depth (0 to get the outer <ul> - NOT RECOMENDED)
			$total			= count($tree) - 1;	// Total of menu items (according to previous first foreach loop above) minus one because we have a 0 key in array eith no menu items
			$x				= 0;				// Reset X for counting next while loop
			$Dep1Count		= 0;				// Counter for depth 1 items
			$depth1			= $this->get_countDepth($menuDetails["menu_id"], 1);
			while (!empty($tree)) {
				$x++;
				$currNode = array_shift($tree);
				
					if ($currNode['depth'] == 1) { $Dep1Count++; };
				
					// Level down?
					if ($currNode['depth'] > $currDepth) {
						// Yes, open <ul>
						$result .= '<ol>';
					} else if ($x > 1) {
						$result .= '</li>';
					}
					
					// Level up?
					if ($currNode['depth'] < $currDepth) {
						// Yes, close n open <ul>
						$result .= str_repeat('</ol></li>', $currDepth - $currNode['depth']);
					}
					
					
					// Checks if it is last item OR last depth one item
					if (($x == $total) or ($Dep1Count == $depth1)) { 
						$lastClass = "last"; 
					} else {
						$lastClass = ""; 
					}
					
					
					// What to do with each type of link ?
					if ($currNode['type'] == "url") {
						$url = $this->get_menuItem_URL($currNode['id']);
					} else if ($currNode['type'] == "component") {
						$url = $this->get_menuItem_COMP($currNode['id']);
					} else if ($currNode['type'] == "content") {
						$url = $this->get_menuItem_CONT($currNode['id']);
					} else {
						$url = "#";
					}
					
					
					// Always add node. Checks if it is last item OR last depth one item
					if ($x == $total) {
						$result 	.= '<li class="'.$lastClass.'"><a href="'.$url.'">'.$currNode['title'].'</a></li>';
					} else {
						$result 	.= '<li class="'.$lastClass.'"><a href="'.$url.'">'.$currNode['title'].'</a>';
					}
					
					// Adjust current depth
					$currDepth 	= $currNode['depth'];
					
					// Are we finished?
					if (empty($tree)) {
						// Yes, close n open <ul>'s and correspondig <li>'s
						$result .= str_repeat('</ol></li>', $currDepth);
					}
				
			}
			$result .= "</ol>";
			
			
			$result .= "<br clear=\"all\" />
						<script>
							$('ol#".$ol_id."').simpleMenu();
						</script>";
			
		}
		$output = $result;
		return $output;
	}
	
	public function buildMenu($safe_name, $ol_id, $orientation = "horizontal", $color = "") {
		
		$menuDetails = $this->get_menuDetails($safe_name);
		
		$tree = $this->get_menuItems($menuDetails["menu_id"]);
		if ($tree == false ) {
			$result = "Menu has no items available";
		} else  {
			// bootstrap loop
			$result 		= '<ul id="'. $ol_id.'" class="simple_menu simple_menu_css '.$orientation.' '.$color.'">';
			
			$currDepth 		= 0;  				// Start from what depth (0 to get the outer <ul> - NOT RECOMENDED)
			$total			= count($tree) ;	// Total of menu items (according to previous first foreach loop above) minus one because we have a 0 key in array eith no menu items
			$x				= 0;				// Reset X for counting next while loop
			$Dep1Count		= 0;				// Counter for depth 1 items
			$depth1			= $this->get_countDepth($menuDetails["menu_id"], 1);
			while (!empty($tree)) {
			
				$x++;
				$currNode = array_shift($tree);
				if ((acl_check($currNode['sAccess'], $currNode['lAccess'])) || (empty($currNode['sAccess']))) {
					if ($currNode['published'] == 1) {
										
						$parent = $currNode['rgt'] - $currNode['lft'];
						$id = $currNode['code'].$currNode['pdValue'];
						
						if ($currNode['depth'] == 1) { $Dep1Count++; };
						
						// Level down?
						if ($currNode['depth'] > $currDepth) {
							// Yes, open <ul>
							$result .= '<ul>';
							
						} else if ($x > 1) {
							$result .= '</li>';
						}
						
						if ($currNode['depth'] == 0) {
							$class = "collapsed";
						} elseif ($parent > 2 || $currNode['title'] == "Visit Form") {
							$class = "collapsed_lv2"; 
						}
						else {
						$class = ""; 
						}
						
						// Level up?
						if ($currNode['depth'] < $currDepth) {
							// Yes, close n open <ul>
							$result .= str_repeat('</ul></li>', $currDepth - $currNode['depth']);
						}
						
						
						// Checks if it is last item OR last depth one item
						if ($x == $total) { 
							$lastClass = "last"; 
						} else {
							$lastClass = ""; 
						}
						
						
						// What to do with each type of link ?
						if ($currNode['type'] == "url") {
							$url = $this->get_menuItem_URL($currNode['id']);
						} else if ($currNode['type'] == "component") {
							$url = $this->get_menuItem_COMP($currNode['id']);
						} else if ($currNode['type'] == "content") {
							$url = $this->get_menuItem_CONT($currNode['id']);
						} else {
							$url = "#";
						}
						
						
						// Always add node. Checks if it is last item OR last depth one item
						if ($x == $total) {
							$result 	.= '<li><a class="'.$class.'" href="'.$url.'">'.$currNode['title'].'</a></li>';
						} elseif ($currNode['title'] == "Visit Form")  {
							$result 	.= '<li><a class="'.$class.'" href="'.$url.'"><span>'.$currNode['title'].'</span></a><ul>';
							
							include_once("$srcdir/registry.inc");
								$reg = getRegistered();
								if (!empty($reg)) {
								foreach ($reg as $entry) {
									$option_id = $entry['directory'];
									$title = trim($entry['nickname']);
										if ($option_id == 'fee_sheet' ) continue;
										if ($option_id == 'newpatient') continue;
								  if (empty($title)) $title = $entry['name'];

								$a1 = 'cod2';
								$a2 = 'RBot';
								$a3 = 'patient_file/encounter/load_form.php?formname='.urlencode($option_id);
								
							
								$result 	.= '<li><a href id="'.$a1.'" onclick="return loadFrame2(\''.$a1.'\', \''.$a2.'\', \''.$a3.'\')" >'.$title.'</a>';
								 
								 }
								$result 	.= '</ul>';
							}
						} else   {
							$result 	.= '<li><a href="#" class="'.$class.'" id="'.$id.'" onclick="return loadFrame2(\''.$id.'\', \''.$currNode['frame'].'\', \''.$currNode['content'].'\')"><span>'.$currNode['title'].'</span></a>';
						}
						
						// Adjust current depth
						$currDepth 	= $currNode['depth'] ;
						
						// Are we finished?
						if (empty($tree)) {
							// Yes, close n open <ul>'s and correspondig <li>'s
							$result .= str_repeat('</ul></li>', $currDepth);
						}
					}
				}
			}
			
			$result .= "</ul>";
			
			
		}
		$output = $result;
		
		return $output;
	
	}
	
	
	
	
	function get_menuItem_URL($id) {
		$result = mysql_query("SELECT content FROM ".$this->tbl_menu_items." WHERE id = $id");
		while ($row = mysql_fetch_assoc($result)) {
			$return = $row["content"];
		}
		return $return;
	}
	
	function get_menuItem_COMP($id) {
		$result = mysql_query("SELECT content FROM ".$this->tbl_menu_items." WHERE id = $id");
		while ($row = mysql_fetch_assoc($result)) {
			$return = $row["content"];
		}
		// NOW YOU SHOULD DO SOMETHING WITH YOUR COMPONENT ID
		// First explode the content by a | to get both component ID and component auxiliary id (category?)
		return "#COMPONENT-".$return;
	}
	
	function get_menuItem_CONT($id) {
		$result = mysql_query("SELECT content FROM ".$this->tbl_menu_items." WHERE id = $id");
		while ($row = mysql_fetch_assoc($result)) {
			$return = $row["content"];
		}
		// NOW YOU SHOULD DO SOMETHING WITH YOUR CONTENT ID
		return "#CONTENT-".$return;
	}
	
	 function genTreeLink($frame, $name, $title, $mono=false) {
		  global $primary_docs, $disallowed;
		  if (empty($disallowed[$name])) {
		   $id = $name . $primary_docs[$name][1];
		   echo "<li><a href='' id='$id' onclick=\"";
		   if ($mono) {
			if ($frame == 'RTop')
			 echo "forceSpec(true,false);";
			else
			 echo "forceSpec(false,true);";
		   }
		   echo "return loadFrame2('$id','$frame','" .
				$primary_docs[$name][2] . "')\">" . $title . ($name == 'msg' ? ' <span id="reminderCountSpan" class="bold"></span>' : '')."</a></li>";
		  }
		 }
		 
	function genMiscLink($frame, $name, $level, $title, $url, $mono=false) {
		  //global $disallowed;
		  //if (empty($disallowed[$name])) {
		   $id = $name . $level;
		   echo "<li><a href='' id='$id' onclick=\"";
		   if ($mono) {
			if ($frame == 'RTop')
			 echo "forceSpec(true,false);";
			else
			 echo "forceSpec(false,true);";
		   }
		   echo "return loadFrame2('$id','$frame','" .
				$url . "')\">" . $title . "</a></li>";
		  //}
		 }
		 
		 function genPopLink($title, $url, $linkid='') {
		  echo "<li><a href='' ";
		  if ($linkid) echo "id='$linkid' ";
		  echo "onclick=\"return repPopup('$url')\"" .
			   ">" . $title . "</a></li>";
		 }
		 
		 function genDualLink($topname, $botname, $title) {
		  global $primary_docs, $disallowed;
		  if (empty($disallowed[$topname]) && empty($disallowed[$botname])) {
		   $topid = $topname . $primary_docs[$topname][1];
		   $botid = $botname . $primary_docs[$botname][1];
		   echo "<li><a href='' id='$topid' " .
				"onclick=\"return loadFrameDual('$topid','$botid','" .
				$primary_docs[$topname][2] . "','" .
				$primary_docs[$botname][2] . "')\">" . $title . "</a></li>";
		  }
		 }

		function genPopupsList($style='') {
		  global $disallowed, $webserver_root;
		  
		 } 
	
	
	
	
	
	
}
?>