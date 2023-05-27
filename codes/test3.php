<?php
	include_once 'db_connection.php';
	include_once 'class_user.php';
	include_once 'class_shop.php';

	// check property login
	session_start();
	if(!isset($_SESSION)||!isset($_SESSION['Authenticated'])){
		$message = "Please login.";
		echo "<script type='text/javascript'>alert($message);</script>";
		header('Location: index.php');
		session_destroy();
		exit();
	}
	if(isset($_SESSION['Authenticated'])){
		if($_SESSION['Authenticated']==false){
			$message = "Please login.";
			echo "<script type='text/javascript'>alert($message);</script>";
			header('Location: index.php');
			session_destroy();
			exit();
		}
	}

	//class user
	$user = new user($_SESSION['uid']);
	//class shop
	$shop = new shop($_SESSION['uid']);
	if($shop->ifshopexist) $_SESSION['sid']=$shop->sid;
?>
<script>
	function validate(){
		<?php echo "var check=".$_SESSION['Authenticated'].";"; ?>
		<?php echo "var ifshopexist =$shop->ifshopexist;"; ?>
		if(ifshopexist){
			new_shop_name.setAttribute("readonly", "readonly");new_shop_name.placeholder="<?php Print($shop->name); ?>";
			new_shop_category.setAttribute("readonly", "readonly");new_shop_category.placeholder="<?php Print($shop->category); ?>";
			new_s_latitude.setAttribute("readonly", "readonly");new_s_latitude.placeholder="<?php Print($shop->latitude); ?>";
			new_s_longitude.setAttribute("readonly", "readonly");new_s_longitude.placeholder="<?php Print($shop->longitude); ?>";
			newshop.setAttribute("readonly", "readonly");
			$('#newshop').prop('disabled', true);$('#m_add').prop('disabled', false);
		}
	}
</script>
<!doctype html>
<html lang="en">

<head>
  <!-- Bootstrap CSS -->

	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!-- For DataTables  -->
	<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
	<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
	
  <!-- Required meta tags -->
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">

  	<title>Home</title>
</head>

