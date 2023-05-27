<?php 
	/*
    echo $_POST['uid'] ."<br />";
    echo $_POST['shop_name'] ."<br />";
    echo $_POST['shop_category'] ."<br />";
    echo $_POST['latitude'] ."<br />";
    echo $_POST['longitude'] ."<br />";
	*/
?>

<?php
$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";

try {
	if(!isset($_POST['shop_name'])||(empty($_POST['shop_name'])&& $_POST['shop_name'] !== '0'))
		throw new Exception('Shop name field required!');
	if(!isset($_POST['shop_category'])||(empty($_POST['shop_category'])&& $_POST['shop_category'] !== '0'))
		throw new Exception('Category field required!');
	if(!isset($_POST['latitude'])||(empty($_POST['latitude'])&& $_POST['latitude'] !== '0'))
		throw new Exception('latitude field required!');
	if(!isset($_POST['longitude'])||(empty($_POST['longitude'])&& $_POST['longitude'] !== '0'))
		throw new Exception('longitude field required!');
	if(!isset($_POST['uid'])||(empty($_POST['uid'])&& $_POST['uid'] !== '0'))
		throw new Exception('Please login!');
	if (!preg_match("/^[+-]?([0-9]*[.])?[0-9]+$/",$_POST['latitude'])||!preg_match("/^[+-]?([0-9]*[.])?[0-9]+$/",$_POST['longitude'])) {  
		throw new Exception('Latitude and longitude should be floating numbers.');
	}
	if ($_POST['latitude'] > 90.0 || $_POST['latitude']< (-90.0)) {  
		throw new Exception('the range of latitude should be -90.0 ~ 90.0');
	}
	if ($_POST['longitude'] > 180.0 || $_POST['longitude']< (-180.0)) {  
		throw new Exception('the range of longitude should be -180.0 ~ 180.0');
	}

	$name=$_POST['shop_name'];
	$category=$_POST['shop_category'];
	$longitude=$_POST['longitude'];
	$latitude=$_POST['latitude'];
	$uid=$_POST['uid'];
	
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	$stmt=$conn->prepare("select sid from shop where binary name=:name");
	$stmt->execute(array('name' => $name));
	if ($stmt->rowCount()==0){
		$stmt=$conn->prepare("insert into shop(uid, name, category, Location)
			values (:uid,:name,:category,ST_GeomFromText('POINT($longitude $latitude)'))");
		$stmt->execute(array('uid' => $uid,'name' => $name,'category' => $category,));
		
		$dbname = "sign_up";
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$stmt=$conn->prepare("UPDATE user SET role='manager' WHERE uid=:uid");
		$stmt->execute(array('uid' => $uid));
		
		throw new Exception('Successfully register!');
	}
	else
		throw new Exception('The shop name has been registered');
}
	
catch(Exception $e){
	$msg = $e->getMessage();
	echo $msg;
}
?>