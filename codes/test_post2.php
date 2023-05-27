<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "shop";
$conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$stmt=$conn->prepare("SELECT * FROM `meal` where `sid` = 26");
$stmt->execute();

?>