<?php
$x=$_POST['firstname'];
$y=$_POST['lastname'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
// Connected successfully message
echo "Connected successfully";
echo '<br>'; 

$sql = "INSERT INTO `user` (`fname`, `lname`) VALUES ('$x','$y')";
if ($conn->query($sql) === TRUE) {
	echo "New record created successfully";
	echo '<br>'; 
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
	echo '<br>'; 
}

$conn->close();
?>