<!DOCTYPE html>
<html>

  <head>
    <script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css" />
    <script src="js/botton.js"></script>

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
<script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="style.css" />
<script src="js/botton.js"></script>
  </head>

  <body>
  
	<?php
	include("test_post2.php"); 
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
	$value = $row
	?>

	<tr class="success">
		<td>
				<?php printf($value["mid"])?>
		</td>
		<td>
				<?php printf($value["name"]) ?>
		</td>
		<td>
				<?php printf($value["price"]) ?>
		</td>
		<td>
				<input type="button" name="button_de" value="刪除">
		</td>
	</tr>
	<?php } ?> 


	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#macdonald">Calculate the price</button>

<div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
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
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Order check</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
                                            <td>Hamburger</td>
                                            <td>80 </td>
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
    <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
  </div>
</div> 
            </div>    
        </div>
  </body>
  

</html>

