<?php
$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";


try {
	if (!isset($_REQUEST['name'])||(empty($_REQUEST['name'])&& $_REQUEST['name'] !== '0')){
		echo 'Failed';
		exit();
	}
	
	$name=$_REQUEST['name'];
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// check if name is unique
	$stmt=$conn->prepare("select name from shop where binary name=:name");
	$stmt->execute(array('name' => $name));
	if ($stmt->rowCount()==0){
		echo 'YES';
	}
	else
		echo 'NO';
}
	
catch(Exception $e){
	echo 'FAILED';
}
?>