<body onload="validate()">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand " href="#">Database_HW2</a>/
			
				<div class="navbar-header">
					<a class="navbar-brand " href="index.php?clearSession=true">Log out</a>
				</div> 
			</div>
        </div>
    </nav>

    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home">Home</a></li>
            <li><a href="#menu1">Shop</a></li>
            <li><a href="#menu2">My Order</a></li>
            <li><a href="#menu3">Shop Order</a></li>
            <li><a href="#menu4">Transaction Record</a></li>
            <li><a href="index.php?clearSession=true">Logout</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h3>Profile</h3>
                <div class="row">
                	<div class="col-xs-12">
						
					<!-- print user profile -->
					Account: <?php printf($user->Account)?>, Name: <?php printf($user->name)?>, Role: <?php printf($user->role)?>, PhoneNumber: <?php printf($user->PhoneNumber)?>
					<br>Location(longitude, latitude): <?php printf($user->longitude)?>, <?php printf($user->latitude)?>

                <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#location">Edit location</button>

                <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-sm"> 
                        <div class="modal-content">
                            <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Edit location</h4>
							</div>
                        
							<form id="form1" method="post">
								<div class="modal-body">
									<input type="text" class="form-control" id="longitude" name="longitude" placeholder="Enter new longitude">
									<br>
									<input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter new latitude">
								</div>
								<div class="modal-footer">
									<input type="button" id="submitData" value="Edit" class="btn btn-default" data-dismiss="modal" onclick="change_position();"/>
								</div>

							</form>

							<div id="results">
							<!-- show edit_location -->
							</div>

							<script>
								function change_position() {
									var re = new RegExp("^[+-]?([0-9]*[.])?[0-9]+$");
									var latitude = document.getElementById("latitude").value;
									var longitude = document.getElementById("longitude").value;
									if (latitude == "" || longitude == ""){
										window.alert('Latitude or longitude cannot be empty.');
										window.location.href='home.php';
										exit();
									}
									if (!re.test(latitude) || !re.test(longitude)){
										window.alert('Latitude and longitude should be floating numbers.');
										window.location.href='home.php';
										exit();
									}
									if ( latitude > 90.0 || latitude < -90.0){
										window.alert("The range of latitude should be -90.0 ~ 90.0");
										window.location.href='home.php';
										exit();
									}

									if (longitude > 180.0 || longitude < -180.0){
										window.alert("The range of longitude should be -180.0 ~ 180.0");
										window.location.href='home.php';
										exit();
									}
									<?php echo "var uid ='$user->uid';"; ?>
									$.post("edit_location.php", { latitude: latitude, longitude: longitude, uid: uid}/*,
										function(data) {
											//$('#results').html(data);
											$('#form1')[0].reset();
										}*/);
									alert("Edit successfully!");
									window.location.href='home.php';
								}
							</script>
						</div>
                    </div>
                </div>

				<!-- print user walletbalance -->
				<br>Walletbalance: <?php printf($user->walletbalance)?>
                
                <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#Recharge">Add value</button>

                <div class="modal fade" id="Recharge"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Recharge</h4>
                            </div>
							<form action="recharge.php" method="post">
                            <div class="modal-body">
                                <input type="text" class="form-control" name="value" placeholder="enter add value"  autocomplete="off">
                            </div>
							<input type="text" hidden="true" value=<?=$user->uid?> name="uid">
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-default" value="Add">
                            </div>
							</form>
                        </div>
                    </div>
                </div> 
					</div>
				</div>
                
                <h3>Search</h3>
                <div class=" row  col-xs-8">
                    <form class="form-horizontal"  method="post">

                        <div class="form-group">
                            <label class="control-label col-sm-1" for="Shop">Shop</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="s_shop_name" placeholder="Enter Shop name" autocomplete="off">
                            </div>
                            <label class="control-label col-sm-1" for="distance">Distance</label>

                            <div class="col-sm-5">
                                <select class="form-control" name="s_distance" id="sel1">
                                    <option>none</option>
                                    <option>near</option>
                                    <option>medium </option>
                                    <option>far</option>
                                </select>
                                <span>near:(&lt;=1000 公尺)</br>medium:(>1000 公尺 and &lt;=5000 公尺)</br>far:(>5000 公尺)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-1" for="Price">Price</label>
                            <div class="col-sm-2">
                                <input type="number" name="s_price_low" class="form-control" autocomplete="off">
                                <style>
                                    input::-webkit-outer-spin-button,
                                    input::-webkit-inner-spin-button {
                                        -webkit-appearance: none;
                                    }
                                    input[type="number"]{
                                        -moz-appearance: textfield;
                                    }
                                </style>
                            </div>
                            <label class="control-label col-sm-1" for="~">~</label>
                            <div class="col-sm-2">
                                <!-- price -->
                                <input type="number" name="s_price_high" class="form-control" autocomplete="off">
                            </div>
                            <label class="control-label col-sm-1" for="Meal">Meal</label>
                            <div class="col-sm-5">
                                <!-- meal -->
                                <input type="text" list="Meals" name="s_meal_name" class="form-control" id="Meal" placeholder="Enter Meal" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-1" for="category"> Category</label>
                            <div class="col-sm-5">
                                <!-- category -->
                                <input type="text" list="categorys" name="s_category" class="form-control" id="category" 
                                placeholder="Enter shop category" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-1" for="s_sort">Sort_by</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="s_sort" id="s_sort" onchange="generate_sel2(this.value)">
                                    <option>None</option>
                                    <option>Shop Name</option>
                                    <option>Category </option>
                                    <option>Far</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <!-- sort -->
                                <select hidden name="s_sort_2" id="s_sort_2" value=>
                                </select>
                                <script>
                                    function generate_sel2(value){
                                        let element = document.getElementById("s_sort_2");
                                        if(value=='None'){
                                            element.setAttribute("hidden", "hidden");
                                            element.removeAttribute("class");
                                        }
                                        else{
                                            var options = "";
                                            element.removeAttribute("hidden");
                                            element.setAttribute("class", "form-control");
                                            options += "<option>Ascending</option>";
                                            options += "<option>Descending</option>";
                                            element.innerHTML = options;
                                        }
                                    }
                                </script>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="active_search" style="margin-left: 18px;"class="btn btn-primary" value="test" />
                            <input type="submit" name="search" style="margin-left: 18px;"class="btn btn-primary" value="Search"/>
                        </div>
                    
                    </form>
                </div>

                <div class="row">
                    <div class="  col-xs-8">
                        <table id="search_result_table" class="table table-striped" style=" margin-top: 15px;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">shop name</th>
                                    <th scope="col">shop category</th>
                                    <th scope="col">Distance</th>
                                    <th scope="col" hidden>menu</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    if(isset($_POST['active_search'])){
                                        $servername = "localhost";
                                        $dbname = "shop";
                                        $username = "root";
                                        $password = "";

                                        $s_shop_name = $_POST['s_shop_name'];
                                        $s_distance = $_POST['s_distance'];
                                        $s_price_low = $_POST['s_price_low'];
                                        $s_price_high = $_POST['s_price_high'];
                                        $s_meal_name = $_POST['s_meal_name'];
                                        $s_category = $_POST['s_category'];
                                        $s_sort = $_POST['s_sort'];

                                        $query = "SELECT distinct sid FROM shop LEFT OUTER JOIN meal USING(sid)";
                        
                                        $conditions = array();
                                        $parameters = array();

                                        if(!empty($s_shop_name) || $s_shop_name=='0'){
                                            $s_shop_name=strtoupper($s_shop_name);
                                            $conditions[] = "	UPPER(shop.name) LIKE ?";
                                            $parameters[] = '%'.$s_shop_name.'%';
                                        }
                                        if(!empty($s_distance) || $s_distance=='0'){
                                            if($s_distance=='far'){
                                                $conditions[] = " ROUND(ST_Distance_Sphere(POINT(?,?),Location),0) >5000 ";
                                                $parameters[] = $user->longitude;
                                                $parameters[] = $user->latitude;
                                            }
                                            else if($s_distance=='medium'){
                                                $conditions[] = " ROUND(ST_Distance_Sphere(POINT(?,?),Location),0) BETWEEN '1000' AND '5000'";
                                                $parameters[] = $user->longitude;
                                                $parameters[] = $user->latitude;
                                            }
                                            else if($s_distance=='near'){
                                                $conditions[] = " ROUND(ST_Distance_Sphere(POINT(?,?),Location),0)<1000 ";
                                                $parameters[] = $user->longitude;
                                                $parameters[] = $user->latitude;
                                            }
                                        }
                                        if(!empty($s_price_low) || $s_price_low=='0'){
                                            $conditions[] = "meal.price>=?";
                                            $parameters[] = $s_price_low;
                                        }
                                        if(!empty($s_price_high) || $s_price_high=='0'){
                                            $conditions[] = "meal.price<=?";
                                            $parameters[] = $s_price_high;
                                        }
                                        if(!empty($s_meal_name) || $s_meal_name=='0'){
                                            $s_meal_name=strtoupper($s_meal_name);
                                            $conditions[] = "	UPPER(meal.name) LIKE ?";
                                            $parameters[] = '%'.$s_meal_name.'%';
                                        }
                                        if(!empty($s_category) || $s_category=='0'){
                                            $s_category=strtoupper($s_category);
                                            $conditions[] = "	UPPER(shop.category) LIKE ?";
                                            $parameters[] = '%'.$s_category.'%';
                                        }
                                        
                                        if(!empty($s_sort) || $s_sort=='0') {
                                            if($s_sort=='Shop Name'){
                                                $order_condition = " ORDER BY shop.name ";
                                            }
                                            else if($s_sort=='Category'){
                                                $order_condition = " ORDER BY shop.category	";
                                            }
                                            else if($s_sort=='Far'){
                                                $order_condition = " ORDER BY ST_Distance_Sphere(POINT(?, ?),Location) ";
                                                $parameters[] = $user->longitude;
                                                $parameters[] = $user->latitude;
                                            }
                                        }
                                        if(isset($_POST['s_sort_2'])){	
                                            if(!empty($_POST['s_sort_2']) || $_POST['s_sort_2']=='0') {
                                                if($_POST['s_sort_2']=='Ascending'){
                                                    $order_condition_2 = " ASC ";
                                                }
                                                else if($_POST['s_sort_2']=='Descending'){
                                                    $order_condition_2 = " DESC ";
                                                }
                                            }
                                        }

                                        $sql = $query;
                                        if (count($conditions) > 0) {
                                            $sql .= " WHERE " . implode(' AND ', $conditions);
                                        }
                                        if (isset($order_condition)){
                                            $sql.=$order_condition;
                                            if(isset($order_condition_2)){
                                                $sql.=$order_condition_2;
                                            }
                                        }

                                        $db_link = new mysqli($servername, $username, $password, $dbname);
                                        $stmt = $db_link->prepare($sql);
                                        if(count($parameters)!=0)
                                            $stmt->bind_param(str_repeat("s", count($parameters)), ...$parameters);
                                        $stmt->execute();
                                        $search_result = $stmt->get_result();

                                        $row_index=1;

                                        while($row_result = mysqli_fetch_assoc($search_result)) {
                                            $shop_sid = $row_result['sid'];
                                            $search_shop[] = $shop_sid;
                                            $search_shop_sql = "SELECT sid,uid,name,category,
                                            ROUND(ST_Distance_Sphere(POINT($user->longitude,$user->latitude),Location),0) AS distant 
                                            FROM shop where sid=$shop_sid";
                                            $new_search_result = mysqli_query($db_link,$search_shop_sql);
                                            $new_row_result = mysqli_fetch_assoc($new_search_result);
								?>
											<tr>
											<th scope="row"><?php printf($row_index)?></th>
											<td><?php print($new_row_result['name'])?></td>
											<td><?php print($new_row_result['category'])?></td>
											<td><?php print($new_row_result['distant']);
											if($new_row_result['distant'] > 5000)
												echo "(far)";
											else if($new_row_result['distant'] > 1000)
												echo "(medium)";
											else
												echo "(near)";
											?>
											</td>
											<td><button type='button' class='btn btn-info' data-toggle='modal' data-target=<?php echo "#search_".$shop_sid ?>>Open menu</button></td>
								<?php
                                            $row_index+=1;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>

                        <script type="text/javascript">
                            $(function() {
                                $("#search_result_table").dataTable({
                                    aoColumnDefs: [
                                        {
                                            "bSortable": false,
                                            "aTargets": [3]  // 哪些列不排序
                                        }
                                    ],
                                    searching: false,
                                    "iDisplayLength": 5,
                                    "aLengthMenu": [[5, 10, 15, 20,  -1], [5, 10, 15, 20, "All"]]
                                });
                            });
                        </script>

                        <?php
                            if(isset($search_shop))
                                foreach($search_shop as $shop_value){
						?>
							<div class="modal fade" id=<?= "search_".$shop_value ?> data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
							<div class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header">
                            <form action="" id=<?="prepare_order".$shop_value?>>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Menu</h4>
							</div>
							<div class="modal-body">
							<div class="row">
							<div class="col-xs-12">
							<table class="table" style=" margin-top: 15px;">
							<thead>
							<tr>
							<th scope="col">#</th>
							<th scope="col">Picture</th>
							<th width="20px" scope="col">meal name</th>
							<th scope="col">price</th>
							<th scope="col">Quantity</th>
							<th scope="col">Order</th>
							</tr>
							</thead>
							<tbody>
                                    
						<?php
							$servername = "localhost";
							$dbname = "shop";
							$username = "root";
							$password = "";
							$db_link = @mysqli_connect($servername, $username, $password, $dbname);
							$search_shop_meal_sql = "SELECT * FROM meal where sid=".$shop_value." ORDER BY mid ASC";
							$search_shop_meal_result = mysqli_query($db_link,$search_shop_meal_sql);
							$row_index2=1;
							while($row_result2 = mysqli_fetch_assoc($search_shop_meal_result)) {
								$img=$row_result2["img"];
								$logodata = $img;
						?>
								<tr>
								<th scope="row"><?php printf($row_index2)?></th>
								<td><img src=<?= "data:".$row_result2['imgType'].';base64,'.$logodata?> style = "width:100px; height:100px;" /></td>
								<td><?php printf($row_result2['name'])?></td>
								<td><?php printf($row_result2['price'])?></td>
								<td><?php printf($row_result2['quantity'])?></td>
								<td>
								<input type="text" name="sid" hidden="true" value=<?=$shop_value?>>
								<input type="text" name="longitude" hidden="true" value=<?=$user->longitude?>>
								<input type="text" name="latitude" hidden="true" value=<?=$user->latitude?>>
								<button id=<?= "minus_".$row_result2['mid']?>>−</button>
								<div class="col-sm-6">
								<input type="text" class="form-control" name=<?="id[".$row_result2['mid']."]"?> id=<?= "Order_Quantity_input_".$row_result2['mid']?> 
								autocomplete="off" value="0" oninput="this.value=value.replace(/[^\d]|^[0]/g,'')" onafterpaste="this.value=value.replace(/\D/g,'')">
								</div>
								
								<button id=<?= "plus_".$row_result2['mid']?>>+</button>
								</td>
								</tr>
								<?php
								echo '<script>';
								echo 'const minusButton_'.$row_result2['mid'].' = document.getElementById("minus_'.$row_result2['mid'].'");';
								echo 'const plusButton_'.$row_result2['mid'].' = document.getElementById("plus_'.$row_result2['mid'].'");';
								echo 'const inputField_'.$row_result2['mid'].' = document.getElementById("Order_Quantity_input_'.$row_result2['mid'].'");';
								echo 'minusButton_'.$row_result2['mid'].'.addEventListener("click", event => {';
								echo 'event.preventDefault();';
								echo 'const currentValue = Number(inputField_'.$row_result2['mid'].'.value) || 0;';
								echo 'if (currentValue != 0)';
								echo 'inputField_'.$row_result2['mid'].'.value = currentValue - 1;';
								echo '});';
								echo 'plusButton_'.$row_result2['mid'].'.addEventListener("click", event => {';
								echo 'event.preventDefault();';
								echo 'const currentValue = Number(inputField_'.$row_result2['mid'].'.value) || 0;';
								echo 'inputField_'.$row_result2['mid'].'.value = currentValue + 1;';
								echo '});';
								echo '</script>';
                                        $row_index2=$row_index2+1;
                                    }
                                ?>
                                    </tbody>
                                    </table>
                                    <label for=<?="delivery_type".$shop_value?>>Type: </label>
                                    <select id=<?="delivery_type".$shop_value?> name="delivery_type">
                                    <option>Delivery</option>
                                    <option>Pick-up</option>
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="cal_price_btn" data-toggle="modal" data-target="#macdonald"
									name=<?=$shop_value?>>Calculate the price</button>
                                    </form>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
								<?php
                                }
                        ?>
                        <script>
                        var request;
                        $(".cal_price_btn").click(function() {
                            event.preventDefault();
                            if (request) {
                                request.abort();
                            }
                            var $form = $("#prepare_order"+this.name);
                            var $inputs = $form.find("input"); //, select, button, textarea
                            var serializedData = $form.serialize();

                            $inputs.prop("disabled", true);
                            request = $.ajax({
                                url: "prepare_order.php",
                                type: "post",
                                dataType: 'json',
                                data: serializedData
                            });

                            // Callback handler that will be called on success
                            request.done(function (response, textStatus, jqXHR){
                                // Log a message to the console
                                if(response.status==2){
                                    var str = "";
                                    response.error_meals.forEach(element => str+=element+"\n");
                                    alert('Your input quantity is exceeded to the shop has!'
                                    +'\n'+'Meal:\n'+str);
                                    window.location.reload();
                                }
                                else if(response.status==4){
                                    alert('Meal is not exist!');
                                    window.location.reload();
                                }
                                //訂單是空包彈
                                else if(response.empty==true){
                                    alert('Please select meal before Order!');
                                    $( "#fortest1" ).empty();
                                    document.getElementById("order_subtotal").innerHTML = 0;
                                    document.getElementById("order_delivery").innerHTML = 0;
                                    document.getElementById("order_totalprice").innerHTML = 0;
                                    $('#order_btn').prop('disabled', true);
                                }
                                
                                else{
                                    console.log(response);
                                    console.log("Hooray, it worked!");
                                    $( "#fortest1" ).empty().append( response.content );
                                    document.getElementById("order_subtotal").innerHTML = response.meal_price;
                                    document.getElementById("order_delivery").innerHTML = response.dis_price;
                                    document.getElementById("order_totalprice").innerHTML = response.total_price;
                                    $( '#order_btn').prop('disabled', false);
                                }
                                //console.log(response.success);
                                //console.log(response.content);
                                //var content = $( response ).find( 'th' );
                                //console.log(content);
                                
                            });
                            // Callback handler that will be called regardless
                            // if the request failed or succeeded
                            request.always(function () {
                                // Reenable the inputs
                                $inputs.prop("disabled", false);
                            });
                        })
                        </script>

                        <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
								<form action="" id="create_order">
								<input type="text" hidden="true" name="walletbalance" value=<?=$user->walletbalance?>>
								<input type="text" hidden="true" name="uid" value=<?=$user->uid?>>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">menu</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="  col-xs-12">
                                                <table class="table" style=" margin-top: 15px;">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Picture</th>
                                                            <th scope="col">meal name</th>
                                                            <th scope="col">price</th>
                                                            <th scope="col">Order Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id='fortest1'>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
                                                            <td>Hamburger</td>
                                                            <td id='fortest2'>80 </td>
                                                            <td>20 </td>
                                                            <td> <input type="checkbox" id="cbox1" value="Hamburger"></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td><img src="Picture/2.jpg" with="10" heigh="10" alt="coffee"></td>
                                                            <td>coffee</td>
                                                            <td>50 </td>
                                                            <td>20</td>
                                                            <td><input type="checkbox" id="cbox2" value="coffee"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>    
                                        </div>
                                    </div>
									<div class="modal-footer">
                                        <span>Subtotal: $</span>
                                        <span id="order_subtotal">0</span>
                                        </br>
                                        <span>Delivery fee: $</span>
                                        <span id="order_delivery">0</span>
                                        </br>
                                        <span>Total Price: $</span>
                                        <span id="order_totalprice">0</span>
                                        </br>
										<button type="button" id="order_btn" class="btn btn-default" data-dismiss="modal">Order</button>
									</div>
								</div> 
								</form>
                            </div>    
                        </div>
                        <script>
                        var request;
                        $("#order_btn").click(function() {
                            event.preventDefault();
                            if (request) {
                                request.abort();
                            }
                            var $form = $("#create_order");
                            var $inputs = $form.find("input"); //, select, button, textarea
                            var serializedData = $form.serialize();

                            $inputs.prop("disabled", true);
                            request = $.ajax({
                                url: "create_order.php",
                                type: "post",
                                dataType: 'json',
                                data: serializedData
                            });

                            // Callback handler that will be called on success
                            request.done(function (response, textStatus, jqXHR){
								console.log("Hooray, it worked!");
                                console.log(response);
								if(response.status==2){
                                    var str = "";
                                    response.error_meals.forEach(element => str+=element+"\n");
                                    alert('Your input quantity is exceeded to the shop has!'
                                    +'\n'+'Meal:\n'+str);
                                    window.location.reload();
                                }
                                else if(response.status==3){
                                    alert('You don\'t have sufficient balance.');
                                    window.location.reload();
                                }
                                else if(response.status==4){
                                    alert('Meal is not exist!');
                                    window.location.reload();
                                }
                                else{
                                    console.log(response);
                                    console.log("Hooray, it worked!");
                                    alert('Order successfully!');
                                    window.location.reload();
                                }
                                //console.log(response.success);
                                //console.log(response.content);
                                //var content = $( response ).find( 'th' );
                                //console.log(content);
                                
                            });
                            // Callback handler that will be called regardless
                            // if the request failed or succeeded
                            request.always(function () {
                                // Reenable the inputs
                                $inputs.prop("disabled", false);
                            });
                        })
                        </script>
                    </div>
                </div>

            </div>

            <script>
                function check_shopname(name){
                    if(name!=""){
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function(){
                            var message;
                            if(this.readyState == 4 && this.status == 200){
                                switch(this.responseText){
                                    case 'YES':
                                        message='The name is available.';	
                                        break;
                                    case 'NO':
                                        message='The name is not available.';
                                        break;
                                    default:
                                        message='Oops. There is something wrong';
                                        break;
                                }
                                document.getElementById("msg").innerHTML = message;
                            }
                        };
                        xhttp.open("POST", "check_shopname.php", true);
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhttp.send("name="+name);
                    }
                    else
                        document.getElementById("msg").innerHTML = "";
                }
            </script>

            <div id="menu1" class="tab-pane fade">
                <form id="form2" method="post">
                    <h3> Start a business </h3>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="new_shop_name">shop name</label>
                                <input class="form-control" name="new_shop_name" id="new_shop_name" placeholder="macdonald" type="text" 
                                oninput="check_shopname(this.value);">
                            </div>
                            <div class="col-xs-2">
                                <label for="new_shop_category">shop category</label>
                                <input class="form-control" name="new_shop_category" id="new_shop_category" placeholder="fast food" type="text" >
                            </div>
                            <div class="col-xs-2">
                                <label for="ex6">longitude</label>
                                <input class="form-control" name="new_s_longitude" id="new_s_longitude" placeholder="121.00028167648875" type="text" >
                            </div>
                            <div class="col-xs-2">
                                <label for="ex8">latitude</label>
                                <input class="form-control" name="new_s_latitude" id="new_s_latitude" placeholder="24.78472733371133" type="text" >
                            </div>
                        </div>
                    </div>

                    <label id="msg"></label>
                    <div class=" row" style=" margin-top: 25px;">
                        <div class=" col-xs-3">
                            <input type="button" id="newshop" onclick="create_shop();" class="btn btn-primary" value="register"/>
                        </div>
                    </div>
                </form>

                <div id="shopresults">
                    <!-- 填入表單內容會秀在這 -->
                </div>

                <script>
                    function create_shop() {
                        var shop_name = $("#new_shop_name").val();
                        var shop_category = $("#new_shop_category").val();
                        var latitude = $("#new_s_latitude").val();
                        var longitude = $("#new_s_longitude").val();
                        <?php echo "var uid ='$user->uid';"; ?>
                        $.post("create_shop.php", { shop_name: shop_name, shop_category: shop_category, latitude: latitude, longitude: longitude, uid: uid},
                            function(data) {
                                //$('#shopresults').html(data);
                                $('#form2')[0].reset();
                                alert(data);
                                window.location.href='home.php';
                            });
                    }
                </script>

                <hr>

                <h3>ADD</h3>
                <form id="form3" method="post" enctype="multipart/form-data" action="addmeal.php">
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="ex3">meal name</label>
                                <input class="form-control" name="meal_name" id="new_meal_name" type="text" required>
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px;">
                            <div class="col-xs-3">
                                <label for="ex7">price</label>
                                <input class="form-control" name="meal_price" id="new_meal_price" type="text" required>
                            </div>
                            <div class="col-xs-3">
                                <label for="ex4">quantity</label>
                                <input class="form-control" name="meal_quantity" id="new_meal_quantity" type="text" required>
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 25px;">
                            <div class=" col-xs-3">
                                <label for="ex12">上傳圖片</label>
                                <input id="new_meal_image" type="file" name="meal_image" class="file-loading" required>
                            </div>
                            <div class=" col-xs-3">
                                <input style=" margin-top: 15px;" type="submit" id="m_add" class="btn btn-primary" onclick="add_meal();" value="Add" disabled/>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="  col-xs-8">
                        <table class="table" style=" margin-top: 15px;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Picture</th>
                                    <th scope="col">meal name</th>
                                
                                    <th scope="col">price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($shop->ifshopexist){
                                        $servername = "localhost";
                                        $dbname = "shop";
                                        $username = "root";
                                        $password = "";

                                        $db_link = @mysqli_connect($servername, $username, $password, $dbname);
                                        $sql_query = "SELECT * FROM meal where sid=".$shop->sid." ORDER BY mid ASC";
                                        $result = mysqli_query($db_link,$sql_query);
                                        if(!$result){
                                            echo "Error: ";
                                        }

                                        $total_records = mysqli_num_rows($result);
                                        $row_index=1;

                                        while($row_result = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<th scope="."row".">".$row_index."</th>";
                                            $img=$row_result["img"];
                                            $logodata = $img;
                                            $width='100';
                                            $height='100';
                                            echo "<td>".'<img src="data:'.$row_result['imgType'].';base64,' . $logodata . '"'.'style = "width:'.$width.'px; height:'.$height.'px;" />'."</td>";
                                            echo "<td>".$row_result['name']."</td>";
                                            echo "<td>".$row_result['price']."</td>";
                                            echo "<td>".$row_result['quantity']."</td>";
                                            echo "<td><a href='update_meal.php?id=".$row_result['mid']."'>修改</a></td>";
                                            //onclick="return  confirm('do you want to delete it?')"
                                            echo "<td><a href='delete_meal.php?id=".$row_result['mid']."' onclick=".'"return  confirm('."'Do you want to delete it?"."')".'"'.">刪除</a></td>";
                                            echo "</tr>";
                                            $row_index=$row_index+1;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- 
			menu2
			-->
			<div id="menu2" class="tab-pane fade">
				<div class="form-group">
				<label class="control-label col-sm-1" for="o_sort">Status</label>
				<div class="col-sm-3">
					<select class="form-control" name="o_sort" id="o_sort">
						<option value="All">All</option>
						<option value="Finished">Finished</option>
						<option value="Not Finish">Not Finish</option>
						<option value="Cancel">Cancel</option>
					</select>
				</div>

            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            $(".nav-tabs a").click(function () {
                $(this).tab('show');
            });
        });
    </script>                  
    
</body>

</html>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>