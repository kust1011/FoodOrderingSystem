<?php
$json=['status'=>'1'];
/*
status:
1: successful
2: an order has been canceled
3: an order has been done
4: some meal for an order are not exist
5: no order selected
*/
try{
    if(!isset($_POST['oids'])){
        $json['status']=5;
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
        // get user account
        $db_link = @mysqli_connect($servername, $username, $password, "sign_up");
        $sql = "SELECT * FROM user WHERE uid=".$uid;
        $result4 = mysqli_query($db_link,$sql);
        $row4 = mysqli_fetch_assoc($result4);
        $user_account = $row4['Account'];
        $db_link = @mysqli_connect($servername, $username, $password, $dbname);

        // check the order's status
        if($row['status']!="Not Finish"){
            if($row['status']=="Done"){
                $json['status']=3;
                throw new Exception("Some orders have been finished !");
            }
            else if($row['status']=="Cancel"){
                $json['status']=2;
                throw new Exception("Some orders have been canceled !");
            }
        }
        else{
            // check if the meal is exist
            $sql = "SELECT * FROM order_detail WHERE oid=".$oid;
            $stmt = $db_link->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = mysqli_fetch_assoc($result)){
                $db_link = @mysqli_connect($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM meal where mid=".$row['mid'];
                $result2 = mysqli_query($db_link,$sql);
                if(mysqli_num_rows($result2)!=1){
                    $json['status']=4;
                    throw new Exception("Some meals are not exist !");
                }
            }
        }
    }
    foreach ($_POST['oids'] as $key => $oid){
        // edit meal quantity
        $db_link = @mysqli_connect($servername, $username, $password, $dbname);
        $sql = "SELECT * FROM order_detail WHERE oid='$oid'";
        $result3 = mysqli_query($db_link,$sql);
        while($row = mysqli_fetch_assoc($result3)){ // error
            $db_link = @mysqli_connect($servername, $username, $password, $dbname);
            $sql = "UPDATE meal SET quantity=quantity+".$row['quantity']." WHERE mid=".$row['mid'];
            $result = mysqli_query($db_link,$sql);
        }
        // Refund
        $db_link = @mysqli_connect($servername, $username, $password, $dbname);
        $sql = "SELECT * FROM shop WHERE sid=$sid";
        $result = mysqli_query($db_link,$sql);
        $row = mysqli_fetch_assoc($result);
            // get shop info
            $shop_uid = $row['uid'];
            $shop_name = $row['name'];
        $db_link = @mysqli_connect($servername, $username, $password, "sign_up");
        $sql = "UPDATE user SET walletbalance=walletbalance-".$total_price." WHERE uid=$shop_uid";
        mysqli_query($db_link,$sql);
        $sql = "UPDATE user SET walletbalance=walletbalance+".$total_price." WHERE uid=$uid";
        mysqli_query($db_link,$sql);

        // update order status
        $db_link = @mysqli_connect($servername, $username, $password, $dbname);
        $sql = "UPDATE orders SET status='Cancel', end=NOW() WHERE oid=".$oid;
        $result = mysqli_query($db_link,$sql);

        // create record (Payment)
        $conn = new PDO("mysql:host=$servername;dbname=shop", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("INSERT INTO record (action, time, trader, amount_change, uid)
        VALUES ('Payment', NOW(), :account, :dollar, :uid);");
        $stmt->execute(array('account' => $user_account,'dollar' => '-'.$total_price, 'uid' => $shop_uid));

        // create record (Receive)
        $conn = new PDO("mysql:host=$servername;dbname=shop", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("INSERT INTO record (action, time, trader, amount_change, uid)
        VALUES ('Receive', NOW(), :account, :dollar, :uid);");
        $stmt->execute(array('account' => $shop_name,'dollar' => '+'.$total_price, 'uid' => $uid));
        
    }
    throw new Exception("Successfully cancel !");
}
catch(Exception $e){
    $msg = $e->getMessage();
    $json['error_message']=$msg;
    echo json_encode($json);
}