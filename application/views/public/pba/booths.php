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
<!--===============================================================================================-->
  
  
  
  <title>Admin Dashboard : Booths list</title>

 <?php include("header-css.php");?>

</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
  <?php include("header.php");?>

  <div class="app-body">
    
	<!--- Navigation Menu Start -->
	<?php include("nav-bar.php");?>
	<!--- Navigation Menu End -->
	
	
	<main class="main">
	<!--
	<div class="container-table100">
            <div class="col-md-12">
              <div class="card">
				<div class="card-header">
					Booths List
                </div>
				<div class="text-center">
					<button class="btn btn-success" onClick="addData();"><i class="glyphicon glyphicon-plus"></i> Add Booth</button>
				  
				</div>
                <div class="card-body">
				
				<table id="dataTable" class="table table-bordered table-hover table-striped">	
				<thead class="thead-light">
                      <tr class="table100-head">
					    <th class="column1">Booth ID</th>
                        <th class="column2">Admin Name</th>
						<th class="column3">Voters Count</th>
						<th class="column4">Created On</th>
						<th class="column5">Status</th>
						
					    <th class="column6">Action</th>
                      </tr>
                    </thead>
					<tbody>
                      </tbody>
					</table>
					</div>
					</div>
			  </div>
			</div>
		</div>
		-->
		
		<div class="limiter">
			<div class="ab"><p class="font1" align="left"><font size="300px">Booths list</font></p>
					
                </div>
				<div class="text-center">
					<button class="btn btn-success" onClick="addData();"><i class="glyphicon glyphicon-plus"></i> Add Booth</button>
				  
				</div>
	
				<div class="table100">
					<table id="dataTable">
						<thead>
							<tr class="table100-head">
								<th class="column1">Booth ID</th>
                        <th class="column2">Admin Name</th>
						<th class="column3">Voters Count</th>
						<th class="column4">Created On</th>
						<th class="column5">Status</th>
					    <th class="column6">Action</th>
							</tr>
						</thead>
		
					</table>
				</div>
			
	</div>
    </main>
</div>
	<!-- Bootstrap modal FOR ADDING AND EDITING -->
	<div class="card">
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><strong>Add User</strong></h3>
				<small>* indicates required field.</small>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" value="" name="id" class="optional"/>
                    <div class="form-body">
                        <!--
                        	<div class="form-group">
                            <label for="User_Group" class="control-label col-md-3">User Type/Group*</label>
                            <div class="col-md-9">
                                
                                
                            <select class="form-control required" required  id="User_Group" name="User_Group">
										<?php 
										/*
                                            $db = new Database();
                                            $resCombo = $db->fillCombo("sys_user_group", "UserGroup", "-Select-", "", "Id", "Status='1'", "UserGroup", "");
                                            echo $resCombo;*/
                                         ?>
							</select>
							<span class="help-block" style="display: none;">Please enter menu item.</span>
                            </div>
                        </div>
						-->
                        <div class="form-group">
                            <label for="Booth_Id" class="control-label col-md-3">Booth Id*</label>
                            <div class="col-md-9">
                                <input name="Booth_Id" id="Booth_Id" placeholder="Booth Id" class="form-control" required type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="Admin_Name" class="control-label col-md-3">Admin Name*</label>
                            <div class="col-md-9">
                                <input name="Admin_Name" id="Admin_Name" placeholder="Admin Name" class="form-control" required type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="Voters_Count" class="control-label col-md-3">Voters Count*</label>
                            <div class="col-md-9">
                                <input name="Voters_Count" id="Voters_Count" placeholder="Voters Count" class="form-control" required type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<!--
						<div class="form-group">
                            <label for="Email" class="control-label col-md-3">Email*</label>
                            <div class="col-md-9">
                                <input name="Email" id="Email" placeholder="Email address" class="form-control" required type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="Sex" class="control-label col-md-3">Sex*</label>
                            <div class="col-md-9">
                                <select name="Sex" id="Sex" class="form-control">
                                    <option value="">-Select-</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <span class="help-block"></span>
				
                            </div>
                        </div>
                        -->
                        
                        <div class="form-group">
                            <label for="Password" class="control-label col-md-3">Password*</label>
                            <div class="col-md-9">
                                <input id="Password" name="Password"  placeholder="Password" class="form-control required" required type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="Retype_Password" class="control-label col-md-3">Retype Password*</label>
                            <div class="col-md-9">
                                <input id="Retype_Password" name="Retype_Password"  placeholder="Retype Password" class="form-control required" required type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>
                      			
						<div class="form-group">
                            <label for="Status" class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <select name="Status" id="Status" class="form-control">
                                    <option value="Active" selected="selected">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="help-block"></span>
				
                            </div>
                        </div>
                        
						<!------ file upload block start
       
                        <div class="form-group" id="hideWhenEdit">
                            <label for="userfile" class="control-label col-md-3">Profile Image</label>
                            <div class="col-md-9">
                                <label class="btn btn-primary btn-file">
                                     Browse <input type="file"  accept="image/*" name="userfile" id="userfile" class="optional"
                                                   style='position:absolute;z-index:2;top:0;left:0;filter: 
                                                   alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                                                   opacity:0;background-color:transparent;color:transparent;'
                                                   onchange="$('#upload-file-info').html($(this).val());">
                                     
                                </label>
                                <span class='label label-info' id="upload-file-info"></span>
                                
								<span class="help-block"></span>
				
                            </div>
                        </div>
                        file upload BLOCK ends --->
					<?php require_once('common-hid-fields.php');?>
					</div>
                
                </form>
				
            </div>
            <div class="modal-footer">
                
                <button type="button" id="btnSave"  class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger"  style="margin-top:0px !important;" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</div>

	
	

   <?php include("footer.php");?>
	
   <!----- BOTTON JS FILES --->
   <?php include("bottom-js.php");?>

   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/booths.js"></script>
   
   
</body>
</html>