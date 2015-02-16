<?php
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
session_start();
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tranac-db", $pass, "tranac-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
  <title>Videos</title>
</head>
<body>';

if (!isset($_SESSION["sort"])||($_SESSION["sort"]=="All"))
{
	if (!$stmt = $mysqli->query("SELECT name, category, length, rented FROM videos")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
}
else
{
	
	$sorter=$_SESSION["sort"];
	if (!$stmt = $mysqli->query("SELECT name, category, length, rented FROM videos WHERE category='$sorter'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
	$_SESSION["sort"]="All";
}


echo '<h>Videos</h>
  <table border="1" bgcolor=#81F7F3 cellpadding = 10>
  <thead> 
    <tr>
      <th>Title</th> 
      <th>Category</th> 
      <th>Length</th> 
      <th>Status</th> 
      <th>Update Status</th> 
      <th>Delete</th>
    </tr> 
  </thead>
<tbody>';

$cats=array();
while($row = mysqli_fetch_array($stmt))	
{
	echo "<tr>" ;
	echo "<td>" . $row['name'] . "</td>";
	echo "<td>" . $row['category'] . "</td>";
	if ($row['length']==0)
	{
		echo "<td> </td>";
	}
	else
	{
	echo "<td>" . $row['length'] . "</td>";
	}
	echo "<td>";
	if (!$row['rented'])
	{
		echo 'available </td>';
		echo '<td><form method="POST" action="checkout.php">';
		echo "<input type=\"hidden\" name=\"name\" value=\"".$row['name']."\">";
		echo '<input type="submit" value="checkout">';
		echo '</form> </td>';
	}
	else
	{
		echo 'checked out </td>';
		echo '<td><form method="POST" action="checkin.php">';
		echo "<input type=\"hidden\" name=\"name\" value=\"".$row['name']."\">";
		echo '<input type="submit" value="returned">';
		echo '</form> </td>';
	}
	echo '<td><form method="POST" action="delete.php">';
    echo "<input type=\"hidden\" name=\"name\" value=\"".$row['name']."\">";
	echo '<input type="submit" value="delete">';
	echo '</form> </td>';
	echo '</tr>';
}
if (!$stmt = $mysqli->query("SELECT category FROM videos")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	
{
	if ((!(in_array($row['category'], $cats)))&&($row['category']!=null))
	{
		array_push($cats,$row['category']);
	}
}
	
echo '</tbody>
</table>
<br>
<fieldset>
<legend>Add Video</legend>
  <form action="addvideo.php" method="post">
		<p>name: <input type="text" name="name" /></p>
		<p>category: <input type="text" name="category" /></p>
		<p>length: <input type="number" name="length" min="1" max="300" /></p>	
		<br><br>
		<input type="submit" value="Submit">
  </form>
</fieldset>
<br>
<fieldset>
  <legend>Filter By Category</legend>
    <form action="filter.php" method="POST">
      <select name="sort">
      <option value="All">All Movies</option>';

  $CatCount=count($cats);
  for ($i=0;$i<$CatCount; $i++)
  {
	echo "<option value=$cats[$i]>$cats[$i]</option>";
  }
echo
      '</select>
      </div>
      <input type="submit" value="Filter">
    </form>
</fieldset>
<br>
<br>	
<form method="POST" action="delete_all.php">
<input type="hidden" name="deletekey" value="xjy">
<input type="submit" value="delete all movies">
</form>
	</body>
</html>';
?>