<?php

/* Check if member is already logged in. */

if ( isset($_COOKIE['memberid']) )
{
  $memberid = $_COOKIE['memberid'];
}    
else
{
  header("Location: login.php");
}



/* Connect to the database */

$db = pg_connect("host=localhost dbname=fullstacker user=postgres")
    or die('Could not connect: ' . pg_last_error());


/* Retrieve the member's id and other key info */

$sql = "SELECT memberid, membername, profileurl FROM member WHERE memberid = " . $memberid;

$result = pg_query($db, $sql);

if ( !$result )
{
  unset($_COOKIE['memberid']);
  setcookie('memberid', '', time() - 3600);
  die("Error finding the member: " . pg_last_error());
}

while ( $row = pg_fetch_assoc($result) )
{
  $memberid = $row['memberid']; // is this necessary, since the value is already in the cookie?
  $membername = $row['membername'];
  $profileurl = $row['profileurl'];
}

/* Set navigation parms for member vs. guest */

$loginout = 'Login';
$loginoutpage = 'login.php';

if ( $memberid >= 1 )
{
  $loginout = 'Logout';
  $loginoutpage = 'logout.php';
  $welcomepage = '#';
}
else
{
  $loginout = 'Login';
  $loginoutpage = 'login.php';
  $welcomepage = 'signup.php';
  $membername = 'Guest';
}


/* Use guest image if member does not have a profile pic */

if ( $profileurl == '' || !($profileurl) )
{
  // Make sure you change this to your filepath and filename!
  $profileurl = 'http://www.datataffy.com/profiles/guest_image.png_1431537825.png';
}


/* Retrieve the member's sun sign */

$sql = "SELECT s.sign FROM sign s INNER JOIN member m ON m.signid = s.signid WHERE m.memberid = " . $memberid;

$result = pg_query($db, $sql);

if ( !$result )
{
  die("Error finding the member's sun sign: " . pg_last_error());
}

while ( $row = pg_fetch_assoc($result) )
{
  $sign = $row['sign'];
}


/* Retrieve the user's hobbies */

$sql = "SELECT mh.hobby FROM memberhobby mh INNER JOIN member m ON m.memberid = mh.memberid WHERE m.memberid = " . $memberid;

$result = pg_query($db, $sql);

if ( !$result )
{
  die("Error finding the member's hobbies: " . pg_last_error());
}

$hobbies = array();

while ( $row = pg_fetch_assoc($result) )
{
  array_push($hobbies, $row['hobby']);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<style>
body
{
    background-color: lightgreen;
}

@media screen and (max-width: 767px)
{
    body
    {
        background-color: lightblue;
    }
}

table
{
    margin-top: 20px;
}
</style>

</head>

<body>

<div class="container">

<div class="row">
<div class="col-md-8 col-xs-10">
<div class="well panel panel-default">
<div class="panel-body">

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Home</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="profile.php">Your Profile</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="signup.php">Sign Up</a></li>
        <?php echo '<li><a href="' . $loginoutpage . '">' . $loginout . '</a></li>'; ?>
        <?php echo '<li><a href="' . $welcomepage . '">Welcome, ' . $membername . '</a></li>'; ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


        <div class="row">
          <div class="col-xs-12 col-sm-4 text-center">
            <img src="<?php echo $profileurl; ?>" alt="" class="center-block img-circle img-thumbnail img-responsive">
            <ul class="list-inline ratings text-center" title="Ratings">
              <li><a href="#"><span class="fa fa-star fa-lg"></span></a></li>
              <li><a href="#"><span class="fa fa-star fa-lg"></span></a></li>
              <li><a href="#"><span class="fa fa-star fa-lg"></span></a></li>
              <li><a href="#"><span class="fa fa-star fa-lg"></span></a></li>
              <li><a href="#"><span class="fa fa-star fa-lg"></span></a></li>
            </ul>
          </div>
          <!--/col--> 
          <div class="col-xs-12 col-sm-8">
            <h2><?php echo $membername; ?></h2>
            <p><strong>Sign: </strong><?php echo $sign; ?></p>
            <p><strong>Hobbies: </strong>

               <?php
                if ( !empty($hobbies) )
        		    {
                  foreach ($hobbies as $hobby) 
                  {
                      echo '<span class="label label-info tags">' . $hobby . '</span> '; 
                  }
          		  }
          		  else
          		  {
          		      echo 'n.a.';
          		  }
                ?>

            </p>
           </div>
            <!--/col-->          
            <div class="clearfix"></div>
            <div class="col-xs-12 col-sm-4">
              <h2><strong> 20,7K </strong></h2>
              <p><small>Followers</small></p>
              <button class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span> Your Horoscope </button>
            </div>
            <!--/col-->
            <div class="col-xs-12 col-sm-4">
              <h2><strong>245</strong></h2>
              <p><small>Following</small></p>
              <button class="btn btn-info btn-block"><span class="fa fa-user"></span> See Other Members </button>
            </div>
            <!--/col-->
            <div class="col-xs-12 col-sm-4">
              <h2><strong>43</strong></h2>
              <p><small>Snippets</small></p>
              <button type="button" class="btn btn-primary btn-block"><span class="fa fa-gear"></span> Logout </button>  
            </div>
            <!--/col-->


            <div class="clearfix"></div>
            <table border="0" cellpadding="10">
              <form action="profile-process.php" enctype="multipart/form-data" method="post">
              <tr>
                <td valign="top"><strong>Image File:</strong></td>
                <td><input name="upfile" type="file"><br />
                  Image files must be in a JPEG, GIF, or PNG format.
                </td>
              </tr>
              <tr>
                <td valign="top"><strong>Filename (Optional):</strong></td>
                <td><input name="newname" type="text" size="64" maxlength="64"></td>
              </tr>
              <tr>
                <td colspan="2">
                  <div align="center"><input type="submit" value="Upload Image"></div>
                </td>
              </tr>
              </form>
            </table>


          </div>
          <!--/row-->
        </div>
        <!--/panel-body-->
      </div>
      <!--/panel-->
    </div>
    <!--/col--> 
  </div>
  <!--/row--> 
</div>
<!--/container-->

</body>

<script>
$(document).ready(function () {

   // alert("Hi!!!");

    $('#form1').validate({ // initialize the plugin
        rules: {
            email1: {
                required: true,
                email: true
            },
            pwd1: {
                required: true,
                minlength: 5
            }
        }
    });

});
</script>
</html>
<?php

pg_free_result($result);
pg_close($db);

?>