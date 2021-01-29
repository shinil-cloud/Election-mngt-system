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
  <title>Admin Dashboard : Add Fingerprint data</title>
 <?php include("header-css.php");?>

</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
  <?php include("header.php");?>

  <div class="app-body">
    
	<!--- Navigation Menu Start -->
	<?php include("nav-bar.php");?>
	<!--- Navigation Menu End -->
	<main class="main"">
	<table width="100%">
        <tr>
            <td colspan="3" align="center" style="padding:20px;color: #428BCA; font-size: 18px!important; font-weight:bold;">
               BIO-METRIC REGISTRATION
            </td>
        </tr>

        <tr>
            <td colspan="3" align="center" style="color: red; font-size: 14px;">
                Please check that your bowser is asking for enable script or not. If yes then enable it.
                <!--<br />-->
                If you are using Internet Explorer then it will ask for "Allow Blocked Content".
                <br />
                First call may take some time, so wait after click any button
            </td>
        </tr>

        <tr>
            <td width="200px;">
                <table align="left" border="0" width="100%">
                    <tr>
                       <!-- <td>
                            <input type="submit" id="btnInfo" value="Get Info" class="btn btn-primary btn-100" onclick="return GetInfo()" />
                        </td> -->
						<?php for($i=1;$i<=4;$i++) 
						{
							$imgid='imgFinger'.$i;
							echo  '<td  width="150px" height="190px" align="center" class="img">
							<img id="'.$imgid.'" width="145px" height="188px" alt="Finger Image" />
						</td>';
							
						}?>
                    </tr>
					<tr>
                    <td class="text-center">
                            <input type="submit" id="btnCapture" value="Capture left thumb" class="btn btn-primary btn-100" onclick="myCapture(1);" />
                    </td>
                        <td class="text-center">
                            <input type="submit" id="btnCapture" value="Capture left index" class="btn btn-primary btn-100" onclick="myCapture(2);" />
                        </td>
						<td class="text-center">
                            <input type="submit" id="btnCapture" value="Capture right thumb" class="btn btn-primary btn-100" onclick="myCapture(3);" />
                        </td>
						<td class="text-center">
                            <input type="submit" id="btnCapture" value="Capture right index" class="btn btn-primary btn-100" onclick="myCapture(4);" />
                        </td>
                    </tr>
					
 
                </table>
            </td>
           
            
        </tr>
    </table>
    <div class="panel">
       
	</main>
</div>
				
            
 <?php include("footer.php");?>
	
   <!----- BOTTON JS FILES --->
   <?php include("bottom-js.php");?>
   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/fingerprint/jquery-1.8.2.js"></script>
   <script type="text/javascript" src="<?php echo base_url();?>assetsAdmin/js/admin/fingerprint/mfs100-9.0.2.6.js"></script>
   <script>
		function myCapture(id){
			var res=CaptureFinger(80,10);
			if (res.httpStaus) {
				if (res.data.ErrorCode == "0") {
					document.getElementById('imgFinger'+id).src = "data:image/bmp;base64," + res.data.BitmapData;
					addFingerToDb(id,res.data.IsoTemplate);
				}
			}
			else {
				alert(res.err);
			}
		}
		function addFingerToDb(fingerid,fingerdata)
		{
			$.ajax({
				url:"<?php echo base_url("admin/addFingerToDb");?>",
				data:{Finger:fingerid,Data:fingerdata,Id:"<?php echo $Id;?>",Type:"<?php echo $Type;?>"},
				type:"post",
				success:function(res){
					bootbox.alert(res);
				},
				error:function(){
					bootbox.alert("Error Saving Finger Please Try Again...");
				}
			})
		}
   </script>
</body>
</html>