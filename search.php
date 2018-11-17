<?php

    require "connect.php";

    $query = "SELECT * FROM linuxdistrubtionpost";

    $statement = $db -> prepare($query);
    $statement -> execute();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
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
            <li><a href="register.php">Register</a></li>
        </ul>
    </div>
    <div id="all_posts">
        <h2>Search</h2>
        <?php while ($row = $statement -> fetch()): ?>
            <div class="post">

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