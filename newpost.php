<?php
	require 'connect.php';

    session_start();
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])){
        header('Location: login.php?redirect=true');
        exit();
    }

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

	function filterimage(){
	    $permitedmimetypes = ['image/gif','image/jpeg','image/png','image/svg+xml','image/bmp','image/x-icon','image/tiff','image/webp'];
	    $permitedfileextensions = ['gif','jpg','jpeg','png','svg','bmp','ico','tiff','webp'];

	    $actualfileextension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
	    $actualmimetype = getimagesize($_FILES['image']['tempname'])['mime'];

	    $fileextensionvalid = in_array($actualfileextension,$permitedfileextensions);
	    $mimetypevalid = in_array($actualmimetype,$permitedmimetypes);
	    return $fileextensionvalid && $mimetypevalid;
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
    //Server.HtmlEncode;

    $websiteurl = isset($_POST['websiteurl']);
	$websiteurl = trim($websiteurl);
	$websiteurl = filterwebsiteurl($websiteurl);
	//$websiteurl = FILTER_VALIDATE_URL($websiteurl);

	$orignatingdistrubtion = isset($_POST['orignatingdistrubtion']);
	$orignatingdistrubtion = trim($orignatingdistrubtion);
	$orignatingdistrubtion = filterorignatingdistrubtion($orignatingdistrubtion);

	$iconorlogolink = isset($_POST['iconorlogolink']);
	if ($iconorlogolink === null) {
		$iconorlogolink = "https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg";
	}else {
		$iconorlogolink = trim($iconorlogolink);
		$iconorlogolink = filtericonorlogolink($iconorlogolink);
		//$iconorlogolink = FILTER_VALIDATE_URL($iconorlogolink);
	}
	if (isset($_FILES['image']) && ($_FILES['image']['error'] == 0))
    {
        $imagefile = filterimage();
        $imagefilename = $_FILES["image"]["name"];
        $temppath = $_FILES["image"]["tempname"];
        $newpath = "images/".basename($imagefilename);
    }



	$username = $_SESSION['username'];
	$query = "INSERT INTO linuxdistrubtionpost (linuxdistrubtionname, username, requirements, cpuarchitecture, details, websiteurl, orignatingdistrubtion, iconorlogolink) VALUES (:linuxdistrubtionname, :username, :requirements, :cpuarchitecture, :details, :websiteurl, :orignatingdistrubtion, :iconorlogolink)";
		$statment = $db->prepare($query);
		$statment->bindValue(':linuxdistrubtionname',$linuxdistrubtionname);
		$statment->bindValue(':username',$username);
		$statment->bindValue(':requirements',$requirements);
		$statment->bindValue(':cpuarchitecture',$cpuarchitecture);
		$statment->bindValue(':details',$details);
		$statment->bindValue(':websiteurl',$websiteurl);
		$statment->bindValue(':orignatingdistrubtion',$orignatingdistrubtion);
		$statment->bindValue(':iconorlogolink',$iconorlogolink);
		//$statment->bindValue(':image', $newpath);

		//move_uploaded_file($_FILES['image']['tempname'],$newpath)

		($statment->execute());

		if (isset($_POST['submit'])) {
		    header('Location: index.php');
		    exit();
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>New Post</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700" rel="stylesheet">
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script>tinymce.init({ selector:'textarea'});</script>
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
				<li>New Post</li>
				<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>
			</ul>
		</div>
		<div id="all_posts">
			<form method="post" enctype="multipart/form-data">
				<fieldset>
					<label>Name of Linux Operating System: </label>
					<input id="linuxdistrubtionname" type="text" name="linuxdistrubtionname" maxlength="60">
					<label>System Requirements: </label>
					<input id="requirements" type="text" name="requirements" maxlength="200">
					<label>CPU Architecture Type: </label>
					<input id="cpuarchitecture" type="text" name="cpuarchitecture" maxlength="40">
					<label>Details: </label>
					<textarea id="details" name="details" maxlength="2000"></textarea>
					<label>Primary Website Address: </label>
					<input id="websiteurl" type="url" name="websiteurl" maxlength="40">
					<label>Oringal Distrubtion Built Off Of: </label>
					<input id="orignatingdistrubtion" type="text" name="orignatingdistrubtion" maxlength="60">
					<label>Link to icon or logo: </label>
					<input id="iconorlogolink" type="url" name="iconorlogolink" maxlength="400">
                    <label>Upload icon or logo:</label>
                    <input id="imageupload" type="file" name="imageupload">
					<input id="submit" type="submit" name="submit" value="Submit">
				</fieldset>
			</form>
		</div>
		<div id="footer">
			<h5>Site Map:</h5>
			<a href="index.php">Home</a>
			<a href="login.php">Login</a>
			<a href="register.php">Register</a>
			<a href="newpost.php">New Post</a>
			<a href="contact.php">Contact</a>
            <a href="admin.php">Admin Only</a>
		</div>
	</div>
</body>
</html>