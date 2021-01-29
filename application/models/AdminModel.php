<?php
	class AdminModel extends CI_Model{

	 function __construct() {
        parent::__construct();
        
		$this->load->helper('common_functions_helper'); // for password encription/ verification
		$this->load->helper('send_email_helper');
        

        //session_start();
    }
	
	function adminLoginCheck($userId, $userPass)
	{
		$SQL  = "SELECT Id, UserId, `Password`,UserType, ProfilePhoto";
        $SQL .= "  FROM login WHERE UserId='$userId' AND Status='Active'  LIMIT 1";
	
        $data = array();
      
        $records = $this->db->query($SQL); // --- using db->query Method
        
        
        if($records->num_rows()<=0)
             return FALSE;
        
        else  // getting the password from table
        {
            foreach ($records->result() as $row)
                {
                    $logPass=$row->Password;
                    $data['USER_TYPE'] = $row->UserType;
                    
                    if(!verifyPassword($userPass, $logPass))
                        {
                            
                            return "FALSE";
                        }
                   else
                       
                   {  
                            $_SESSION['ADMIN_ID'] =$userId;
                            $_SESSION['ADMIN_PASS'] =$userPass;
                            $_SESSION['ADMIN_USER_TYPE'] =$row->UserType;
                            $_SESSION['ADMIN_LOGIN_ID'] =$row->Id;
							$_SESSION['PROFILE_PHOTO'] = $row->ProfilePhoto;
                            $loginIP    = getRequestIPAddress();
                            $Id     = $row->Id;
                            
							
							 //------------------ updating the Last Login IP address login table
                            $today = date('Y-m-d');
							$logData= array (
								'LastLoginIP' => $loginIP,
								'LoginStatus' => '1',
								'LastLoginDate'=>date("Y-m-d H:i:s")	
                            );
							
							$this->db->where('Id',$Id);
                            $this->db->update('login',$logData);
                           
			         
							return "TRUE";
                   }     
                }
            
            
        }
			
			
		
		
	}  // adminLoginCheck ending
	
	
	function changePass($userId, $oldPassword, $newPassword){
		$password	= encriptPassword($oldPassword);
		$SQL		= "SELECT Id FROM login WHERE UserId='{$userId}' AND Password='{$password}' LIMIT 1";
		$res		= $this->db->query($SQL);
		$row		= $res->row();
		
		if(!isset($row))
		{
			return FALSE;
			
		}
        else  // getting the password from table
        {
			$password	= encriptPassword($newPassword);
			$id			= $row->Id;
			$SQL		= "UPDATE login SET Password='{$password}'";
			$this->db->query($SQL);
			return TRUE;
		}
		
	}
	
	
	
} // CLASS ENDING

?>