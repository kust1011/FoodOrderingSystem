<!DOCTYPE html>
<html>

<head>
<script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="style.css" />
<script src="js/botton.js"></script>
</head>

<body>
<form action="test_post.php" method="post">
Name: <input type="text" name="id[aaa]" value="a"/></br>
Name: <input type="text" name="id[ddd]" value="b"/></br>
Name: <input type="text" name="id[ddw]" value="c"/></br>
Name: <input type="text" name="id[wad]" value="d"/></br>
Name: <input type="text" name="id[wa]" value="e"/></br>
<input type="submit" value="送出表單"/>
</form>

<button type="button" class="btn btn-info" data-toggle="modal" data-target="#order" onclick="cal_order_'.$shop_value.'();">Calculate the price</button>

<div class="modal fade" id="order"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
</div>
</div>
</div>
                                    
</body>


</html>