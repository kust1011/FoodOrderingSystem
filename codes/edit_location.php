<?php 
	/*
    echo $_POST['latitude'] ."<br />";
    echo $_POST['longitude'] ."<br />";
    echo $_POST['Account'] ."<br />";
    echo "==============================<br />";
    echo "已成功將資料POST過來，且不用刷新頁面！";
	*/
?>

<?php
$servername = "localhost";
$dbname = "sign_up";
$username = "root";
$password = "";

try {
	
	if (!isset($_POST['latitude'])||(empty($_POST['latitude'])&& $_POST['latitude'] !== '0') ||
		!isset($_POST['longitude'])||(empty($_POST['longitude'])&& $_POST['longitude'] !== '0')||
		!isset($_POST['uid'])||(empty($_POST['uid'])&& $_POST['uid'] !== '0')){
		throw new Exception('qq.');
	}
	$longitude=$_POST['longitude'];
	$latitude=$_POST['latitude'];
	$uid=$_POST['uid'];
	
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	$stmt=$conn->prepare("UPDATE user SET Location=ST_GeomFromText('POINT($longitude $latitude)') WHERE uid=:uid");
	$stmt->execute(array('uid' => $uid));
	throw new Exception("Edit successfully!");
}
	
catch(Exception $e){
	$msg = $e->getMessage();
	echo $msg;
}
?>