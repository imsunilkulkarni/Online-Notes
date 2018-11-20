<?php
session_start();
// print_R($_REQUEST);die;
// initializing variables

$username = "";
$email    = "";
$errors = array(); 

// connect to the database
require_once('conf/db_conf.php');

// REGISTER USER
if (isset($_POST['register'])) {
  // receive all input values from the form
  global $dbh;
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password_1 = $_POST['password_1'];
  $password_2 = $_POST['password_2'];

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = array();
  foreach($dbh->query($user_check_query) AS $key=>$value) {
	  foreach($value AS $key1=>$value1) {
		  $result[$key1] = $value1;
	  }
  }
  $user = $result;
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	$ret_mysql = $dbh->query($query);
	if(!empty($ret_mysql)) {
		session_start();
  	    $_SESSION['username'] = $username;
  	    $_SESSION['success'] = "You are now logged in";
        header('location: login.php');
	} else {
		header('location: registration.php');
	}
  }
}
  
  // LOGIN USER
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
	foreach($dbh->query($query) AS $key=>$value) {
	  foreach($value AS $key1=>$value1) {
		  $results[$key1] = $value1;
	  }
	}
  	if (!empty($results)) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}


