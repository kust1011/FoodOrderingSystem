<?php
session_start();
$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";
$db_link = @mysqli_connect($servername, $username, $password, $dbname);

$mid = $_GET['id'];
if (!((is_int($mid) || ctype_digit($mid)) && (int)$mid >= 0 )){
	echo<<<EOT
		<!DOCTYPE html>
		<html>
		<body>
		<script>
		alert("Please check your account!");
		window.location="http://localhost/DB_hw2/home.php#menu1";
		</script>
		</body>
		</html>
EOT;
}
$sql_getDataQuery = "SELECT * FROM meal WHERE mid = $mid";

$result = mysqli_query($db_link, $sql_getDataQuery);

$row_result = mysqli_fetch_assoc($result);

$sid = $row_result['sid'];
if(!isset($_SESSION['sid'])){
	echo<<<EOT
		<!DOCTYPE html>
		<html>
		<body>
		<script>
		alert("Please check your account!");
		window.location="http://localhost/DB_hw2/home.php#menu1";
		</script>
		</body>
		</html>
EOT;
}
if(isset($_SESSION['sid'])){
	if($_SESSION['sid']!=$sid){
		echo<<<EOT
		<!DOCTYPE html>
		<html>
		<body>
		<script>
		alert("Please check your account!");
		window.location="http://localhost/DB_hw2/home.php#menu1";
		</script>
		</body>
		</html>
EOT;
	}
}



$sql_query = "DELETE FROM meal WHERE mid = $mid";

mysqli_query($db_link,$sql_query);

$db_link->close();

header('Location: home.php#menu1');

?>