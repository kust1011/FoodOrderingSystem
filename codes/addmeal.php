<?php

session_start();
$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";

try {
	if(!isset($_POST['meal_name'])||(empty($_POST['meal_name']) && $_POST['meal_name'] !== '0'))
		throw new Exception('Meal name field required!');
	if(!isset($_POST['meal_price'])||(empty($_POST['meal_price']) && $_POST['meal_price'] !== '0'))
		throw new Exception('Price field required!');
	if(!isset($_POST['meal_quantity'])||(empty($_POST['meal_quantity']) && $_POST['meal_price'] !== '0'))
		throw new Exception('Quantity field required!');
	if(($_FILES['meal_image']["error"]>0))
		throw new Exception($_FILES['meal_image']["error"]);
	
	$meal_name=$_POST['meal_name'];
	$meal_price=$_POST['meal_price'];
	
	if (!((is_int($meal_price) || ctype_digit($meal_price)) && (int)$meal_price >= 0 )) 	
		throw new Exception("Meal price must be non-negative integer!");
	
	$meal_quantity=$_POST['meal_quantity'];
	if (!((is_int($meal_quantity) || ctype_digit($meal_quantity)) && (int)$meal_quantity >= 0 )) 	
		throw new Exception("Meal quantity must be non-negative integer!");
	
	$sid=$_SESSION['sid'];
	
	// open image file
	$img_file = fopen($_FILES["meal_image"]["tmp_name"], "rb");
	$fileContents = fread($img_file, filesize($_FILES["meal_image"]["tmp_name"]));
	fclose($img_file);
	$fileContents = base64_encode($fileContents);
	
	// move the image to folder /upload
	$fileName=$_FILES["meal_image"]["name"];
	if (false !== $pos1 = strripos($fileName, '.')) {
		$fileName1 = uniqid(substr($fileName, 0, $pos1)."_");
	}
	if (false !== $pos2 = strripos($fileName, '.')) {
		$random_name=$fileName1.substr($fileName, $pos2, strlen($fileName));
	}
	if(file_exists("upload/".$random_name))
		throw new Exception('The file is exist!');
	else
		move_uploaded_file($_FILES["meal_image"]["tmp_name"],"upload/".$random_name);
	$fileType = $_FILES["meal_image"]["type"];
	
	// Create connection
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Check connection
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	$stmt=$conn->prepare("insert into meal(sid,name,price,quantity,img,imgType)
		values (:sid,:meal_name,:meal_price,:meal_quantity,:meal_image,:imgType)");
		$stmt->execute(array('sid' => $sid,'meal_name' => $meal_name,'meal_price' => $meal_price,
		'meal_quantity' => $meal_quantity, 'meal_image' => $fileContents, 'imgType' => $fileType));
	throw new Exception('Add successfully.');
}
	
catch(Exception $e){
	$msg = $e->getMessage();
	
	echo<<<EOT
	<!DOCTYPE html>
	<html>
		<body>
			<script>
			alert("$msg");
			window.location.replace("home.php");
			</script>
		</body>
		</html>
EOT;
}

?>
