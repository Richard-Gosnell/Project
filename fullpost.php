<?php
	require 'connect.php';

	$id = $_GET["id"];

	$query = "SELECT * FROM linuxdistrubtionpost WHERE id = $id";

	$statement = $db -> prepare($query);
	$statement -> execute();

	$row = $statement -> fetch();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Post</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1><a href="index.php">Site of Linux</a></h1>
            <form method="get" id="searchbar" action="search.php">
                <input type="text" id="search" name="search" minlength="1">
                <input type="submit" id="searchquery" name="searchquery" value="Search">
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
			<small>Posted by: <?=$row['username']?></small>
			<div class="fullpost">	
				<h2><?=$row['linuxdistrubtionname']; ?></h2>
				<img src="<?=$row['iconorlogolink']?>" alt="logo">
				<?=html_entity_decode($row['details'])?>
				<p>Link to website: <a href="<?=$row['websiteurl']?>"><?=$row['websiteurl']?></a></p>
				<h3>System Requirements</h3><br>
				<p><?=$row['requirements']?></p>
				<h3>Architecture</h3><br>
				<p><?=$row['cpuarchitecture']?></p>
				<h3>Based On</h3><br>
				<p><?=$row['orignatingdistrubtion']?></p>
			</div>
			<?php 
				session_start();
				
				if (isset($_POST) & !empty($_POST)) {
					if ($_POST['captcha'] == $_SESSION['code']) {
						function filtername(){
							return filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
						}

						function filtercomment(){
							return filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
						}

						$username = isset($_POST['username']);
						$username = trim($username);
						$username = filtername($username);

						$comment = isset($_POST['comment']);
						$comment = trim($comment);
						$comment = filtercomment($comment);

						$querycommentinput = "INSERT INTO comments(linuxdistrubtionpostid, username, comment) VALUES (:linuxdistrubtionpostid,:username, :comment)";
						$newcommentstatement = $db->prepare($querycommentinput);
						$newcommentstatement->bindValue(':linuxdistrubtionpostid',$id);
						$newcommentstatement->bindValue(':comment',$comment);
						$newcommentstatement->bindValue(':username',$username);

						$newcommentstatement->execute();

					} else {
						echo "<p style='color: red'>Warning: Captcha Invalid, Comment Denied</p>";
					}
				}

				$querycomment = "SELECT * FROM comments WHERE linuxdistrubtionpostid = $id";

				$commentstatment = $db -> prepare($querycomment);
				$commentstatment -> execute();

			 ?>
			<div id="comments">
			
			<form action="fullpost.php?id=<?=$row['id']?>" method="post">
				<label>Name (if not logged in): </label>
				<input type="text" name="username" id="username" maxlength="55">
				<label>Comment:</label>
				<small>Please keep comments respectful and polite.</small>
				<textarea id="comment" name="comment" rows="2" maxlength="200"></textarea>
				<label>Please enter number in box below:</label>
				<input type="text" id="captcha" name="captcha">
				<img src="captcha.php" alt="captcha">
				<input type="submit" name="submit" value="Submit" style="position: relative; left: -60px;">
			</form>
			<h4>Comments:</h4>
			<?php while ($commentrow = $commentstatment -> fetch()): ?>
				<small>By: <?= $commentrow['username']; ?></small>
				<br>
				<p><?= $commentrow['comment']; ?></p>
			<?php endwhile; ?>
			</div>
        </div>
		</div>
			<div id="footer">
			<h5>Site Map:</h5>
			<a href="index.php">Home</a>
			<a href="login.php">Login</a>
			<a href="register.php">Register</a>
			<a href="newpost.php">New Post</a>
                <a href="admin.php">Admin Only</a>
        </div>
</body>
</html>