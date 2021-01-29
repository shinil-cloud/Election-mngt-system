
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='<?php echo base_url();?>assetsAdmin\css\SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
<div class="sidebar">
      <nav class="sidebar-nav">
        <ul class="nav">
		<?php if($_SESSION['ADMIN_USER_TYPE']!="USER"):?>
		 <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/adminHome"><i class="fa fa-fw fa-home"></i> Dashboard</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/usersList"><i class="fa fa-fw fa-user"></i> Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/boothsList"><i class="fa fa-server"></i> Manage Booths</a>
          </li>
		 
            <a class="nav-link" href="<?php echo base_url();?>admin/candidatesList"><i class="fas fa-user-tie"></i> Manage Candidates</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/votersList"><i class="fa fa-fw fa-users"></i> Manage Voters</a>
          </li>
		  
		  
		 <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/staff"><i class="fas fa-user-shield"></i> Manage Staff</a>
          </li> 
		  
		  
		  
		  
		  
		  
		  <li class="nav-item"> 
            <a class="nav-link" href="<?php echo base_url();?>admin/schedule"><i class="fa fa-clock-o"></i> Set Schedule</a>
          </li>
		<?php endif;?> 
		  <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/changePass"> Change Your Password</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url();?>admin/adminLogOut"> Logout</a>
          </li>
		</ul>
	  </nav>
	</div>
	