<!-- This hidden fieds are common for all the admin  view page -->
<?php 
		$ci = get_instance(); // CI_Loader instance
         $ci->load->config('config');
         $adminController = $ci->config->item('admin_controller');

?>

<input type="hidden" id="hidID" name="hidID" value="0" class="optional" />
<input type="hidden" id="hidBASE_URL" name="hidBASE_URL" value="<?php echo base_url();?>" class="optional" />
<input type="hidden" id="hidAdminController" name="hidAdminController" value="<?php echo $adminController;?>" class="optional" />

<!-- for error and message -->

<div class="alert alert-danger alert-dismissable divError" style="margin-top:5px; text-align:left; display:none; color:red!important;" id="divError" name="divError"></div>
<div class="alert alert-success divMessage" style="margin-top:5px; text-align:left; display:none; color:green!important;" id="divMessage" name="divMessage"></div>	