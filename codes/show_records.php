<?php
//$json=$_POST;
//echo json_encode($json);

$json=['content'=>""];
$uid=$_POST['uid'];
$status=$_POST['r_sort'];
$json['status']=$status;

$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";
$sql = "SELECT * FROM record WHERE uid=".$uid." AND action='".$status."'";
if($status=="All"){
    $sql = "SELECT * FROM record WHERE uid=".$uid;
}
$db_link = new mysqli($servername, $username, $password, $dbname);
$stmt = $db_link->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while($row = mysqli_fetch_assoc($result)){
    $rid=$row['rid'];
    $action=$row['action'];
    $time=$row['time'];
    $trader=$row['trader'];
    $amount_change=$row['amount_change'];
    
    $json['content']=$json['content'].'
    <tr>
    <th scope="row">'.$rid.'</th>
    <td>'.$action.'</td>
    <td>'.$time.'</td>
    <td>'.$trader.'</td>
    <td>'.$amount_change.'</td></tr>';
    
    //$json['content']=$json['content'].'<tr><td><img src=data:'.$imgType.';base64,'.$img.' style = "width:100px; height:100px;" /></td><td>'.$name.'</td><td>'.$price.'</td><td>'.$quantity.'</td></tr>';
}

echo json_encode($json);
?>