<?php

/* Connect to the database */

$db = pg_connect("host=localhost dbname=fullstacker user=postgres")
    or die('Could not connect: ' . pg_last_error());


if ( isset($_POST['email']) && isset($_POST['pwd']) )
{
  $email = $_POST['email'];
  $pwd = $_POST['pwd'];
}
else
{
  $email = '';
  $pwd = '';	
}


/* Retrieve the member's id and other key info */

$sql = "SELECT memberid FROM member WHERE email = '" . $email . "' AND password = '" . $pwd . "'";

$result = pg_query($db, $sql);

if ( !$result )
{
  die("Error finding the member: " . pg_last_error());
}


/* Destroy any previous cookies for this member */

if ( isset($_COOKIE['memberid']) )
{
	unset($_COOKIE['memberid']);
	setcookie('memberid', '', time() - 3600);
} 


/* Set the new cookie with the memberid value */

while ( $row = pg_fetch_assoc($result) )
{
  $memberid = $row['memberid'];
  setcookie("memberid", $memberid, time() + 120, "/"); // 86400 for a full day
  $_COOKIE["memberid"] = $memberid;
}

header("Location: profile.php");

// echo 'MemberID: ' . $memberid;

?>