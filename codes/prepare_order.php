<?php
/*
$test = isset($_POST['id']) ? $_POST['id'] : null;
print_r($_POST);
$tt = 3;
printf("<th scope='row'>Picture</th>");
print($tt)
*/
?>

<?php
$json=['status'=>'1',
'content'=>'',
'empty'=>true,
'meal_price'=>0,
'dis_price'=>0,
'total_price'=>0];
$json['error_meals']=array();
$json['post']=$_POST;
/*
status:
1: successful
2: order's quantity exceeds shop has
3: price exceed wallet balance (not implement)
4: meal is not exist

"input quantity is non-negative integer" is ensured
*/
try{
    $servername = "localhost";
    $dbname = "shop";
    $username = "root";
    $password = "";
    $db_link = @mysqli_connect($servername, $username, $password, $dbname);
    //$search_shop_meal_sql = "SELECT * FROM meal where sid=".$shop_value." ORDER BY mid ASC";
    $row_index=1;
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
                    $json['empty']=false;
                    $json['content']=$json['content'].'<tr><th scope="row">'.$row_index.'</th><td><img src="data:'.$row['imgType'].';base64,'.$row["img"].'" style = "width:100px; height:100px;" /></td><td>'.$row['name'].'</td><td>'.$row['price'].'</td><td>'.$value.'</td></tr>';
                    $json['content']=$json['content'].'<tr><td><input type="text" hidden="true" name="id['.$key.']" value="'.$value.'"></td></tr>';
                    $row_index++;
                }
            }
        }
    }
    
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $sid = $_POST['sid'];
    $type = $_POST['delivery_type'];
    
    $json['content']=$json['content'].'<tr><td><input type="text" name="sid" hidden="true" value="'.$sid.'"></td></tr>';
    $json['content']=$json['content'].'<tr><td><input type="text" name="longitude" hidden="true" value="'.$longitude.'"></td></tr>';
    $json['content']=$json['content'].'<tr><td><input type="text" name="latitude" hidden="true" value="'.$latitude.'"></td></tr>';
    $json['content']=$json['content'].'<tr><td><input type="text" name="delivery_type" hidden="true" value="'.$type.'"></td></tr>';

    $search_shop_sql = "SELECT ROUND(ST_Distance_Sphere(POINT($longitude,$latitude),Location),0) 
    AS distant FROM shop where sid=$sid";
    
    $new_search_result = mysqli_query($db_link,$search_shop_sql);
    $row_result = mysqli_fetch_assoc($new_search_result);
    if($type=="Pick-up"){
        $json['dis_price']=0;
    }
    else if($row_result['distant']<1000){
        $json['dis_price']=10;
    }
    else{
        $json['dis_price']=round($row_result['distant']*10/1000);
    }
    $json['total_price']=$json['dis_price']+$json['meal_price'];

    throw new Exception("Done");
}
catch(Exception $e){
    $msg = $e->getMessage();
    $json['error_message']=$msg;
    echo json_encode($json);
}
?>