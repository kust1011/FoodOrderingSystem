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
$name = $row_result['name'];
$price = $row_result['price'];
$quantity = $row_result['quantity'];
$img = $row_result['img'];
$imgType = $row_result['imgType'];
try{
	if (isset($_POST["action"]) && $_POST["action"] == 'update') {
		$name=$_POST['cName'];
		$price=$_POST['cPrice'];
		$quantity=$_POST['cQuantity'];
		if(empty($_POST['cName'])&& $_POST['cName'] !== '0'){
			throw new Exception('Name field cannot be empty!');
		}
		if(empty($_POST['cPrice'])&& $_POST['cPrice'] !== '0'){
			throw new Exception('Price field cannot be empty!');
		}
		if (!((is_int($_POST['cPrice']) || ctype_digit($_POST['cPrice'])) && (int)$_POST['cPrice'] >= 0 )) 	
			throw new Exception("Meal price must be non-negative integer!");
		if(empty($_POST['cQuantity'])&& $_POST['cQuantity'] !== '0'){
			throw new Exception('Quantity field cannot be empty!');
		}
		if (!((is_int($_POST['cQuantity']) || ctype_digit($_POST['cQuantity'])) && (int)$_POST['cQuantity'] >= 0 )) 	
			throw new Exception("Quantity must be non-negative integer!");
		if($_FILES["cImage"]["error"]==0){
			$img_file = fopen($_FILES["cImage"]["tmp_name"], "rb");
			$fileContents = fread($img_file, filesize($_FILES["cImage"]["tmp_name"]));
			fclose($img_file);
			$img = base64_encode($fileContents);
			
			$imgType = $_FILES["cImage"]["type"];
		}
		
		// Create connection
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

		// Check connection
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$stmt=$conn->prepare("UPDATE meal SET name=:name, price=:price, quantity=:quantity, img=:img, imgType=:imgType WHERE mid=:mid");
		$stmt->execute(array('name' => $name,'price' => $price,'quantity' => $quantity,
			'img' => $img, 'imgType' => $imgType, 'mid' => $mid));
		throw new Exception('Update successfully!');
	}
}
catch(Exception $e){
	$msg = $e->getMessage();
	
	echo<<<EOT
		<!DOCTYPE html>
		<html>
		<body>
		<script>
		alert("$msg");
		window.location="http://localhost/DB_hw2/home.php#menu1";
		</script>
		</body>
		</html>
EOT;
}
?>
<html>

<head>
	<meta charset="UTF-8" />
	<title>Edit meal</title>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data" name="formAdd" id="formAdd">
      
	請輸入名稱：<input type="text" name="cName" id="cName" value="<?php echo $name ?>" required><br/>
	請輸入價錢：<input type="text" name="cPrice" id="cPrice" value="<?php echo $price ?>" required><br/>
	請輸入數量：<input type="text" name="cQuantity" id="cQuantity" value="<?php echo $quantity ?>" required><br/>
	請上傳圖片：<input type="file" name="cImage" id="cImage"><br/>
	
	<input type="hidden" name="action" value="update">
	<input type="submit" name="button" value="Edit">
	<input type="button" name="button2" value="Cancel" onclick='goback()'>
</form>
<script>
	function goback(){
		window.location="http://localhost/DB_hw2/home.php#menu1";
	}
</script>
</body>
</html>
