
<?php
	session_start();
	if(isset($_SESSION['Authenticated'])){
		if($_SESSION['Authenticated']==true){
			header('Location: home.php');
		}
	}
	
	
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sign up</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Free HTML5 Template by FreeHTML5.co" />
	<meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
	<meta name="author" content="FreeHTML5.co" />

  <!-- 
	//////////////////////////////////////////////////////

	FREE HTML5 TEMPLATE 
	DESIGNED & DEVELOPED by FreeHTML5.co
		
	Website: 		http://freehtml5.co/
	Email: 			info@freehtml5.co
	Twitter: 		http://twitter.com/fh5co
	Facebook: 		https://www.facebook.com/fh5co

	//////////////////////////////////////////////////////
	 -->

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">


	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
		<script>
		function check_name(Account){
			if(Account!=""){
				document.getElementById("msg").removeAttribute("hidden");
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					var message;
					var re = new RegExp("^[a-zA-Z0-9]*$");
					if(this.readyState == 4 && this.status == 200){
						switch(this.responseText){
							case 'YES':
								message='The Account is not registered.';
								break;
							case 'NO':
								message='The Account is registered.';
								break;
							default:
								message='Oops. There is something wrong';
								break;
						}
						if (!re.test(Account))
							message='Only alphabets or numbers are allowed.';
						document.getElementById("msg").innerHTML = message;
					}
				};
				xhttp.open("POST", "check_name.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("Account="+Account);
			}
			else
				document.getElementById("msg").setAttribute("hidden","hidden");
		}
		</script>
		
		<div class="container">

			<div class="row">
				<div class="col-md-4 col-md-offset-4">


					<!-- Start Sign In Form -->
					<form action="check_sign_up.php" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="post">
						<h2>Sign up</h2>
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="Name" autocomplete="off" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Account" oninput="check_name(this.value);" 
							placeholder="Account" autocomplete="off" required><label id="msg" hidden></label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="PhoneNumber" placeholder="PhoneNumber" autocomplete="off" required>
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="Password" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="retype_password" placeholder="Retype password" autocomplete="off" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="longitude" placeholder="Longitude" autocomplete="off" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="latitude" placeholder="Latitude" autocomplete="off" required>
						</div>
						<div class="form-group">
							<p>Have an account? <a href="index.php">Sign Up</a> </p>
						</div>
						<div class="form-group">
							<input type="submit" value="Sign In" class="btn btn-primary">
						</div>
					</form>
					<!-- END Sign In Form -->

				</div>
			</div>
			<div class="row" style="padding-top: 60px; clear: both;">
				<div class="col-md-12 text-center"><p><small>&copy; All Rights Reserved. Designed by <a href="https://freehtml5.co">FreeHTML5.co</a></small></p></div>
			</div>
		</div>
	
	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Placeholder -->
	<script src="js/jquery.placeholder.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Main JS -->
	<script src="js/main.js"></script>




	</body>
</html>
