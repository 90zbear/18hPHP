<?php
ini_set('display_errors',1);
require_once 'config.php';
require_once 'db.php';

// var_dump($_POST);
if($_POST){
	if( $_POST['pid'] != NULL || $_POST['content'] != NULL ){
		connect_db('edit_post', $_POST);
	}
}
?>