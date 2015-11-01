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
    <h2>Sign Up</h2>

    <form role="form" id="form2" action="signup-process.php" method="post">
      <div class="form-group">
        <label for="username2">User Name:</label>
        <input type="text" class="form-control" id="username2" name="username2">
      </div>
      <div class="form-group">
        <label for="email2">Email Address:</label>
        <input type="email" class="form-control" id="email2" name="email2" placeholder="email@example.com">
      </div>
      <div class="form-group">
        <label for="email3">Confirm Email:</label>
        <input type="email" class="form-control" id="email3" name="email3" placeholder="email@example.com">
      </div>
      <div class="form-group">
        <label for="pwd2">Password:</label>
        <input type="password" class="form-control" id="pwd2" name="pwd2">
      </div>
      <div class="form-group">
        <label for="pwd3">Confirm Password:</label>
        <input type="password" class="form-control" id="pwd3" name="pwd3">
      </div>


      <div class="form-group">
        <label for="month">Month You Were Born:</label>
        <select class="form-control" id="month" name="month">
          <option>01</option>
          <option>02</option>
          <option>03</option>
          <option>04</option>
          <option>05</option>
          <option>06</option>
          <option>07</option>
          <option>08</option>
          <option>09</option>
          <option>10</option>
          <option>11</option>
          <option>12</option>
         </select>
       </div>


      <div class="form-group">
        <label for="day">Date Your Were Born:</label>
        <select class="form-control" id="day" name="day">
          <option>01</option>
          <option>02</option>
          <option>03</option>
          <option>04</option>
          <option>05</option>
          <option>06</option>
          <option>07</option>
          <option>08</option>
          <option>09</option>
          <option>10</option>
          <option>11</option>
          <option>12</option>
          <option>13</option>
          <option>14</option>
          <option>15</option>
          <option>16</option>
          <option>17</option>
          <option>18</option>
          <option>19</option>
          <option>20</option>
          <option>21</option>
          <option>22</option>
          <option>23</option>
          <option>24</option>
          <option>25</option>
          <option>26</option>
          <option>27</option>
          <option>28</option>
          <option>29</option>
          <option>30</option>
          <option>31</option>
        </select>
      </div>

      <button type="submit" class="btn btn-default" id="submit2" name="submit2">Submit</button>
    </form><!-- END: id="form2 -->
  </div>


  <div class="col-md-4 center-block">
    <h2>** OR **</h2>
  </div>

  <div class="col-md-4">

  <h2>Already a Member?</h2>
  <h3>Login <a href="login.php">Here!</a></h3>

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

     $("#form2").validate({
        rules: {
            username2: {
                required: true,
                minlength: 2
            },

            email2: {
                required: true,
                email: true
            },
            email3: {
                required: true,
                email: true,
                equalTo: "#email2"
            },

            pwd2: {
                required: true,
                minlength: 7
            },
            pwd3: {
                required: true,
                equalTo: "#pwd2"
            }
         },

         messages: {
             email3: {
                equalTo: "Please enter the same email as above"
             },

             pwd3: {
                equalTo: "Please enter the same password as above"
             }
         }
   
    }); // END: "#form2

});
</script>
</html>
<?php

pg_free_result($result);
pg_close($db);

?>