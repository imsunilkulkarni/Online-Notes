<?php
require_once('../conf/db_conf.php');


$text_val = $_REQUEST['text_val'];
$desc = $_REQUEST['desc'];
$note_id = $_REQUEST['edit_id'];

if($note_id){
	// Update query here
	$sql = "UPDATE tbl_notes SET title='".$text_val."', description='".$desc."' WHERE Id=".$note_id;
	$result1 = $dbh->query($sql);
}else{ // Insert Here
	
	$sql = "INSERT INTO tbl_notes (title, description, created_at, updated_at, status) 
	VALUES('".$text_val."','".$desc."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."',1)";
	$result1 = $dbh->query($sql);
}



