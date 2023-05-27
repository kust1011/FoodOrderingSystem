<?php
// session
session_start();
$_SESSION['Authenticated']=false;

$servername = "localhost";
$dbname = "sign_up";
$username = "root";
$password = "";

// Set user's input
$Account=$_POST['Account'];
$Password=$_POST['Password'];

try {
	if (!isset($_POST['Account'])||!isset($_POST['Password'])){
		header("Location: index.php");
		throw new Exception('Please input Account and password.');
	}
	if (empty($Account)&& $Account !== '0'||empty($Password)&& $Password !== '0'){
		throw new Exception('Please input Account and password.');
	}

	if (!preg_match("/^[a-zA-z0-9]*$/",$Account)) {  
		throw new Exception('Account: Only alphabets and numbers are allowed.');
	}
	
	if (!preg_match("/^[a-zA-z0-9]*$/",$Password)) {  
		throw new Exception('Password: Only alphabets and numbers are allowed.');
	}
	
	
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// check if name is unique
	$stmt=$conn->prepare("select uid,Account,Password from user where binary Account=:Account");
	$stmt->execute(array('Account' => $Account));
	if ($stmt->rowCount()==1){
		$row = $stmt->fetch();
		if($row['Password']==hash('sha256',$Password)){
			$_SESSION['Authenticated']=true;
			$_SESSION['uid']=$row[0];
			header("Location: home.php");
			exit();
		}
		else
			throw new Exception('Login failed.');
	}
	else
		throw new Exception('Login failed.');
}
	
catch(Exception $e){
	$msg = $e->getMessage();
	session_unset();
	session_destroy();
	
	echo<<<EOT
	<!DOCTYPE html>
	<html>
		<body>
			<script>
			alert("$msg");
			window.location.replace("index.php");
			</script>
		</body>
		</html>
EOT;
}
?>