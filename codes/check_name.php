<?php
$servername = "localhost";
$dbname = "sign_up";
$username = "root";
$password = "";


try {
	if (!isset($_REQUEST['Account'])||(empty($_REQUEST['Account'])&& $_REQUEST['Account'] !== '0')){
		echo 'Failed';
		exit();
	}
	
	$Account=$_REQUEST['Account'];
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// check if name is unique
	$stmt=$conn->prepare("select Account from user where binary Account=:Account");
	$stmt->execute(array('Account' => $Account));
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