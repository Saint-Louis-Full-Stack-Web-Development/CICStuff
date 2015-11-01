<?php


if ( isset($_COOKIE['memberid']) )
{
	unset($_COOKIE['memberid']);
	setcookie('memberid', '', time() - 3600);
}    

header("Location: index.php");


?>