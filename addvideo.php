<?php
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
$error=0;
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tranac-db", $pass, "tranac-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (($_POST["name"]==null))
{
	echo 'Error to add a video it must have a name click <a href="Video_Store.php">here</a> to return to main menu';
}
else if (($_POST["category"]==null))
{
	echo 'Error to add a video it must have a category click <a href="Video_Store.php">here</a> to return to main menu';
}
else if (($_POST["length"]==null))
{
	echo 'Error to add a video it must have a length click <a href="Video_Store.php">here</a> to return to main menu';
}

else
{
	$name=$_POST["name"];
	$category=$_POST["category"];
	$length=$_POST["length"];
	if (!($stmt = $mysqli->prepare("INSERT INTO videos(name, category, length) VALUES (?,?,?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 $error=1;
	}
	if (!$stmt->bind_param("ssi", $name, $category, $length)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	if (!$stmt->execute()) {
		$error=1;
	}
	$stmt->close();
	if ($error==0)
	{
		header('Location: Video_Store.php', true);
	}
	else
	{
		echo 'Error there is already a video with the same name in the inventory click <a href="Video_Store.php">here</a> to return to main menu';
	}
}
?>