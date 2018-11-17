<?php
	require 'connect.php';

	function filterusername(){
		return filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	}

	function filterpassword(){
		return filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	}

	function filterconfirmpassword(){
		return filter_input(INPUT_POST, 'confirmpassword', FILTER_SANITIZE_STRING);
	}

	function filteremail(){
		return filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_STRING);
	}

	function filterfname(){
		return filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
	}

	function filterlname(){
		return filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
	}

	$username = "";
	$password = "";
	$emailaddress = "";
	$confirmpassword = "";
	$firstname = "";
	$lastname = "";
	$errors = array();

	if (isset($_POST['submit'])) {
		$username = filterusername($username);
		$password = filterpassword($password);
		$emailaddress = filteremail($emailaddress);
		$confirmpassword = filterconfirmpassword($confirmpassword);
		$firstname = filterfname($firstname);
		$lastname = filterlname($lastname);

		if (empty($username)) { 
			array_push($errors, "Username is required"); 
		}

  		if (empty($emailaddress)) { 
  			array_push($errors, "Email is required"); 
  		}

  		if (empty($firstname)) { 
  			array_push($errors, "Firstname is required"); 
  		}

  		if (empty($lastname)) { 
  			array_push($errors, "Lastname is required"); 
  		}

  		if (empty($password)) { 
  			array_push($errors, "Password is required"); 
  		}

  		if ($password != $confirmpassword) {
		array_push($errors, "The two passwords do not match");
  		}

    if (count($errors) == 0) {
        $salt = openssl_random_pseudo_bytes(20);
        $saltedpassword = $salt . $password;
        $encyptedpassword = password_hash($saltedpassword,1);

        $query = "INSERT INTO userprofile (username, emailaddress, password, firstname, lastname) VALUES(:username, :emailaddress, :password, :firstname, :lastname)";
        $statment = $db->prepare($query);
        $statment->bindValue(':username', $username);
        $statment->bindValue(':emailaddress', $emailaddress);
        $statment->bindValue(':password', $encyptedpassword);
        $statment->bindValue(':firstname', $firstname);
        $statment->bindValue(':lastname', $lastname);

        $statment->execute();
    }
  	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<H1>Site of Linux</H1>
            <form method="get" id="search" action="search.php">
                <input type="text" id="search" name="search">
                <input type="submit" id="search" name="search" value="Search">
            </form>
		</div>
		<div id="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="newpost.php">New Post</a></li>
				<li><a href="login.php">Login</a></li>
				<li>Register</li>
			</ul>
		</div>
		<div id="all_posts">
			<form id="register" action="register.php" method="post">
				<fieldset>
					<legend>Register</legend>
					<h5>All fields must be entered.</h5>
					<label>Username:</label>
					<input id="username" type="text" name="username">
					<label>Password:</label>
					<input id="password" type="password" name="password">
					<label>Confirm Password:</label>
					<input id="confirmpassword" type="password" name="confirmpassword">
					<label>Email Address:</label>
					<input id="emailaddress" type="email" name="emailaddress">
					<label>First Name:</label>
					<input id="firstname" type="text" name="firstname">
					<label>Last Name:</label>
					<input id="lastname" type="text" name="lastname">
					<input id="submit" type="submit" name="submit" value="Submit">
                    <div id="error">
                        <?php if (count($errors) != 0): ?>
                            <h4>Error(s) below must be solved to register:</h4>
                        <?php endif; ?>
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
		</div>
	</div>
</body>
</html>