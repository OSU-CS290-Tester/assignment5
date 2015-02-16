<?php
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
$set=$_POST["name"];
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tranac-db", $pass, "tranac-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!($stmt = $mysqli->prepare("UPDATE videos SET rented=1 WHERE name=?"))) {
     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("s", $set)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->close();
header('Location: Video_Store.php', true);
?>