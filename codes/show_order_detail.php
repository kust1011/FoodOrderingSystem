<?php

//$json=$_POST;
//echo json_encode($json);
$json=['content'=>"",'test'=>1,'total_price'=>0,'subtotal'=>0,'delivery_fee'=>0];
$oid=$_POST['oid'];

$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";
$sql = "SELECT * FROM order_detail WHERE oid=".$oid;
$db_link = new mysqli($servername, $username, $password, $dbname);
$stmt = $db_link->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while($row = mysqli_fetch_assoc($result)){
    $img=$row['img'];
    $imgType=$row['imgType'];
    $name=$row['name'];
    $price=$row['price'];
    $quantity=$row['quantity'];

    $json['content']=$json['content'].'<tr><td><img src=data:'.$imgType.';base64,'.$img.' style = "width:100px; height:100px;" /></td><td>'.$name.'</td><td>'.$price.'</td><td>'.$quantity.'</td></tr>';
}

$sql = "SELECT * FROM orders WHERE oid=".$oid;
$db_link = new mysqli($servername, $username, $password, $dbname);
$stmt = $db_link->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_assoc($result);

$json['total_price']=$row['total_price'];
$json['subtotal']=$row['subtotal'];
$json['delivery_fee']=$row['delivery_fee'];

echo json_encode($json);
?>