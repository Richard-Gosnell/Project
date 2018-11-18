<?php
	//require session(loggedin)
	require 'authenticate.php';
	require 'connect.php';

	function filterlinuxdistrubtionname(){
		return filter_input(INPUT_POST,'linuxdistrubtionname',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}

	function filterrequirements(){
		return filter_input(INPUT_POST, 'requirements', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}

	function filtercpuarchitecture(){
		return filter_input(INPUT_POST,'cpuarchitecture',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}

	function filterdetails(){
		return filter_input(INPUT_POST,'details',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}

	function filterwebsiteurl(){
		return filter_input(INPUT_POST,'websiteurl',FILTER_SANITIZE_URL);
	}

	function filterorignatingdistrubtion(){
		return filter_input(INPUT_POST,'orignatingdistrubtion',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}

	function filtericonorlogolink(){
		return filter_input(INPUT_POST,'iconorlogolink',FILTER_SANITIZE_URL);
	}

	$linuxdistrubtionname = isset($_POST['linuxdistrubtionname']);
	$linuxdistrubtionname = trim($linuxdistrubtionname);
	$linuxdistrubtionname = filterlinuxdistrubtionname($linuxdistrubtionname);

	$requirements = isset($_POST['requirements']);
	$requirements = trim($requirements);
	$requirements = filterrequirements($requirements);

	$cpuarchitecture = isset($_POST['cpuarchitecture']);
	$cpuarchitecture = trim($cpuarchitecture);
	$cpuarchitecture = filtercpuarchitecture($cpuarchitecture);

	//$details = isset($_POST['tinymcetextarea']);
	$details = isset($_POST['details']);
	$details = trim($details);
	$details = filterdetails($details);

	$websiteurl = isset($_POST['websiteurl']);
	$websiteurl = trim($websiteurl);
	$websiteurl = filterwebsiteurl($websiteurl);
	//$websiteurl = FILTER_VALIDATE_URL($websiteurl);

	$orignatingdistrubtion = isset($_POST['orignatingdistrubtion']);
	$orignatingdistrubtion = trim($orignatingdistrubtion);
	$orignatingdistrubtion = filterorignatingdistrubtion($orignatingdistrubtion);

	$iconorlogolink = isset($_POST['iconorlogolink']);
	$iconorlogolink = trim($iconorlogolink);
	$iconorlogolink = filtericonorlogolink($iconorlogolink);
	//$iconorlogolink = FILTER_VALIDATE_URL($iconorlogolink);

	$query = "INSERT INTO linuxdistrubtionpost (linuxdistrubtionname, requirements, cpuarchitecture, details, websiteurl, orignatingdistrubtion, iconorlogolink) VALUES (:linuxdistrubtionname, :requirements, :cpuarchitecture, :details, :websiteurl, :orignatingdistrubtion, :iconorlogolink)";
		$statment = $db->prepare($query);
		$statment->bindValue(':linuxdistrubtionname',$linuxdistrubtionname);
		$statment->bindValue(':requirements',$requirements);
		$statment->bindValue(':cpuarchitecture',$cpuarchitecture);
		$statment->bindValue(':details',$details);
		$statment->bindValue(':websiteurl',$websiteurl);
		$statment->bindValue(':orignatingdistrubtion',$orignatingdistrubtion);
		$statment->bindValue(':iconorlogolink',$iconorlogolink);

		($statment->execute());

	$id = $_GET["id"];
	$oldid = $_GET["id"];

	if (isset($_POST['delete'])) {
		$query = "DELETE FROM linuxdistrubtionpost WHERE id = $id";
		$statment = $db -> prepare($query);
		$statment -> execute();
	} else {
		$query = "SELECT * FROM linuxdistrubtionpost WHERE id = $id";

		$statement = $db -> prepare($query);
		$statement -> execute();

		$row = $statement -> fetch();

		$query = "DELETE FROM linuxdistrubtionpost WHERE id = $oldid";
		$statment = $db -> prepare($query);
		$statment -> execute();
	}
    if (isset($_POST['submit'])) {
        header('Location: index.php');
        exit();
    }

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Post</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script>tinymce.init({ selector:'textarea'});</script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<H1>Site of Linux</H1>
            <form method="get" id="search" action="search.php">
                <input type="text" id="search" name="search">
                <input type="submit" id="searchquery" name="searchquery" value="Search">
            </form>
		</div>
		<div id="menu">
			<ul>
				<li>Please Select Update or Delete to be returned to home page</li>
			</ul>
		</div>
		<div id="all_posts">
			<form method="post">
				<fieldset>
					<label>Name of Linux Operating System: </label>
					<input id="linuxdistrubtionname" type="text" name="linuxdistrubtionname" maxlength="60" value="<?=$row['linuxdistrubtionname'];?>">
					<label>System Requirements: </label>
					<input id="requirements" type="text" name="requirements" maxlength="200" value="<?=$row['requirements']?>">
					<label>CPU Architecture Type: </label>
					<input id="cpuarchitecture" type="text" name="cpuarchitecture" maxlength="40" value="<?=$row['cpuarchitecture']?>">
					<label>Details: </label>
					<!--<textarea id="tinymcetextarea" name="tinymcetextarea"></textarea>-->
					<textarea id="details" name="details" maxlength="2000"><?=$row['details']?></textarea>
					<label>Primary Website Address: </label>
					<input id="websiteurl" type="url" name="websiteurl" maxlength="40" value="<?=$row['websiteurl']?>">
					<label>Oringal Distrubtion Built Off Of: </label>
					<input id="orignatingdistrubtion" type="text" name="orignatingdistrubtion" maxlength="60" value="<?=$row['orignatingdistrubtion']?>">
					<label>Link to icon or logo (Must be a Link): </label>
					<input id="iconorlogolink" type="url" name="iconorlogolink" maxlength="400" value="<?=$row['iconorlogolink']?>">
					<input id="submit" type="submit" name="submit" value="Update">
					<input id="delete" type="button" name="delete" value="Delete Post">
				</fieldset>
			</form>
		</div>
		<div id="footer">
			<h5>Site Map:</h5>
			<li>Please Select Update or Delete to be returned to home page</li>
		</div>
	</div>
</body>
</html>