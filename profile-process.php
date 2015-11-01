<?php

/* Confirm member is currently logged in... Reset the cookie to avoid multiple redirects */

if ( isset($_COOKIE['memberid']) )
{
  $memberid = $_COOKIE['memberid'];
  unset($_COOKIE['memberid']);
  setcookie('memberid', '', time() - 3600);

  setcookie("memberid", $memberid, time() + 120, "/"); // 86400 for a full day
  $_COOKIE["memberid"] = $memberid;
}



/* Connect to the database */

$db = pg_connect("host=localhost dbname=fullstacker user=postgres")
    or die('Could not connect: ' . pg_last_error());



/* Configuration */

$root = "/usr/share/nginx/html";
$urlroot = "http://www.datataffy.com";
$max_width = 1400;
$max_height = 1300;
$overwrite_images = false;


/* Permitted subdirectories */

$target_dirs = array("profiles"); // DIRECTORY ARRAYS

/* END: Configuration */



/* Confirm getimagesize() function is available to use */

if ( !function_exists(getimagesize) )
{

	die("getimagesize() required.");
}



/* Extract information from the form */

$location = 'profiles';
$newname = strval($_POST['newname']);
$upfile = $_FILES['upfile']['tmp_name'];
$upfile_name = $_FILES['upfile']['name'];



/* Delete unwanted characters in the target name */

if ( $newname )
{
	$newname = preg_replace('/[^A-Za-z0-9_.-]/', '', $newname);
}
else
{
	$newname = preg_replace('/[^A-Za-z0-9_.-]/', '', $upfile_name);
}



/* Validate parameters */

if ( !in_array($location, $target_dirs) )
{
	/* Invalid location */
	die('invalid target directory.');
}
else
{
	$urlroot .= "/$location";
}



/* Sanity Check: Did PHP actually upload a file? */

if ( !$upfile )
{
	/* No file was uploaded */
	die('no file was uploaded.');
}



/* Verify the file type */

$file_types = array(
    "image/jpeg"    => "jpg"
    , "image/pjpeg" => "jpg"
    , "image/gif"   => "gif"
    , "image/png"   => "png"
);

$width = null;
$height = null;



/* Extract the MIME type and size for the image. */

$img_info = getimagesize($upfile);
$upfile_type = $img_info["mime"];
list($width, $height, $t, $attr) = $img_info;



/* Validate the file type. */

if( !$file_types[$upfile_type] )
{
	die('image must be in JPEG, GIF, of PNG format.');
}
else
{
	$file_suffix = $file_types[$upfile_type];
}



/* Validate the file size. */

if ( $width > $max_width || $height > $max_height )
{
    die("size $width x $height exceeds maximum $max_width X $max_height.");
}



/* Force file suffix... Add a time value to the file to prevent name clashes */

$newname  = preg_replace('/\.(jpe?g|gif|png)$/i', "", $newname);

$time = time();
$newname = $newname . '_' . $time;

$newname  = $newname . '.' . $file_suffix;
$new_fullpath = "$root/$location/$newname";



/* Verify that the file does not already exist (File overwrites NOT allowed!) */

if ( (!$overwrite_images) && file_exists($new_fullpath) )
{
    die('files exists... will not overwrite');
}



/* Copy the file to its final location. */

if ( !copy($upfile, $new_fullpath) )
{
	die('copy failed.');
}



/* Prepare the path for the new file (Need to insert this into the database) */

$image_url = "$urlroot/$newname";



/* Update the member's row with the path to new uploaded file */

$sql = "UPDATE member SET profileurl = '" . $image_url . "' WHERE memberid = " . $memberid . ";";

pg_query($db, $sql);



/* Redirect member back to the profile page with the new uploaded profile pic */

header("Location: profile.php");


/*
$result = pg_query($db, $sql);

if ( $result )
{
	header("Location: profile.php");
}
else
{

	header("Location: login.php")
}
*/


?>