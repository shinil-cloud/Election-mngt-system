<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
  <meta name="author" content="Lukasz Holeczek">
  <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
  <!-- <link rel="shortcut icon" href="assetsAdmin/ico/favicon.png"> -->

  <title>Admin Login</title>
  <?php include("header-css.php");?>

</head>

<body class="app flex-row align-items-center" background="<?php echo base_url();?>img/vote.jpg">
  <div class="container">
  
    <div class="row justify-content-center">
      
	  <div class="col-md-4">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
			<form id="loginForm">
              <h1>Login</h1>
              <p class="text-muted">Sign In to your account</p>
              <div class="input-group mb-3">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" id="uId"  name="uId" class="form-control" placeholder="Username">
              </div>
              <div class="input-group mb-4">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" id="password"  name="password" class="form-control" placeholder="Password">
              </div>
              <div class="row">
                <div class="col-6">
                  <button type="button" class="btn btn-primary px-4" id="btnLogin">Login</button>
                </div>
                <div class="col-6 text-right">
                  <button type="button" id="btnForgotPass" class="btn btn-link px-0">Forgot password?</button>
                </div>
              </div>
			  <div class="alert alert-danger divError" style="display:none;" id="divError"></div>
			  </form>
            </div>
          </div>
        </div>
      </div>
	  
    </div>
  </div>
  <?php 
		$ci = get_instance(); // CI_Loader instance
         $ci->load->config('config');
         $adminController = $ci->config->item('admin_controller');

 ?>
 
 <!-- Bootstrap modal FOR ADDING AND EDITING -->
	<div class="card">
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><strong>Forgot Password</strong></h3>
				<small>* indicates required field.</small>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" value="" name="id" class="optional"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="UserId" class="control-label col-md-3">User Id*</label>
                            <div class="col-md-9">
                                <input name="UserId" id="UserId" placeholder="User Id" class="form-control" required type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Email" class="control-label col-md-3">Email*</label>
                            <div class="col-md-9">
                                <input name="Email" id="Email" placeholder="Email address" class="form-control" required type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        
                        
                        <!------ file upload BLOCK ends --->
             
					<?php require_once('common-hid-fields.php');?>
					</div>
                
                </form>
				
            </div>
            <div class="modal-footer">
                
                <button type="button" id="btnResetPass"  class="btn btn-primary">Send Reset Mail</button>
                <button type="button" class="btn btn-danger"  style="margin-top:0px !important;" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</div>
 
 
 
 
 

<input type="hidden" id="hidID" name="hidID" value="0" class="optional" />
<input type="hidden" id="hidBASE_URL" name="hidBASE_URL" value="<?php echo base_url();?>" class="optional" />
<input type="hidden" id="hidAdminController" name="hidAdminController" value="<?php echo $adminController;?>" class="optional" />


  <!-- Bootstrap and necessary plugins -->
  <?php include "bottom-js.php";?>
  

  <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/adminlogin.js"></script>
  
  
</body>
</html>