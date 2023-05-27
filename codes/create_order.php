<?php
$json=['status'=>'1',
'meal_price'=>0,
'dis_price'=>0,
'total_price'=>0];
/*
status:
1: successful
2: order's quantity exceeds shop has
3: price exceed wallet balance (not implement)
4: meal is not exist
*/
try{
    $servername = "localhost";
    $dbname = "shop";
    $username = "root";
    $password = "";
    $db_link = @mysqli_connect($servername, $username, $password, $dbname);
    //$search_shop_meal_sql = "SELECT * FROM meal where sid=".$shop_value." ORDER BY mid ASC";
    foreach ($_POST['id'] as $key => $value) {
        $sql = "SELECT * FROM meal where mid=".$key;
        $result = mysqli_query($db_link,$sql);
        if(mysqli_num_rows($result)!=1){
            $json['status']=4;
            throw new Exception("4");
        }
        else{
            $row = mysqli_fetch_assoc($result);
            if($row['quantity']<$value){
                $json['status']=2;
                array_push($json['error_meals'],$row['name']);
            }
            else{
                if($value!=0){
                    $json['meal_price'] = $json['meal_price'] + $row['price'] * $value;
                }
            }
        }
    }
    
    $sid = $_POST['sid'];
    $uid = $_POST['uid'];
    // get user account
    $db_link = @mysqli_connect($servername, $username, $password, "sign_up");
    $sql = "SELECT ST_AsText(Location) as Location FROM user WHERE uid=".$uid;
    $result4 = mysqli_query($db_link,$sql);
    $row4 = mysqli_fetch_assoc($result4);
    $Location=$row4['Location'];
    $Location1=str_replace("POINT(","",$Location);
    $Location1=str_replace(")","",$Location1);
    $NewStringn = preg_split("[\s]", $Location1);
    $longitude = $NewStringn[0];
    $latitude = $NewStringn[1];
    $db_link = @mysqli_connect($servername, $username, $password, $dbname);
    
    $user_account = $_POST['user_account'];
    $search_shop_sql = "SELECT ROUND(ST_Distance_Sphere(POINT($longitude,$latitude),Location),0) 
    AS distant, name FROM shop where sid=$sid";
    $new_search_result = mysqli_query($db_link,$search_shop_sql);
    $row_result = mysqli_fetch_assoc($new_search_result);
    $shop_name = $row_result['name'];
    if($_POST['delivery_type']=="Pick-up"){
        $json['dis_price']=0;
    }
    else if($row_result['distant']<1000){
        $json['dis_price']=10;
    }
    else{
        $json['dis_price']=round($row_result['distant']*10/1000);
    }
    $json['total_price']=$json['dis_price']+$json['meal_price'];
    if($_POST['walletbalance']<$json['total_price']){
        $json['status']=3;
    }

    if($json['status']!=1){
        throw new Exception($json['status']);
    }
    else{
        // payment
        $sql = "SELECT * FROM shop WHERE sid=$sid";
        $result = mysqli_query($db_link,$sql);
        $row = mysqli_fetch_assoc($result);
        $shop_uid = $row['uid'];
        $shop_name = $row['name'];
        $db_link = @mysqli_connect($servername, $username, $password, "sign_up");
        $sql = "UPDATE user SET walletbalance=walletbalance+".$json['total_price']." WHERE uid=$shop_uid";
        mysqli_query($db_link,$sql);
        $sql = "UPDATE user SET walletbalance=walletbalance-".$json['total_price']." WHERE uid=$uid";
        mysqli_query($db_link,$sql);
        $db_link = @mysqli_connect($servername, $username, $password, $dbname);
        // create order
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("insert into orders(status, start, shop_name, total_price, subtotal, delivery_fee, uid, sid)
			values ('Not Finish', NOW(),:shop_name,:total_price,:subtotal,:delivery_fee,:uid,:sid)");
        $stmt->execute(array('shop_name' => $shop_name, 'total_price' => $json['total_price'],
        'subtotal' => $json['meal_price'], 'delivery_fee' => $json['dis_price'], 
        'uid' => $uid, 'sid' => $sid));
        // first get order primary key
        $sql = "SELECT LAST_INSERT_ID() as oid;";
        $result=$conn->query($sql);
        $row=$result->fetch(PDO::FETCH_ASSOC);
        $oid=$row['oid'];
        foreach ($_POST['id'] as $key => $value){
            // get meal's detail
            $sql = "SELECT * FROM meal where mid=".$key;
            $result = mysqli_query($db_link,$sql);
            $row = mysqli_fetch_assoc($result);
            // create order_detail
            $stmt=$conn->prepare("insert into order_detail(mid, name, price, quantity, img, imgType, oid)
			    values (:mid, :name, :price, :quantity, :img, :imgType, :oid)");
            $stmt->execute(array('mid' => $key, 'name' => $row['name'], 'price' => $row['price'],
            'quantity' => $value, 'img' => $row['img'], 'imgType' => $row['imgType'], 'oid' => $oid));
            // edit meal amount
            $stmt=$conn->prepare("UPDATE meal SET quantity=quantity-:value WHERE mid=:mid");
	        $stmt->execute(array('value' => $value, 'mid' => $key));
        }
        // create record (Payment)
        $conn = new PDO("mysql:host=$servername;dbname=shop", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("INSERT INTO record (action, time, trader, amount_change, uid)
        VALUES ('Payment', NOW(), :account, :dollar, :uid);");
        $stmt->execute(array('account' => $shop_name,'dollar' => '-'.$json['total_price'], 'uid' => $uid));
        // create record (Receive)
        $conn = new PDO("mysql:host=$servername;dbname=shop", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("INSERT INTO record (action, time, trader, amount_change, uid)
        VALUES ('Receive', NOW(), :account, :dollar, :uid);");
        $stmt->execute(array('account' => $user_account,'dollar' => '+'.$json['total_price'], 'uid' => $shop_uid));
        

        throw new Exception("Done");
    }
}
catch(Exception $e){
    $msg = $e->getMessage();
    $json['error_message']=$msg;
    echo json_encode($json);
}
?>