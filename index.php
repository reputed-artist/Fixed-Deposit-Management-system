<?php
session_start();
include("dbconnection.php");

if(isset($_POST['login']))
{
 
  $adminusername=$_POST['username'];
  $pass=$_POST['password'];
$ret=mysqli_query($con,"SELECT * FROM admin WHERE username='$adminusername' and password='$pass'");
$num=mysqli_fetch_array($ret);
if(($num)<1)
{
    $error="1";
}

else
{

$_SESSION['login']=$_POST['username'];
$_SESSION['name']=$_POST['name'];
$_SESSION['id']=$num['id'];
header("Location:pages/index1.php");
exit();
}
}



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
	.input-group{
		margin-left:32px;
	}
	#try
	{
		margin-left: 30%;
	}
</style>
</head>
<body class="hold-transition login-page" align="center">
<div class="login-box">
  </div>
  <!-- /.login-logo -->

 
        <div class="col-md-4" id="try">
          <!-- general form elements -->
          <div class="box box-info"  style="box-shadow: 5px 10px 8px #888888;">
            
 	</br></br>
  	<div align="center">
  	<img src ="dist/img/logo.png" class="" alt="CODETECH Logo" height="150" width="210"></div>
  </br>
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="" method="post" role="form">
    <div class="box-body">
      <div class="form-group" >
        <div class="input-group col-sm-10">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" name="username" id="username" class="form-control col-sm-8"  required="required" placeholder="Email">
               
              </div>
              <div id="usererror"></div>
      </div>

      <div class="form-group">
        <div class="input-group col-sm-10">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" name="password" id="password" class="form-control col-sm-8" required="required" placeholder="Password">
                
              </div>
      <div id="passerror"> </div>         
      </div>
     
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck pull-left" style="margin-left: 50px;">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
    </div>
        <!-- /.col -->
        <div class="box-footer" style="margin-right: 10px; ">
                <label class="col-sm-1"></label>
                <input type="submit" name="login" value="Login" onclick="validate()" class="btn btn-info col-sm-10"></br>
              <div id="dberror" style="color:red;"><?php if(isset($error)){
                echo "*Invalid Login credentials";
              }
              ?></br></br></br>
              </div>
               </div>
              
        </div>
    </form>
</div>
<p style="color:black; font-weight: bold; "> Email : admin@gmail.com </p>
<p style="color:black; font-weight: bold; "> Password: admin@123 </p>
</div>
   
<script type="text/javascript">
  function validate(){
  var username = document.getElementById("username").value;
  var password=document.getElementById("password").value;

  if(!username)
  {
    document.getElementById("usererror").innerHTML ="Enter User name";
    document.getElementById("usererror").style.color="Red";
  }
  

if(!password)
{
  document.getElementById("passerror").innerHTML="Enter Password";
  document.getElementById("passerror").style.color="Red";
}

}
</script>



<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
