<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="keyword" content="">
  <link rel="shortcut icon" href="../img/favicon.ico">
  <title>Admin Dashboard : Change Password</title>

 <?php include("header-css.php");?>

</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
  <?php include("header.php");?>

  <div class="app-body">
    
	<!--- Navigation Menu Start -->
	<?php include("nav-bar.php");?>
	<!--- Navigation Menu End -->
	<main class="main">
	<div class="row">
            <div class="col-md-12">
				<div class="ab"><p class="font1" align="left"><font size="300px">
                 Change Password</font></p>
                </div>
				<div class="offset-2 card-body col-md-8">
					
					

                <form action="" id="changeform" method="post" class="form-horizontal">
						<div class="form-group row col-md-offset-1">
							<label class="col-md-4 col-form-label text-right" for="UserId" >User Id</label>
							<div class="col-md-6">
								<input type="text" id="UserId" name="UserId" class="form-control" placeholder="Please Enter Your User Id" required type="text">
							</div>
						</div>	
						<div class="form-group row col-md-offset-1">
							<label class="col-md-4 col-form-label text-right" for="OldPass" >Current Password</label>
							<div class="col-md-6">
								<input type="Password" id="OldPass" name="OldPass" class="form-control" placeholder="Please Enter Your Current Password" required type="text">
							</div>
						</div>							
						<div class="form-group row col-md-offset-1">
							<label class="col-md-4 col-form-label text-right" for="NewPass" >New Password</label>
							<div class="col-md-6">
								<input type="Password" id="NewPass" name="NewPass" class="form-control" placeholder="Please Enter Your New Password" required type="text">
							</div>
						</div>
						<div class="form-group row col-md-offset-1">
							<label class="col-md-4 col-form-label text-right" for="ConfPass" >Confirm Password</label>
							<div class="col-md-6">
								<input type="Password" id="ConfPass" name="ConfPass" class="form-control" placeholder="Please Re-Enter Your New Password" required type="text">
							</div>
						</div>
						<?php require_once('common-hid-fields.php');?>
					
                
                </form>
					</div>
						<div class="form-group text-center col-md-10">
							<button id="btnChangePass" class="btn btn-success"><i class="glyphicon glyphicon-lock"></i><i class="glyphicon glyphicon-refresh"></i> Change Password</button>
						</div>
					
					</div>
			 </div>
			</div>
	</div>

	</main>
</div>
				
            
 <?php include("footer.php");?>
	
   <!----- BOTTON JS FILES --->
   <?php include("bottom-js.php");?>
   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/adminlogin.js"></script>
   
</body>
</html>