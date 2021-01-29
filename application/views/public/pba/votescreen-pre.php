<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="keyword" content="">
  <link rel="shortcut icon" href="../img/favicon.png">
  
  
   <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo base_url();?>assetsAdmin/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/table/candidates.css">
<!--===============================================================================================-->
  
  
  
  
  
  
  <title>Vote Screen</title>

 <?php include("header-css.php");?>
<style>
	.wait,.wait h1 .fa{
		font-size:50px!important;
		text-align:center;
		margin-top:150px;
	}
</style>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

  <div class="wait">
	<h1><i class="fa fa-spinner fa-spin"></i></h1>
	Please Wait......
</div>

	
   <!----- BOTTON JS FILES --->
   <?php include("bottom-js.php");?>
	<script>
	setTimeout(function(){ window.location.reload(); },2090);
	</script>
   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/public/candidates.js"></script>
   

</body>
</html>