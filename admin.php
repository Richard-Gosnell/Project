<?php
    require "connect.php";
    session_start();
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])){
        header('Location: login.php?redirect=true');
        exit();
    }
    if ($_SESSION['username'] != "Admin")
    {
        header('Location: login.php?redirectadmin=true');
        exit();
    }

    $query = "SELECT * FROM userprofile ORDER BY username";
    $statement = $db->prepare($query);
    $statement->execute();

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
    $message = "";

    if (isset($_POST['submit'])) {
        $username = filterusername();
        $password = filterpassword();
        $emailaddress = filteremail();
        $confirmpassword = filterconfirmpassword();
        $firstname = filterfname();
        $lastname = filterlname();

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
            $encyptedpassword = password_hash($password,1);

            $query = "INSERT INTO userprofile (username, emailaddress, password, firstname, lastname) VALUES(:username, :emailaddress, :password, :firstname, :lastname)";
            $statment = $db->prepare($query);
            $statment->bindValue(':username', $username);
            $statment->bindValue(':emailaddress', $emailaddress);
            $statment->bindValue(':password', $encyptedpassword);
            $statment->bindValue(':firstname', $firstname);
            $statment->bindValue(':lastname', $lastname);

            $statment->execute();
            $message = "Registration Successful";
        }
    }

    if (isset($_GET["id"])){
        $id = $_GET["id"];
        $query = "DELETE FROM userprofile WHERE id = $id";
        $statment = $db->prepare($query);

        $statment->execute();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
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
        <form method="get" id="logoutbutton" action="login.php">
            <input type="submit" id="logout" name="logout" value="Logout">
        </form>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="newpost.php">New Post</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </div>
    <div id="all_posts">
        <h2>Registered Users</h2>
        <h3>Current Users</h3>
        <h5>Note: Data will be copied in whole</h5>
        <?php while ($row = $statement -> fetch()): ?>
            <div class="post">
                <table>
                    <tr>
                        <th>Username</th>
                        <!--<th>Password</th></td>-->
                        <th>Email Address</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><?=$row['username']?></td>
                        <!--<td><?=$row['password']?></td>-->
                        <td><?=$row['emailaddress']?></td>
                        <td><?=$row['firstname']?></td>
                        <td><?=$row['lastname']?></td>
                        <td><a href="admin.php?id=<?=$row['id']?>">Delete</a></td>
                    </tr>
                </table>
            </div>
        <?php endwhile; ?>
        <h3>Add User</h3>
        <form id="register" action="admin.php" method="post">
            <fieldset>
                <p><strong><?= $message ?></strong></p>
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
                        <h4>Error(s) below must be solved to add user:</h4>
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
        <a href="admin.php">Admin Only</a>
    </div>
</div>
</body>
</html>
