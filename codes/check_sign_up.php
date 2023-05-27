<?php
// session
session_start();
$_SESSION['Authenticated']=false;

$servername = "localhost";
$dbname = "sign_up";
$username = "root";
$password = "";

// Set user's input
$name=$_POST['name'];
$Account=$_POST['Account'];
$PhoneNumber=$_POST['PhoneNumber'];
$Password=$_POST['Password'];
$retype_password=$_POST['retype_password'];
$latitude=$_POST['latitude'];
$longitude=$_POST['longitude'];

try {
	if (!isset($_POST['name'])||!isset($_POST['Password'])){
		header("Location: index.php");
		throw new Exception('Please input user name and password.');
	}
	if ((empty($name)&& $name !== '0')||(empty($Password)&& $Password !== '0')||(empty($Account)&& $Account !== '0')
	||(empty($PhoneNumber)&& $PhoneNumber !== '0')||(empty($retype_password)&& $retype_password !== '0')
	||(empty($latitude)&& $latitude !== '0')||(empty($longitude)&& $longitude !== '0')){
		throw new Exception('Required fields can not be blank.');
	}
	if ($Password != $retype_password){
		throw new Exception('Please retype the same password.');
	}
	if (!preg_match("/^\d{10,10}$/",$PhoneNumber) || strlen($_POST['PhoneNumber']) != 10){
		throw new Exception('PhoneNumber must be 10 digits.');
	}	
	if (!preg_match("/^[a-zA-Z]*$/",$name)) {  
		throw new Exception('Name: Only alphabets are allowed.');
	}	
	if (!preg_match("/^[a-zA-Z0-9]*$/",$Account)) {  
		throw new Exception('Account: Only alphabet and number are allowed.');
	}
	if (!preg_match("/^[a-zA-Z0-9]*$/",$Password)) {  
		throw new Exception('Password: Only alphabets and number are allowed.');
	}
	if (!preg_match("/^[+-]?([0-9]*[.])?[0-9]+$/",$latitude)||!preg_match("/^[+-]?([0-9]*[.])?[0-9]+$/",$longitude)) {  
		throw new Exception('Latitude and longitude should be floating numbers.');
	}
	if ($latitude > 90.0 || $latitude < (-90.0)) {  
		throw new Exception('the range of latitude should be -90.0 ~ 90.0');
	}
		
	if ($longitude > 180.0 || $longitude< (-180.0)) {  
		throw new Exception('the range of longitude should be -180.0 ~ 180.0');
	}
	
	
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// check if name is unique
	$stmt=$conn->prepare("select uid from user where binary Account=:username");
	$stmt->execute(array('username' => $Account));
	if ($stmt->rowCount()==0){
		$stmt=$conn->prepare("insert into user (name,Account,PhoneNumber,Password,Location)
		values (:name,:Account,:PhoneNumber,:Password,ST_GeomFromText('POINT($longitude $latitude)'))");
		$Password=hash('sha256',$Password);
		$stmt->execute(array('name' => $name,'Account' => $Account,'PhoneNumber' => $PhoneNumber,
		'Password' => $Password));
	
	echo <<<EOT
	<!DOCTYPE html>
	<html>
		<body>
			<script>
			alert("register successfully.");
			window.location.replace("index.php");
			</script>
		</body>
		</html>
EOT;
		exit();
	}
	else
		throw new Exception("Account is registered.");
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
			window.location.replace("sign_up.php");
			</script>
		</body>
		</html>
EOT;
}
?>