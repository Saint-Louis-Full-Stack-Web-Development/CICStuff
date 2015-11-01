<?php

/* include the code to calculate a user's sun sign */

require_once('calculatesign.php');



/* Initialize an error array for error messages */
$errors = array();


/* Connect to the database */

$db = pg_connect("host=localhost dbname=fullstacker user=postgres")
    or die('Could not connect: ' . pg_last_error());


/*
if
( 
	    isset($_POST['username2']) 
	&&  isset($_POST['email2']) 
	&&  isset($_POST['email3']) 
	&&  isset($_POST['pwd2']) 
	&&  isset($_POST['pwd3']) 
	&&  isset($_POST['month'])
	&&  isset($_POST['day']) 
)
{
*/
  $username2 = $_POST['username2'];
  $email2 = $_POST['email2'];
  $email3 = $_POST['email3'];
  $pwd2 = $_POST['pwd2'];
  $pwd3 = $_POST['pwd3'];
  $month = $_POST['month'];
  $day = $_POST['day'];
 /*
}
else
{
	// header("Location: signup.php")
    array_push($errors, 'Please fill out all fields.');	
}
*/

if ( $username2 == '' )
{
	// header("Location: signup.php")
	array_push($errors, 'Username cannot be blank.');
}

if ( $email2 != $email3 )
{
	// header("Location: signup.php")
	array_push($errors, 'Email addresses must be identical.');
}

if ( $pwd2 == '' || $pwd3 == '' )
{
	// header("Location: signup.php")
	array_push($errors, 'Passwords cannot be blank.');	
}

if ( $pwd2 != $pwd3 )
{
	// header("Location: signup.php")
	array_push($errors, 'Passwords must be identical.');
}

if ( $month < 1 || $month > 12 )
{
	// header("Location: signup.php")
	array_push($errors, 'Month must be between 1 and 12.');
}

if ( $day < 1 || $day > 31 )
{
	// header("Location: signup.php")
	array_push($errors, 'Day must be between 1 and 31.');
}


/* Immediately kill the process if there are any errors. */

if( !empty($errors) )
{
	print_r($errors);
	die('You have errors in your sign up form.');
}


/* See if this email already exists in our database */

$sql = "SELECT COUNT(memberid) AS count FROM member WHERE email = '" . $email2 . "';";

$result = pg_query($db, $sql);

while ( $row = pg_fetch_assoc($result) )
{
  $membercount = $row['count'];
}


/* Immediately kill the process if this email already exists in our database */

if ( $membercount > 0 )
{
  die("A member with this email already exists.");
}


/* Destroy any previous cookies for this user (not a member yet) */

if ( isset($_COOKIE['memberid']) )
{
	unset($_COOKIE['memberid']);
	setcookie('memberid', '', time() - 3600);
} 


$signid = calculateSign($month, $day);


$sql  = "INSERT INTO member (membername, email, password, birthmonth, birthday, signid) VALUES ('";
$sql .= $username2 . "', '";
$sql .= $email2 . "', '";
$sql .= $pwd2 . "', ";
$sql .= $month . ", ";
$sql .= $day . ", ";
$sql .= $signid . ")";
$sql .= " ";
$sql .= "RETURNING memberid;";

$result = pg_query($db, $sql);

$row = pg_fetch_row($result);

$memberid = $row['0'];

if ( $memberid >= 1 )
{
	setcookie("memberid", $memberid, time() + 120, "/"); // 86400 for a full day
	$_COOKIE["memberid"] = $memberid;
	header("Location: profile.php");
}


?>