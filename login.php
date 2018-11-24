<?php 
	require 'connect.php';

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

	$notice = "";
	if (isset($_GET['redirect'])){
	    $notice = "You must be logged in to view that page";
    }

    if (isset($_GET['redirectadmin'])){
        $notice = "You must be logged in <strong>as Admin</strong> to view that page";
    }

    if (isset($_GET['logout'])){
        session_start();
        $_SESSION['username'] = [];
        $notice = "You have been logged out";
    }
	$username = isset($_POST['username']);
	$password = isset($_POST['password']);
	$username = trim($username);
	$password = trim($password);
	$username = filterusername();
	$password = filterpassword();
    $errors = array();
	$errorMessage ="";
	$loginresult = "";
	if (isset($_POST['submit'])) {
        if (empty($username)) {
            array_push($errors, "Username is required");
        }

        if (empty($password)) {
            array_push($errors, "Password is required");
        }


        if (count($errors) == 0) {
            $query = "SELECT * FROM userprofile WHERE username = '$username'";
            $statement = $db->prepare($query);
            $statement->execute();

            $row = $statement->fetch();
            $passwordhash = $row['password'];

            if (password_verify($password, $passwordhash)) {
                session_start();
                //$_SESSION['loggedin'];
                $_SESSION['username'] = $username;
                $loginresult = "<p style='color:green;'>Logged in as $username</p>";
            } else {
                $loginresult = "<p style='color:red;'>Login Failed: Incorrect username or password<p>";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<H1>Site of Linux</H1>
            <form method="get" id="searchbar" action="search.php">
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
                <div id="notice">
                <p><?=$notice?></p>
                </div>
				<fieldset>
					<legend>Login</legend>
					<h5>All fields must be entered.</h5>
					<label>Username:</label>
					<input id="username" type="text" name="username" maxlength="55">
					<label>Password:</label>
					<input id="password" type="password" name="password" maxlength="55">
					<input id="submit" type="submit" name="submit" value="Submit"><br>
					<p><?= $loginresult?></p>
                    <div id="error">
                        <?php foreach ($errors as $errormessage): ?>
                            <p><?= $errormessage ?></p>
                        <?php endforeach; ?>
                    </div>
				</fieldset>
			</form>
		</div>
		<div id="footer">
			<h5>Site Map:</h5>
			<a href="index.php">Home</a>
			<a href="login.php">Login</a>
			<a href="register.php">Register</a>
			<a href="newpost.php">New Post</a>
            <a href="admin.php">Admin Only</a>
		</div>
	</div>
</body>
</html>