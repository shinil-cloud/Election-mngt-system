<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome</title>
	<?php include("header-css.php");?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo base_url();?>assetsAdmin/login/images/icons/favicon.ico"/>
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
<body>

	<div class="limiter">
    <div id="wrapper" >
        <div id="left" class="js-tilt" data-tilt>
              <p class="btnContent">DEDICATE FOR VOTE SCREEN</p>
        </div>
        <div id="right" class="js-tilt" data-tilt>
			</div>
        </div>
    </div>  
					<?php require_once('common-hid-fields.php');?>
	</div>
	 
 
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
			scale: 1.1
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
}

#left {
  flex: 0 0 50%;
  background-color: blue;
  height:300px;
  margin-right:50px;
}

#right {
  flex:  0 0 50%;
  background-color:red;
  height:300px;
  margin-left:50px;
}
.btnContent{
    color: white;
    font-size: 100px;

    text-align:center;
    font-family:'poppins-bold'
}
</style>
 
</body>
</html>