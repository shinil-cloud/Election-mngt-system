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
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


  <title>Polling Booth Login</title>
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
              <p class="text-muted">Sign In to Polling Booth</p>
			  
			  <table width="100%" style="padding-top:0px;">
			  <tr>
				<td colspan="2">
			  <div class="input-group" style="margin-bottom:1rem;">
                <span class="input-group-addon"><i class="fas fa-poll-h"></i></span>
                <input type="text" id="boothId"  name="boothId" class="form-control" placeholder="Booth ID">
              </div>
				</td>
			 </tr>
			 <tr>
				<td colspan="2">
              <div class="input-group" style="margin-bottom:1rem;">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" id="uId"  name="uId" class="form-control" placeholder="Staff ID">
              </div>
			  </td>
			 </tr>
			 <tr>
				<td colspan="2">
              <div class="input-group" style="margin-bottom:1rem;">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" id="password"  name="password" class="form-control" placeholder="Password">
              </div>
			  </td>
			  </tr>
			  <tr>
					
				<td>
					<button type="button" style="width:90%;margin-left:0px;" class="btn btn-primary" id="btnOtp">Get OTP</button>
					</td>
					<td width="60%">	
					 <div class="input-group">
                <span class="input-group-addon"><i class="fas fa-key"></i></span>
					<input type="text" id="otp" name="otp" class="form-control" placeholder="OTP Code">
					</div>
					</td>
				</tr>
				<tr>
				</tr>
				<tr>
					<td colspan="2">
					<button type="button" style="height:100%;width:100%;margin-top:1.5rem;margin-left:0px;" class="btn btn-primary" id="btnLogin">Login</button>
					<td>
					</tr>
					 </div>
				<tr>
					<td>
						<div class="alert alert-danger divError" style="display:none;" id="divError"></div>
					</td>
				</tr>
			</table>
			 <!--
			  <div class="row">
                <div class="col-6">
                  <button type="button" class="btn btn-primary px-4" id="btnOtp">Send OTP</button>
                </div>	
				 
              <div class="col-6">
                <input type="text" id="otp"  name="otp" class="form-control" placeholder="OTP Code">
              </div>
			  </div>
			  
              <div class="row">
                <div class="col-6">
                  <button type="button" class="btn btn-primary px-4" id="btnLogin">Login</button>
                </div>
               <div class="col-6 text-right">
                  <button type="button" id="btnForgotPass" class="btn btn-link px-0">Forgot password?</button>
                </div>-->
             
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

   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/fingerprint/mfs100-9.0.2.6.js"></script>

  <!-- Bootstrap and necessary plugins -->
  <?php include "bottom-js.php";?>
  

  
  <script>
  $('#btnOtp').click(function(){
	// if(!$('#boothId').val()||!$('#uId').val()||!$('#password').val())
	// {
		// bootbox.alert("Please fill all Fields...");
		// return;
	// }
	$.ajax({
		url:"<?php echo base_url("pba/sendBoothOTP")?>",
		data:{Id:$('#boothId').val(),StaffId:$('#uId').val(),Password:$('#password').val()},
		type:"post",
		success:function(res){
			bootbox.alert(res);
		},
		error:function(){
			bootbox.alert("Netwrk Error....");
		}
	});
  });
  $('#btnLogin').click(function(){
	if(!$('#otp').val()||!$('#boothId').val()||!$('#uId').val()||!$('#password').val())
	{
		bootbox.alert("Please Fill OTP");
		return;
	}
	$.ajax({
		url:"<?php echo base_url("pba/checkOTP")?>",
		data:{OTP:$('#otp').val(),StaffId:$('#uId').val()},
		type:"post",
		dataType:"json",
		success:function(res){
			if(!res.status)
			{
				bootbox.alert(res.msg);
				return;
			}
			bootbox.dialog({ 
				message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Scan Your Finger...</div>', 
				closeButton: false ,
				size: 'large',
				onEscape: false,
				backdrop: false
			});
			scanLoginFinger(res.fingers);
			
		},
		error:function(){
			bootbox.alert("Netwrk Error....");
		}
	});
  });
  
  
  function scanLoginFinger(fingers){
	  	var match=false,
			scanned="";
		var res=CaptureFinger(80,10);
		if (res.httpStaus) {
			if (res.data.ErrorCode == "0") 
				scanned=res.data.IsoTemplate;
		}
		else 
		{
			bootbox.alert(res.err);
			return;
		}
		$.each(fingers,function(key,value){
			var res = VerifyFinger(value, scanned);
			
			if (res.httpStaus) {
				if (res.data.Status) {
					match=true;
					return;
				}
			}
		});
		if(!match)
		{
			bootbox.alert("Finger Mismatch Try Again...",function(){
				scanLoginFinger(fingers);
			});
			return;
		}
		$.ajax({
			url:"<?php echo base_url("pba/setLogin")?>",
			type:"post",
			data:{Id1:$('#boothId').val(),Id2:$('#uId').val()},
			success:function(res){
				location.replace(res);
			}
		})
  }
  </script>
</body>
</html>