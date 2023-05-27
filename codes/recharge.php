<script>
console.log(<?=$_POST?>);
</script>
<?php


$servername = "localhost";
$dbname = "sign_up";
$username = "root";
$password = "";

// Set user's input
$value=$_POST['value'];
$account=$_POST['account'];
$uid=$_POST['uid'];

try {
	if (!preg_match("/^[1-9]\d*$/",$value)){
		throw new Exception('Recharge input must be non-negative integer');
	}
	// add money
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$stmt=$conn->prepare("UPDATE user SET walletbalance=walletbalance+:dollar WHERE uid=:uid;");
    $stmt->execute(array('dollar' => $value,'uid' => $uid));
	// create transaction record
	$conn = new PDO("mysql:host=$servername;dbname=shop", $username, $password);

	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$stmt=$conn->prepare("INSERT INTO record (action, time, trader, amount_change, uid)
	VALUES ('Recharge', NOW(), :account, :dollar, :uid);");
    $stmt->execute(array('account' => $account,'dollar' => '+'.$value, 'uid' => $uid));
	
	echo <<<EOT
	<!DOCTYPE html>
	<html>
		<body>
			<script>
			alert("Recharge successfully.");
			window.location.replace("home.php");
			</script>
		</body>
		</html>
EOT;
		exit();
	
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