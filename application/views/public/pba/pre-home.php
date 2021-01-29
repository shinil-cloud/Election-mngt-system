<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome</title>
	<?php include("header-css.php");?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo base_url();?>img/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/login/css/main.css">
<!--===============================================================================================-->
</head>
<body class="back-img">
<header class="app-header navbar">
  <!--<a class="navbar-brand" href="<?php// echo base_url();?>admin/adminHome"></a>-->
	<label class="heading" style="font-family: 'poppins-bold';"> ONLINE ELECTION MANAGEMENT SYSTEM</label>
</header>

	<div class="container" id="wrapper"> 
		<div class="row" style="margin-top:10%;">
			<div class="col-md-6">
				<a href="<?php echo base_url("pba/voteScreen");?>">
					<div id="left" class="js-tilt text-center" data-tilt>
						  <p class="btnContent">DEDICATE FOR VOTE SCREEN</p>
						  <i class="fas fa-fingerprint" style="font-size:120px!important;color:#fff;"></i>
					</div>
				</a>
			</div>
			<div class="col-md-6">
				<a href="<?php echo base_url("pba/adminHome");?>">
					<div id="right" class="js-tilt text-center" data-tilt>
						<p class="btnContent">PROCEED TO DASHBOARD</p> 
						<i class="fa fa-dashboard" style="font-size:120px!important;color:#fff;"></i>
					</div>
				</a>
			</div>
        </div> 
	</div>
					<?php require_once('common-hid-fields.php');?>
<footer class="footer">
	 <span>Online Voting System</a> © <?php echo date("Y");?> S8 CSE MIT</span>
</footer> 
</body>

<!--===============================================================================================-->	
	<script src="<?php echo base_url();?>assetsAdmin/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assetsAdmin/login/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url();?>assetsAdmin/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assetsAdmin/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assetsAdmin/login/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.05
		})
	</script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assetsAdmin/login/js/main.js"></script>


<input type="hidden" id="hidID" name="hidID" value="0" class="optional" />
<input type="hidden" id="hidBASE_URL" name="hidBASE_URL" value="<?php echo base_url();?>" class="optional" />
<input type="hidden" id="hidAdminController" name="hidAdminController" value="<?php echo $adminController;?>" class="optional" />

   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/fingerprint/mfs100-9.0.2.6.js"></script>

  <!-- Bootstrap and necessary plugins -->
  <?php include "bottom-js.php";?>

  
  <style>
  .back-img{
	  background-image:url(<?php echo base_url();?>img/vote.jpg);
	  position:relative;
  }
  
  .footer{
	position:fixed;
  left: 0;
  bottom: 0;
  padding:10px;
  width: 100%;
  background-color: #1c1531;
  color: white;
  text-align: center;
  }
  
  
  .icon-bar {
  width: 50px; /* Set a specific width */
  background-color: #1e78bb; /* Dark-grey background */
  position:fixed;
  left:0px;
  top:0px;
  z-index:1;
}

.icon-bar a {
  display: block; /* Make the links appear below each other instead of side-by-side */
  text-align: center; /* Center-align text */
  padding: 16px; /* Add some padding */
  transition: all 0.3s ease; /* Add transition for hover effects */
  color: white; /* White text color */
  font-size: 36px; /* Increased font-size */
}

.icon-bar a:hover {
  background-color: #000; /* Add a hover color */
}

.active {
  background-color: #4CAF50; /* Add an active/current color */
}
#wrapper {
  display: flex;
  padding:5%;
	position:center;
}

#left {
  flex: 0 0 50%;
  background-color: blue;
  height:300px;
  margin-right:50px;
  border-style:solid;
  border-width:10px;
  border-color:#1c1531;
}

#right {
  flex:  0 0 50%;
  background-color:red;
  height:300px;
  margin-left:50px;
  border-style:solid;
  border-width:10px;
  border-color:#1c1531;
}
.btnContent{
    color: white;
    font-size: 30px!important;

    text-align:center;
    font-family:'poppins-bold';
	padding-bottom:20px;
}
</style>

</html>