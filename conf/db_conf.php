<?php 
	try {
		$dbh = new PDO('mysql:host=localhost;dbname=db_notes', 'root', '');
		// foreach($dbh->query('SELECT * from tbl_notes') as $row) {
			// print_r($row);
		// }
		// $dbh = null;
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}