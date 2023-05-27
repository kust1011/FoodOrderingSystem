<?php
//$json=$_POST;
//echo json_encode($json);

$json=['content'=>""];
$sid=$_POST['sid'];
$status=$_POST['s_o_sort'];
$json['status']=$status;

$servername = "localhost";
$dbname = "shop";
$username = "root";
$password = "";
$sql = "SELECT * FROM orders WHERE sid=".$sid." AND status='".$status."'";
if($status=="All"){
    $sql = "SELECT * FROM orders WHERE sid=".$sid;
}
$db_link = new mysqli($servername, $username, $password, $dbname);
$stmt = $db_link->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while($row = mysqli_fetch_assoc($result)){
    $oid=$row['oid'];
    $status=$row['status'];
    $start=$row['start'];
    $end=$row['end'];
    $shop_name=$row['shop_name'];
    $total_price=$row['total_price'];
    
    $json['content']=$json['content'].'
    <tr>
    <th scope="row">'.$oid.'</th>
    <td>'.$status.'</td>
    <td>'.$start.'</td>
    <td>'.$end.'</td>
    <td>'.$shop_name.'</td>
    <td>'.$total_price.'
    <form action="" method="post" id="create_order_detail'.$oid.'">
    <input type="text" hidden="true" value='.$oid.' name="oid">
    </td>
    <td><button type="button " style="margin-left: 5px;" class=" btn btn-info "
    data-toggle="modal" data-target="#order_detail" onclick="show_detail(this);" name='.$oid.'>Order Details</button></td>';
    if($status=="Not Finish"){
        $json['content']=$json['content'].'<td><button type="button " style="margin-left: 5px;" class=" btn btn-success " onclick="done_order(this);"
    name='.$oid.'>Done</button></td>';
        $json['content']=$json['content'].'<td><button type="button " style="margin-left: 5px;" class=" btn btn-danger " onclick="cancel_order(this);"
    name='.$oid.'>Cancel</button></td>';
        $json['content']=$json['content'].'<td><input type="checkbox" class="form-check-input" value="1" name="'.'oids['.$oid.']'.'" style="width:20px;height:20px;"></td>';
    }
    $json['content']=$json['content'].'</form></tr>';
    
    //$json['content']=$json['content'].'<tr><td><img src=data:'.$imgType.';base64,'.$img.' style = "width:100px; height:100px;" /></td><td>'.$name.'</td><td>'.$price.'</td><td>'.$quantity.'</td></tr>';
}

echo json_encode($json);