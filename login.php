<?php

/* Check if member is already logged in. */

if ( isset($_COOKIE['memberid']) )
{
  $memberid = $_COOKIE['memberid'];
} 
else
{
  $memberid = 0;
}

/* Connect to the database */

$db = pg_connect("host=localhost dbname=fullstacker user=postgres")
    or die('Could not connect: ' . pg_last_error());


/* Retrieve the member's id and other key info */

$sql = "SELECT memberid, membername FROM member WHERE memberid = " . $memberid;

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

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="jquery.validate.js"></script>

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

.col-md-4
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

</div> <!--/panel-body-->
 
  <div class="col-md-4">
    <h2>Login</h2>
    <form role="form" id="form" action="login-process.php" method="post">
      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com">
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="pwd">
      </div>
      <button type="submit" class="btn btn-default" id="submit" name="submit">Submit</button>
    </form><!-- END: id="form -->
  </div>

  <div class="col-md-4 center-block">
    <h2>** OR **</h2>
  </div>

  <div class="col-md-4">

  <h2>Not a Member?</h2>
  <h3>Sign Up <a href="signup.php">Here!</a></h3>

  </div><!-- /class="col-md4" -->

 
</div> <!--/panel-body-->
</div><!--/panel-->
</div><!--/col--> 
</div><!--/row--> 
</div><!--/container-->

</body>

<script>
$(document).ready(function () {

   // alert("Hi!!!");

    $('#form').validate({ // initialize the plugin
        rules: {
            email: {
                required: true,
                email: true
            },
            pwd: {
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