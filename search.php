<?php
    require "connect.php";

    function filtersearch(){
        return filter_input(INPUT_GET,'search',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    $searchterm = $_GET['search'];
    $searchterm = filtersearch();
    $query = "SELECT * FROM linuxdistrubtionpost WHERE linuxdistrubtionname LIKE '%$searchterm%' OR details LIKE '%$searchterm%'" ;

    $statement = $db -> prepare($query);
    $statement -> execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <div id="header">
        <H1>Site of Linux</H1>
        <form method="get" id="searchbar" action="search.php">
            <input type="text" id="search" name="search" minlength="1">
            <input type="submit" id="searchquery" name="search" value="Search">
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
                <ul>
                    <?php $plink = preg_replace("/[\s]/", "-",$row['linuxdistrubtionname']);?>
                    <li><a <a href="fullpost.php?id=<?=$row['id']?>&p=<?php echo $plink;?>"><?=$row['linuxdistrubtionname'];?></a><br>
                    <small><a href="edit.php?id=<?=$row['id']?>">Edit</a></small></li>
                </ul>
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