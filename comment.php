<?php
ini_set('display_errors',1);
require_once 'config.php';
require_once 'db.php';

// var_dump($_POST);
if($_POST){
	if( $_POST['title'] != NULL || $_POST['ccontent'] != NULL ){
		connect_db('comment_write', $_POST);
		var_dump($_POST);
	}
}
?>