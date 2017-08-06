<?php
session_start();
require_once('bazas.php');

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	
		$user = htmlspecialchars($_POST["user"]);
		$password = htmlspecialchars($_POST["password"]);
		$sql = "select user from korisnici where user='$user' and password='".sha1($password)."' ";
		//echo $sql;
		$rez = $conn->query($sql);
		if($rez->num_rows == 1)
		{
			$conn->query("INSERT INTO log(user, ip) VALUES('$user', '".$_SERVER['REMOTE_ADDR']."')");
			$red = $rez->fetch_assoc();
			$_SESSION["user"] = $user;
			header("Location: index.php");
		}
		
}
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Login Form</title>
  <link rel="stylesheet" href="css/styleLogin.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
  <section class="container">
    <div class="login">
      <h1>Login to Web App</h1>
      <form method="post" action="login.php">
        <p><input type="text" name="user" value="" placeholder="Username"></p>
        <p><input type="password" name="password" value="" placeholder="Password"></p>
     
        <p class="submit"><input type="submit" name="commit" value="Login"></p>
      </form>
	  
    </div>

  </section>

 
</body>
</html>