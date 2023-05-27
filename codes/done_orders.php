<?php
//$json=$_POST;
//echo json_encode($json);
$json=['status'=>'1'];
/*
status:
1: successful
2: order has been done
3: order has been canceled
5: no order selected
*/
try{
    if(!isset($_POST['oids'])){
        $json['status']=4;
        throw new Exception("No order selected !");
    }
    foreach ($_POST['oids'] as $key => $oid){
        $servername = "localhost";
        $dbname = "shop";
        $username = "root";
        $password = "";
        $sql = "SELECT * FROM orders WHERE oid=".$oid;
        $db_link = new mysqli($servername, $username, $password, $dbname);
        $stmt = $db_link->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        // get shop id and user id
        $uid=$row['uid'];
        $sid=$row['sid'];
        $total_price=$row['total_price'];

        // check the order's status
        if($row['status']!="Not Finish"){
            if($row['status']=="Cancel"){
                $json['status']=3;
                throw new Exception("Order has been canceled !");
            }
            else if($row['status']=="Done"){
                $json['status']=2;
                throw new Exception("Order has been finished !");
            }
        }
    }
    foreach ($_POST['oids'] as $key => $oid){
        // update order status
        $db_link = @mysqli_connect($servername, $username, $password, $dbname);
        $sql = "UPDATE orders SET status='Done', end=NOW() WHERE oid=".$oid;
        $result = mysqli_query($db_link,$sql);

    }
    throw new Exception("Done");
}
catch(Exception $e){
    $msg = $e->getMessage();
    $json['error_message']=$msg;
    echo json_encode($json);
}
?>