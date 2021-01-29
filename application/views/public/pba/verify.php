<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Łukasz Holeczek">
  <meta name="keyword" content="">
  <link rel="shortcut icon" href="../img/favicon.ico">
  
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/UI/bootstrap.css">
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/UI/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assetsAdmin/css/public/UI/animate.css">




			
  <title>Verification </title>

  <?php include("header-css.php");?>
 
  <?php require_once('common-hid-fields.php');?>
</head>


<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
 <?php include("header.php");?>
	


  <div class="app-body">
    
	
	
	
		<!--- Navigation Menu Start -->
		 <?php include("nav-bar.php");?>
		<!--- Navigation Menu End -->
	

    <!-- Main content -->
    <main class="main"> 
	
	  <!--
	  <div class="row">
            <div class="col-md-12">
              <div class="card">
				
				Election Management System
			  </div>
			</div>
	  </div>
	  -->
<div class="ab"><p class="font1" align="left"><font size="300px">Verification</font></p>
</div>


	
	
	
	<div class="container emp-profile" width="100%" height="100%">
            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                           <img style="border-radius:50%;" src="<?php echo base_url($user['Photo']);?>" alt=""/>
                            
							
						
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                                    <h6>
                                      Name : <b> <?php echo $user['VoterName']; ?></b>
                                    </h6>
                                    <h6>
                                        Date of Birth :  <?php echo $user['DOB']; ?>
                                    </h6>
                                    <p class="proile-rating">Eligibility : <span> <?php echo $user['Eligibility']; ?></span></p>
                   
                        </div>
                    </div>
               
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div>
                            <div id="home" role="tabpanel" aria-labelledby="home-tab">
    <table>
		<tr><td>
         <div class="col-md-6">
            <label>Voter ID</label>
         </div>
		 </td>
		 <td>
		<div class="col-md-6">
            <p> <?php echo $user['VoterId']; ?></p>
        </div>
		</td>
		</tr>
	<tr><td>
         <div class="col-md-6">
            <label>Aadhar ID</label>
         </div>
		 </td>
		 <td>
		<div class="col-md-6">
            <p> <?php echo $user['AadharId']; ?></p>
        </div>
		</td>
   </tr>
   <!--
                                        <div class="row">
                                            <div class="col-md-6" >
                                                <label>Phone</label>
                                            </div>
                                            <div class="col-md-6" >
                                                <p>123 456 7890</p>
                                            </div>
                                        </div>-->
                                        <tr>
											<td>
                                            <div class="col-md-6" >
                                                <label>Address</label>
                                            </div>
											</td>
											<td>
                                            <div class="col-md-6" >
                                                <p> <?php echo $user['Address']; ?></p>
                                            </div>
											</td>
											</tr>
										<?php if($user['Eligibility']!="Revoked" && $user['VoteStatus'] != "Voted"){?>
											<tr>
											<td>
                                            <div class="col-md-6" id="fingerDiv">
                                                <Button type="button" class="btn btn-fpscan" id="btnFinger"><i class="fas fa-fingerprint" id="fpspin"></i>
												 Verify Finger
												</button>
                                            </div>
											<div class="col-md-6" id="prcd"></div>
											<td>
                                            </tr>
										<?php }?>
											</table>
										</div>
										</div>
									</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>           
        </div>		
 </main>
  </div>

   <?php include("footer.php");?>
   <!----- BOTTON JS FILES --->
   <?php include("bottom-js.php");?>
	<script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/public/homecandid.js"></script>
   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/fingerprint/mfs100-9.0.2.6.js"></script>
   <script>
	$('#btnFinger').click(function(){
		$('#fpspin').addClass('fa-spin');
		var match=false;
		scanned="";
		setTimeout(function(){
			var res=CaptureFinger(80,10);
			if (res.httpStaus) {
				if (res.data.ErrorCode == "0") 
					scanned=res.data.IsoTemplate;
			}
			else 
			{
				bootbox.alert(res.err);
				$('#fpspin').removeClass('fa-spin');
				return;
			}
				
			$.ajax({
				url:"<?php echo base_url("pba/getFingers/{$user['Id']}");?>",
				dataType:"json",
				success:function(fingers){
					$('#fpspin').removeClass('fa-spin');
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
						bootbox.alert("Finger Mismatch Try Again...");
						return;
					}
					//$('#prcd').html('<a href="<?php echo base_url()."pba/intoPolling/".$user['Id'];?>" class="btn btn-fpscan" ><i class="fa fa-check"></i> Proceed</a>');
					$('#prcd').html('<button type="button" class="btn btn-fpscan" ><i class="fa fa-check"></i> Proceed</button>');
					$('#btnFinger').remove();
				},
				error:function(){
					bootbox.alert("Network Error");
				}
			});
		},100);
		
	});
	
	
	$('#prcd').click(function(){
		$.ajax({
				url:"<?php echo base_url("pba/setPolling/{$user['Id']}");?>",
				dataType:"json",
				success:function(res){
					bootbox.alert(res.message);
						return;
				},
				error:function(){
					bootbox.alert("Session Initialization Failed");
					return;
				}
			});
		
	});
	
   </script>
</body>
</html>