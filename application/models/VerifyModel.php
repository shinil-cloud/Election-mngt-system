<?php
	class VerifyModel extends CI_Model{
var $table = 'voter';
	
	 function __construct() {
        parent::__construct();
        
		$this->load->helper('common_functions_helper'); // for password encription/ verification
		$this->load->helper('send_email_helper');
        

        //session_start();
    }
	
	function voterProceedCheck($voterId, $aadharId)
	{
		$SQL  = "SELECT Id, AadharId, VoterName, VoterId, Address";
        $SQL .= "  FROM voter WHERE VoterId='$voterId' AND AadharId='$aadharId'  LIMIT 1";
	
        $data = array();
      
        $records = $this->db->query($SQL); // --- using db->query Method
        
        
        if($records->num_rows()<=0)
             return FALSE;
        
        else  // getting the password from table
        {
            foreach ($records->result() as $row)
                {
                          //  $_SESSION['VOTER_ID'] =$voterId;
                            //$_SESSION['AADHAR_ID'] =$aadharId;
                        //    $_SESSION['VOTER_NAME'] =$row->VoterName;
                           // $_SESSION['ADMIN_LOGIN_ID'] =$row->Id;
							//$_SESSION['PROFILE_PHOTO'] = $row->ProfilePhoto;
                         //   $loginIP    = getRequestIPAddress();
                            $id     = $row->Id;
                            
							/*
							 //------------------ updating the Last Login IP address login table
                            $today = date('Y-m-d');
							$logData= array (
								'LastLoginIP' => $loginIP,
								'LoginStatus' => '1',
								'LastLoginDate'=>date("Y-m-d H:i:s")	
                            );
							
							$this->db->where('Id',$Id);
                            $this->db->update('login',$logData);
                           */
			         
							return "TRUE";
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
	
		public function getVoterId($id)
	{
		$this->db->from($this->table);
		$this->db->where('Id',$id);
		$query = $this->db->get();

		return $query->row();
	}
	
} // CLASS ENDING

?>