<?php 
	require 'connect.php';
	session_start();

	function filterusername(){
		return filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	}

	function filterpassword(){
		return filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	}
	if (isset($_POST['username'])) {
		if (empty($username)) {
			$errorMessage = "Username must be entered.";
		}
	}

	if (isset($_POST['password'])) {
		if (empty($password)) {
			$errorMessage = "Password must be entered.";
		}
	}

	$username = isset($_POST['username']);
	$password = isset($_POST['password']);
	$username = trim($username);
	$password = trim($password);
	$username = filterusername();
	$password = filterpassword();
	$errorMessage ="";
	$loginresult = "";


	
	$query = "SELECT * FROM userprofile WHERE username = $username";
	$statement = $db -> prepare($query);
	$statement -> execute();

	$row = $statement -> fetch();

	$encyptedpassword = $row['password'];
	echo $encyptedpassword;
	if (password_verify($password, $encyptedpassword)) {
	    $loginresult = "Logged in";
    } else {
	    $loginresult = "Login Failed: Incorrect username or password";
    }

	/*if (empty($row)) {
		$loginresult = "Login does not match any users";
	} else {
		$loginresult = "Login Successful";
		$_SESSION['login'] = $username;
	}*/

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<H1>Site of Linux</H1>
            <form method="get" id="search" action="search.php">
                <input type="text" id="search" name="search" minlength="1">
                <input type="submit" id="searchquery" name="searchquery" value="Search">
            </form>
		</div>
		<div id="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="newpost.php">New Post</a></li>
				<li>Login</li>
				<li><a href="register.php">Register</a></li>
			</ul>
		</div>
		<div id="all_posts">
			<form id="login" action="login.php" method="post">
				<fieldset>
					<legend>Login</legend>
					<h5>All fields must be entered.</h5>
					<label>Username:</label>
					<input id="username" type="text" name="username" maxlength="55">
					<label>Password:</label>
					<input id="password" type="password" name="password" maxlength="55">
					<input id="submit" type="submit" name="submit" value="Submit"><br>
					<p><?= $loginresult?> <?=$errorMessage?></p>
				</fieldset>
			</form>
		</div>
		<div id="footer">
			<h5>Site Map:</h5>
			<a href="index.php">Home</a>
			<a href="login.php">Login</a>
			<a href="register.php">Register</a>
			<a href="newpost.php">New Post</a>
		</div>
	</div>
</body>
</html>