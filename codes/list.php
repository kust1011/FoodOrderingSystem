
<?php
// session
session_start();

$servername = "localhost";
$dbname = "sign_up";
$username = "root";
$password = "";


try {
	if (!isset($_SESSION['Authenticated'])||$_SESSION['Authenticated']!=true){
		header("Location: index.php");
		exit();
	}
	if (isset($_GET['page'])){
		$page=$GET['page'];
	}
	else
		$page=1;
	$postperpage=2;
	
	
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// check if name is unique
	$stmt=$conn->prepare("select Account,Password from user where Account=:Account");
	$stmt->execute(array('Account' => $Account));
	if ($stmt->rowCount()==1){
		$row = $stmt->fetch();
		if($row['Password']==hash('sha256',$Password)){
			$_SESSION['Authenticated']=true;
			$_SESSION['Account']=$row[0];
			header("Location: list.php");
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