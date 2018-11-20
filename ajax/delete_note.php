<?php
require_once('../conf/db_conf.php');

$note_id = $_GET['note_id'];

if($note_id){
	$sql = "UPDATE tbl_notes SET status=0 WHERE Id=".$note_id;
	$result1 = $dbh->query($sql);
	
}
