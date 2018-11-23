<?php
    session_start();
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])){
        header('Location: login.php?redirect=true');
        exit();
    }

	require "connect.php";
    $username = $_SESSION['username'];
	$option = "linuxdistrubtionname";
	$sortoption = "Name";
	if (isset($_POST['title'])) {
		$option = "linuxdistrubtionname";
		$sortoption = "Name";
	} else if (isset($_POST['date'])) {
		$option = "dateandtime";
		$sortoption = "Date Posted";
	}
	$query = "SELECT * FROM linuxdistrubtionpost ORDER BY $option";

	$statement = $db -> prepare($query);
	$statement -> execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
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
            <form method="get" id="logoutbutton" action="login.php">
                <input type="submit" id="logout" name="logout" value="Logout">
            </form>
		</div>
		<div id="menu">
			<ul>
				<li>Home</li>
				<li><a href="newpost.php">New Post</a></li>
				<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>
			</ul>
		</div>
		<div id="all_posts">
			<div id="sort">
				<form method="post">
					<h5>Sort By: <?=$sortoption?></h5>
					<input type="radio" name="sort" value="title">Title
					<input type="radio" name="sort" value="date">Date
					<input type="submit" name="submit" value="Sort">
				</form>
			</div>
			<?php while ($row = $statement -> fetch()): ?>
			<div class="post">
				<h2><?=$row['linuxdistrubtionname'];
				?></h2>
				<img src="<?=$row['iconorlogolink']?>" alt="Distrubtion Logo">

				<p>Link to site: <a href="<?=$row['websiteurl'];?>"><?=$row['websiteurl'];?></a></p>
				<p>Details: <?=html_entity_decode($row['details']);?>
				<a href="fullpost.php?id=<?=$row['id']?>"><br>Read More...</a><br>
				<small><a href="edit.php?id=<?=$row['id']?>">Edit</a></small>
			</div>
			<?php endwhile; ?>
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