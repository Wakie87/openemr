<?php

require_once("../interface/globals.php");

if (isset($_POST['task'])){
    $task = $_POST['task'];  
}else{
    $task = "";
}


if ($task == "add_menu") {
	include("menu.class.php");
	$menu 		= new menu;
	$name 		= $_POST['n'];
	$safe_name 	= $_POST['s'];
	$res 		= $menu->add_menu($name, $safe_name);
	echo $res;
	
	
	
} else if ($task == "save_menu") {
	include("menu.class.php");
	$menu 		= new menu;
	$menu2 		= $_POST['menu'];
	$serialized = $_POST['s'];
	$newname 	= $_POST['nn'];
	$newsname 	= $_POST['nsn'];
	$res 		= $menu->save_menu($menu2, $serialized, $newname, $newsname);
	echo $res;
	
	
	
} else if ($task == "rem_menu") {
	include("menu.class.php");
	$menu 		= new menu;
	$menu2 		= $_POST['menu'];
	
	$res 		= $menu->rem_menu($menu2);
	echo $res;
	
	
	
}




?>