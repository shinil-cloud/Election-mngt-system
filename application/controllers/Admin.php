<?php

// This controller is going to be the client side controller.

defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {


 function __construct() {
        parent::__construct();
        
		$this->load->helper('common_functions_helper'); // for password encription/ verification
		$this->load->helper('send_email_helper');
		$this->load->helper('dbclass');
				$this->load->helper('sms');
        
        session_start();
		$this->load->model("AdminModel");
		$this->load->model("UserModel");
		$this->load->model("BoothModel");
		$this->load->model("CandidateModel");
		$this->load->model("VoterModel");
		$this->load->model("ScheduleModel");
		$this->load->model("StaffModel");
    }
	
	public function test()
	{
		echo encriptPassword('Password');
		
	}
	
	
	
	
	private function redirect()
    {
         if (!$this->input->is_ajax_request()) 
           exit("Access denied");
    }
	
   private function isSessionSet()
   {
	if(!isset($_SESSION['ADMIN_ID']) && !isset($_SESSION['ADMIN_USER_TYPE']) && !isset($_SESSION['ADMIN_LOGIN_ID']))
	   return FALSE;
	else
	    return TRUE;	
   }
	
	
	
	public function index()
	{
		$this->load->view('admin/login');
	}
	
	
	public function adminHome()
	{
	
		
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}
  		$data = array();
		$db= new Database();
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data['count']=$this->db->get_where("voter","Eligibility = 'Eligible'")->num_rows();
		$data['poll']=$this->db->get_where("polling","Status = 'Inactive'")->num_rows();
		$data['electionTitle']=$this->db->order_by("Id","DESC")->select('ElectionName')->get('voting_config')->row('ElectionName');
		$this->load->view('admin/index', $data);
	}
	
	public function usersList()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		$data = array();
		$db= new Database();
		$data["DeptCount"]  = $db->totalCount("Id", "department");
		$data["PostCount"]  = $db->totalCount("Id", "post");
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data["VoterCount"]  = $db->totalCount("Id", "voters");
		$data["CandidCount"]  = $db->totalCount("Id", "candidate_list");
		$data["InvigilatorCount"]  = $db->totalCount("Id", "invigilator");
		
		
		$this->load->view('admin/users', $data);
	}
	
	public function changePass()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		$data = array();
		$db= new Database();
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data["InvigilatorCount"]  = $db->totalCount("Id", "invigilator");
		
		
		$this->load->view('admin/changepassword', $data);
	}

	
	 public function adminLoginCheck()
	 {
		 $userId = $this->input->post("uId");
		 $password = $this->input->post("password");
		 
		 $res = $this->AdminModel->adminLoginCheck($userId, $password);
		 echo $res;
		 
	 }
	 
	 public function changeAdminPassword()
	 {
		 $userId		= $this->input->post("UserId");
		 $oldPassword	= $this->input->post("OldPass");
		 $newPassword	= $this->input->post("NewPass");
		 
		 $res = $this->AdminModel->changePass($userId, $oldPassword, $newPassword);
		 
		 $response = array();
		 if($res)
		 {
			 
		 	 $response["status"] = true;
			 $response["msg"]  = "Password has been successfully changed!";
			
			 
		 }
		 else
		 {
			 
		 	 $response["status"] = false;
			 $response["msg"]  = "Error!!! Invalid User Id or current password";
			 
			 
		 }
		  echo json_encode($response);
		 
		 
	 }
	 
	 public function adminLogOut()
	 {
		 
		 if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}
		
		
		// updating the login status
		$logData= array (
								'LoginStatus' => '0'
							);
		
		$this->db->where('Id',$_SESSION['ADMIN_LOGIN_ID']);
        $this->db->update('login',$logData);	
		session_destroy(); 	
		
		redirect(base_url());
		
		
		
		 
	 }
	 
	 public function saveUser()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $userId    	= $this->input->post("User_Id"); 
		 
		 $db	  		= new Database();	
		 $where   		="UserId='{$userId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("login", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		 

		 $password      = encriptPassword($this->input->post("Password")); 
		 $sex    		= $this->input->post("Sex"); 
		 $status    	= $this->input->post("Status"); 
		 $email    		= $this->input->post("Email"); 
		 
		 $activatedOn    =NULL;
         $InActivatedOn    =NULL;
		 
		 if($this->input->post('Status')=='Active')
                    $activatedOn =date('Y-m-d H:i:s');
         else
                    $InActivatedOn =date('Y-m-d H:i:s');
		 
		 
		 $profileImage =NULL;
		 
		 $userGroupId			= $this->input->post("UserGroup");
		 $userGroup				= $db->getFieldValueById("sys_user_group", "UserGroup", "Id='{$userGroupId}'");
		 
		 
		 $data = array(
				'UserId' => $this->input->post('User_Id'),
				'Sex' => $sex,
				'Email' => $email,
				'UserGropId' =>$userGroupId,
				'UserType' =>$userGroup,
				'Status' => $status,
                'ActivatedOn' => $activatedOn,
                'InActivatedOn' => $InActivatedOn,
                'ProfilePhoto' => $profileImage
								
				);
		if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
			    $data['password']	= $password;
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		 
		$insert = $this->UserModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		}
		
		 
		 
		 
	 }
	 
	public function userList()
   {
    
        $list = $this->UserModel->getDataTables();
		
		$data = array();
		$data1 = array();
		$no = $_POST['start']; // commented for working
		//$no=1;
		
		foreach ($list as $data) {
		
			$statusClass = "label label-success";
			$showClass	 = "label label-success";
			$statusText	 = "Active";
			
			$avatar 	 = $data->ProfilePhoto;
			
			if(empty($avatar))
			{
					$sex = $data->Sex;
					if($sex=="Male")
						$avatar = "../img/avatars/male.jpg";
					else if($sex=="Female")
						$avatar = "../img/avatars/female.jpg";
					

				
					
				
			}
			else
			{
						$avatar = "../img/avatars/{$avatar}";
			}
			 $avatar = "<div class='avatar' style='text-align:center'><img src='{$avatar}' class='img-avatar' alt='cand'></div>";	
			  
			$no++;
			$row = array();
			$row[] = $avatar;
			$row[] = $data->UserId;
		
			if($data->LastLoginDate!=NULL)
                            $row[] = date('d/m/Y h:i:s a', strtotime($data->LastLoginDate)); // converting to indian format with 12hr.
			else
                            $row[] ="Not yet login.";
                        $row[] = date('d/m/Y h:i:s a', strtotime($data->CreatedOn));
                      
			
			if($data->Status=="Inactive")
			 {
				$statusClass = "label label-danger";
				$statusText  = "Inactive";
			  }
			  
			 
			$row[] = "<span class='" . $statusClass . "'>" . $data->Status . "</span>";
			
			//add html for action
			$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  <a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
			//<a id="viewButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to edit" onclick="edit_menu('."'". $menu->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->UserModel->countAll(),
						"recordsFiltered" => $this->UserModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }

  


    public function getEditUser($id)  // getting data for editing
	{
			
			$data = $this->UserModel->getById($id);
			echo json_encode($data);
	
	}
   
   
   public function deleteUser($id)
   {
	   $data = $this->UserModel->deleteData($id);
	   echo "success";
	   
	   
   }
   //------------------ FUNCTION FOR FILE UPLOAD ---------------------------------
   
  private function uploadFile($id,$path,$types,$width=0,$height=0)
  {
	$config=array(
		'upload_path'	=> $path,
		'allowed_types'	=> $types,
		'encrypt_name'	=> TRUE
	);  
	$this->load->library("upload",$config);
	if($this->upload->do_upload($id))
	{
		$data=$this->upload->data();
		if($data['is_image']==1 && $width!=0 && $height!=0)
			$this->resizeImage($path.$data['file_name'],$width,$height);
		return $data['file_name'];
	}
	else
		return FALSE;
  }
	
	
	private function resizeImage($path,$width,$height)
	{
		$config=array(
			'image_library' => 'gd2',
			'source_image'	=> $path,
			'quality'		=> 60,
			'maintain_ratio'=> FALSE,
			'width'			=> $width,
			'height'		=> $height
		);
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();	
	}
   
   //------------------- FUNCTION FOR FILE UPLOAD END -------------------------------
   
   
   
	 
	 
 
	public function resetAdminPassword()
	{
		$email	= $this->input->post('Email');
		$userId	= $this->input->post('UserId');
		$response = array();
		$db 	=  new Database();
		
		$response["status"] = true;
		$response["msg"]    = "Password reset mail successfully sent to your email, please check it.";
		
		$currentYear = date("Y");
		$SQL ="SELECT Id, UserId, Email FROM login WHERE  UserId='{$userId}' AND Email='{$email}' LIMIT 1";
		$res = $this->db->query($SQL);
		$collegeName= $db->getFieldValueById("settings", "CollegeName", "Id=1");
		if($res->num_rows()>0)
		{
			
			$res = $res->result_array();
			foreach($res as $row)
			{
				$emailTo 	= $row["Email"];
				$name 		= $row["UserId"];
				$id 		= $row["Id"];
				$OTP 		= generateOTP(5);
				
			
				$emailMessage ="Hi, <strong>{$name},</strong><br/>";
				$emailMessage .="<p>Your password has been reseted,</p>";
				$emailMessage .=" <p>User Id : {$name}<br/>";
				$emailMessage .=" <p>Password : {$OTP}<br/>";
				
				$emailMessage .="<br/>Regards,<br/> College Election System";
				$emailMessage .="<p style='font-size:10px;'>Note : This is a system generated email, do not reply.</p>";
				$res = sendCiEMail($emailTo, $emailMessage, "{$collegeName} : College Election System");
				
				$OTP = encriptPassword($OTP);
				
				$updateData = array(
									'Password'	=> $OTP,
									'ModifiedOn'=> date('Y-m-d H:i:s')
					);

				$this->db->update("login", $updateData, array("Id" => $id));		
				
			}
			
			
		}
		else{
			$response["status"] = false;
			$response["msg"]    = "Error!!! User Id or Email is incorrect.";
		}
		
		echo json_encode($response);
		
		
		
		
	}



	public function boothsList()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		$data = array();
		$db= new Database();
		$data["DeptCount"]  = $db->totalCount("Id", "department");
		$data["PostCount"]  = $db->totalCount("Id", "post");
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data["VoterCount"]  = $db->totalCount("Id", "voters");
		$data["CandidCount"]  = $db->totalCount("Id", "candidate_list");
		$data["InvigilatorCount"]  = $db->totalCount("Id", "invigilator");
		$this->load->view('admin/booths', $data);
	}
   
	
	
	public function boothList()
   {
    
        $list = $this->BoothModel->getDataTables();
		
		$data = array();
		$data1 = array();
		$no = $_POST['start']; // commented for working
		//$no=1;
		
		foreach ($list as $data) {
		
			$statusClass = "label label-success";
			$showClass	 = "label label-success";
			$statusText	 = "Active";
			/*
			$avatar 	 = $data->ProfilePhoto;
			
			if(empty($avatar))
			{
					$sex = $data->Sex;
					if($sex=="Male")
						$avatar = "../img/avatars/male.jpg";
					else if($sex=="Female")
						$avatar = "../img/avatars/female.jpg";
					

				
					
				
			}
			else
			{
						$avatar = "../img/avatars/{$avatar}";
			}
			 $avatar = "<div class='avatar' style='text-align:center'><img src='{$avatar}' class='img-avatar' alt='cand'></div>";	
			  */
			$no++;
			$row = array();
			//$row[] = $avatar;
			$row[] = $data->BoothId;
			$row[] = $this->db->where("BoothId",$data->BoothId)->count_all_results("polling");
			$row[] = $data->CreatedOn;
			/*
			if($data->LastLoginDate!=NULL)
                            $row[] = date('d/m/Y h:i:s a', strtotime($data->LastLoginDate)); // converting to indian format with 12hr.
			else
                            $row[] ="Not yet login.";
                        $row[] = date('d/m/Y h:i:s a', strtotime($data->CreatedOn));
                      
			*/
			if($data->Status=="Inactive")
			 {
				$statusClass = "label label-danger";
				$statusText  = "Inactive";
			  }
			  
			 
			$row[] = "<span class='" . $statusClass . "'>" . $data->Status . "</span>";
			
			//add html for action
			$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  <a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
			//<a id="viewButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to edit" onclick="edit_menu('."'". $menu->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->BoothModel->countAll(),
						"recordsFiltered" => $this->BoothModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }

	 public function getEditBooth($id)  // getting data for editing
	{
			
			$data = $this->BoothModel->getById($id);
			echo json_encode($data);
	
	}
	
	
	 public function saveBooth()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $boothId    	= $this->input->post("Booth_Id"); 
		 
		 $db	  		= new Database();	
		 $where   		="BoothId='{$boothId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("booth", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		 
		$adminName = $this->input->post("Admin_Name"); 
		//$pollingCount = $this->input->post("Poll_Count");
		$password      = encriptPassword($this->input->post("Password")); 
		// $sex    		= $this->input->post("Sex"); 
		 $status    	= $this->input->post("Status"); 
		// $email    		= $this->input->post("Email"); 
		 
		 $activatedOn    =NULL;
         $InActivatedOn    =NULL;
		 
		 if($this->input->post('Status')=='Active')
                    $activatedOn =date('Y-m-d H:i:s');
         else
                    $InActivatedOn =date('Y-m-d H:i:s');
		 
		 
		 //$profileImage =NULL;
		 
		// $userGroupId			= $this->input->post("UserGroup");
		// $userGroup				= $db->getFieldValueById("sys_user_group", "UserGroup", "Id='{$userGroupId}'");
		 
		
		 $data = array(
				'BoothId' => $this->input->post('Booth_Id'),
				//'Sex' => $sex,
				'AdminName'=>$adminName,
				//'PollingCount'=>$pollingCount,
				//'Email' => $email,
				//'UserGropId' =>$userGroupId,
				//'UserType' =>$userGroup,
				'Status' => $status,
                'ActivatedOn' => $activatedOn,
                'InActivatedOn' => $InActivatedOn,
              //  'ProfilePhoto' => $profileImage
								
				);
				 $data['password']	= $password;
		if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
			    $data['password']	= $password;
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		 
		$insert = $this->BoothModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		}
		
		 
		 
		 
	 }
	
	
	
	 public function deleteBooth($id)
   {
	   $data = $this->BoothModel->deleteData($id);
	   echo "success";
	   
	   
   }
   
   
   public function candidatesList()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		$data = array();
		$db= new Database();
		$data["DeptCount"]  = $db->totalCount("Id", "department");
		$data["PostCount"]  = $db->totalCount("Id", "post");
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data["VoterCount"]  = $db->totalCount("Id", "voters");
		$data["CandidCount"]  = $db->totalCount("Id", "candidate_list");
		$data["InvigilatorCount"]  = $db->totalCount("Id", "invigilator");
	
		$this->load->view('admin/candidates', $data);
	}


public function candidateList()
   {
    
        $list = $this->CandidateModel->getDataTables();
		
		$data = array();
		$data1 = array();
		$no = $_POST['start']; // commented for working
		//$no=1;
		
		foreach ($list as $data) {
		
			$statusClass = "label label-success";
			$showClass	 = "label label-success";
			$statusText	 = "Active";
			/*
			$avatar 	 = $data->ProfilePhoto;
			
			if(empty($avatar))
			{
					$sex = $data->Sex;
					if($sex=="Male")
						$avatar = "../img/avatars/male.jpg";
					else if($sex=="Female")
						$avatar = "../img/avatars/female.jpg";
					

				
					
				
			}
			else
			{
						$avatar = "../img/avatars/{$avatar}";
			}
			 $avatar = "<div class='avatar' style='text-align:center'><img src='{$avatar}' class='img-avatar' alt='cand'></div>";	
			  */
			$no++;
			$row = array();
			//$row[] = $avatar;
			$row[] = $data->CandidateId;
			$row[] = $data->CandidateName;
			$row[] = $data->DOB;
			$row[] = $data->Gender;
			$row[] = $data->PartyName;
			$row[] = $data->Symbol;
			$row[] = crypt_data($data->VoteCount,"d");
			/*
			if($data->LastLoginDate!=NULL)
                            $row[] = date('d/m/Y h:i:s a', strtotime($data->LastLoginDate)); // converting to indian format with 12hr.
			else
                            $row[] ="Not yet login.";
                        $row[] = date('d/m/Y h:i:s a', strtotime($data->CreatedOn));
                      
			
			if($data->Status=="Inactive")
			 {
				$statusClass = "label label-danger";
				$statusText  = "Inactive";
			  }
			  
			 */
			//$row[] = "<span class='" . $statusClass . "'>" . $data->Status . "</span>";
			
			//add html for action
			$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  <a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
			//<a id="viewButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to edit" onclick="edit_menu('."'". $menu->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->CandidateModel->countAll(),
						"recordsFiltered" => $this->CandidateModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }

public function saveCandidate()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $candidateId    	= $this->input->post("Candidate_Id"); 
		 
		 $db	  		= new Database();	
		 $where   		="CandidateId='{$candidateId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("candidate", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		 
		$candidateName = $this->input->post("Candidate_Name"); 
		$gender   		= $this->input->post("Gender"); 
		$partyName   	= $this->input->post("Party_Name");
		$symbol  		= $this->input->post("Symbol");
		$dob 		= $this->input->post("DOB");
		// $status    	= $this->input->post("Status"); 
		// $email    		= $this->input->post("Email"); 
		 
		 $activatedOn    =NULL;
		 $activatedOn    =NULL;
         $InActivatedOn    =NULL;
		 /*
		 if($this->input->post('Status')=='Active')
                    $activatedOn =date('Y-m-d H:i:s');
         else
                    $InActivatedOn =date('Y-m-d H:i:s');
		 */
		 
		 //$profileImage =NULL;
		 
		// $userGroupId			= $this->input->post("UserGroup");
		// $userGroup				= $db->getFieldValueById("sys_user_group", "UserGroup", "Id='{$userGroupId}'");
		 
		 
		 $data = array(
				'CandidateId' => $this->input->post('Candidate_Id'),
				'Gender' => $gender,
				'CandidateName'=>$candidateName,
				'DOB'=>$dob,
				//'VotersCount'=>$dob,
				//'Email' => $email,
				//'UserGropId' =>$userGroupId,
				//'UserType' =>$userGroup,
				'PartyName' => $partyName,
				'Symbol' => $symbol,
               // 'ActivatedOn' => $activatedOn,
                //'InActivatedOn' => $InActivatedOn,
              //  'ProfilePhoto' => $profileImage
								
				);
		/*if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
			    $data['password']	= $password;
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		 */
		$insert = $this->CandidateModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		} 
		 
	 }
	 
	 
	  public function deleteCandidate($id)
   {
	   $data = $this->CandidateModel->deleteData($id);
	   echo "success";
	   
	   
   }
   
   
   public function getEditCandidate($id)  // getting data for editing
	{
			
			$data = $this->CandidateModel->getById($id);
			echo json_encode($data);
	
	}
	
	public function votersList()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		$data = array();
		$db= new Database();
		$data["DeptCount"]  = $db->totalCount("Id", "department");
		$data["PostCount"]  = $db->totalCount("Id", "post");
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data["VoterCount"]  = $db->totalCount("Id", "voters");
		$data["CandidCount"]  = $db->totalCount("Id", "candidate_list");
		$data["InvigilatorCount"]  = $db->totalCount("Id", "invigilator");
	
		$this->load->view('admin/voters', $data);
	}
	
	
	
	public function voterList()
   {
    
        $list = $this->VoterModel->getDataTables();
		
		$data = array();
		$data1 = array();
		$no = $_POST['start']; // commented for working
		//$no=1;
		
		foreach ($list as $data) {
		
			$statusClass = "label label-success";
			$showClass	 = "label label-success";
			$statusText	 = "Active";
			
			$avatar 	 = base_url().$data->Photo;
			
			if(empty($avatar))
			{
					$sex = $data->Gender;
					if($sex=="Male")
						$avatar = "../img/avatars/male.jpg";
					else if($sex=="Female")
						$avatar = "../img/avatars/female.jpg";
				
			}
			 $avatar = "<a href='".$avatar."' target='_blank'><div class='avatar' style='text-align:center'><img src='{$avatar}' class='img-avatar' alt='cand'></div></a>";	
			  
			  
			 
			$no++;
			$row = array();
			$row[] = $avatar;
			$row[] = $data->VoterId;
			$row[] = $data->AadharId;
			$row[] = $data->VoterName;
			$row[] = $data->DOB;
			$row[] = $data->Address;
			$row[] = $data->Gender;
			$row[] = $data->BoothId;
			$row[] = $data->Eligibility;
			/*
			if($data->LastLoginDate!=NULL)
                            $row[] = date('d/m/Y h:i:s a', strtotime($data->LastLoginDate)); // converting to indian format with 12hr.
			else
                            $row[] ="Not yet login.";
                        $row[] = date('d/m/Y h:i:s a', strtotime($data->CreatedOn));
                      
			*/
		/*if($data->Status=="Inactive")
			 {
				$statusClass = "label label-danger";
				$statusText  = "Inactive";
			  }
			  */
			 
			//$row[] = "<span class='" . $statusClass . "'>" . $data->Status . "</span>";
			//add html for action
			$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
			<a  id="fingerButton" class="btn btn-warning" href="addfinger/'.$data->Id.'/voter" title="Click to Add Finger"><i class="fas fa-fingerprint"></i> Fingers</a>
			
				  <a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
	//	<a id="revokeButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to Revoke" onclick="revokeVoter('."'". $data->Id ."'".')"><i class="fas fa-ban"></i>Revoke</a>
		
			$data1[] = $row;
		}
		
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->VoterModel->countAll(),
						"recordsFiltered" => $this->VoterModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }

	
	
		 public function saveVoter()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $voterId    	= $this->input->post("Voter_Id"); 
		 $aadharId    	= $this->input->post("Aadhar_Id"); 
		 $db	  		= new Database();	
		 $where   		="VoterId='{$voterId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("voter", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		 
		$voterName = $this->input->post("Voter_Name"); 
		$dob = $this->input->post("DOB");
		$address = $this->input->post("Address");
		$gender = $this->input->post("Gender");
		$boothId = $this->input->post("Booth_Id");
		$eligibility = $this->input->post("Eligibility");
		
		 
		 
		 $data = array(
				'VoterId' => $this->input->post('Voter_Id'),
				'AadharId' => $this->input->post('Aadhar_Id'),
				'VoterName'=>$voterName,
				'DOB'=>$dob,
				'Address'=>$address,
				'Gender'=>$gender,
				'BoothId'=>$boothId,
				'Eligibility'=>$eligibility,
			
				);
		if($_FILES['userfile']['error']<1)
		{
			$image=$this->uploadFile("userfile","uploads/voters/","jpg|jpeg|png",200,200);
			if(!$image)
			{
				echo json_encode(array("status" => FALSE,"msg" => "Upload Error!!!"));
				exit;
			}
			$data['Photo']="uploads/voters/".$image;
		}
		if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		
		$insert = $this->VoterModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		} 
		 
	 }
	
	  public function deleteVoter($id)
   {
	   $data = $this->VoterModel->deleteData($id);
	   echo "success";
	   
	   
   }
	public function revokeVoter($id)
	{
		$data = $this->VoterModel->revokeVoter($id);
		echo "success";
	}
	
	public function getEditVoter($id)  // getting data for editing
	{
			
			$data = $this->VoterModel->getById($id);
			echo json_encode($data);
	}
	
	public function schedule()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		$data = array();
		$db= new Database();
		$data["UserCount"]  = $db->totalCount("Id", "login");
		$data["InvigilatorCount"]  = $db->totalCount("Id", "invigilator");
		
		
		$this->load->view('admin/schedule', $data);
	}

	public function schedulesubmit()
	{
		$data=array("ElectionDate"=>$this->input->post('ElectionDate'),"ElectionName"=>$this->input->post('ElectionName'),"StartTime"=>$this->input->post('StartTime'),"EndTime"=>$this->input->post('EndTime'));
		$this->db->insert("voting_config",$data);
		
	}
	
	public function scheduleList()
   {
    
        $list = $this->ScheduleModel->getDataTables();
		
		$data = array();
		$data1 = array();
		$no = $_POST['start']; // commented for working
		//$no=1;
		
		foreach ($list as $data) {
		
			$statusClass = "label label-success";
			$showClass	 = "label label-success";
			$statusText	 = "Active";
			/*
			$avatar 	 = $data->ProfilePhoto;
			
			if(empty($avatar))
			{
					$sex = $data->Sex;
					if($sex=="Male")
						$avatar = "../img/avatars/male.jpg";
					else if($sex=="Female")
						$avatar = "../img/avatars/female.jpg";
					

				
					
				
			}
			else
			{
						$avatar = "../img/avatars/{$avatar}";
			}
			 $avatar = "<div class='avatar' style='text-align:center'><img src='{$avatar}' class='img-avatar' alt='cand'></div>";	
			  */
			$no++;
			$row = array();
			//$row[] = $avatar;
			
			$row[] = $data->ScheduleId;
			$row[] = $data->ElectionName;
			$row[] = $data->ElectionDate;
			
			$row[] = $data->StartTime;
			$row[] = $data->EndTime;
			/*
			if($data->LastLoginDate!=NULL)
                            $row[] = date('d/m/Y h:i:s a', strtotime($data->LastLoginDate)); // converting to indian format with 12hr.
			else
                            $row[] ="Not yet login."
                        $row[] = date('d/m/Y h:i:s a', strtotime($data->CreatedOn));
                      
			
			if($data->Status=="Inactive")
			 {
				$statusClass = "label label-danger";
				$statusText  = "Inactive";
			  }
			  
			 
			$row[] = "<span class='" . $statusClass . "'>" . $data->Status . "</span>";
			*/
			//add html for action
			$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  <a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
			//<a id="revokeButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to Revoke" onclick="edit_menu('."'". $data->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->ScheduleModel->countAll(),
						"recordsFiltered" => $this->ScheduleModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }
	
	 public function saveSchedule()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $scheduleId    = $this->input->post("Schedule_Id"); 
		 $db	  		= new Database();	
		$where   		="ScheduleId='{$scheduleId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("voting_config", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		// $scheduleId=  $this->input->post("Schedule_Id"); 
		$electionDate = $this->input->post("Election_Date"); 
		$electionName = $this->input->post("Election_Name"); 
		$startTime = $this->input->post("Start_Time");
		$endTime = $this->input->post("End_Time");
		// $password      = encriptPassword($this->input->post("Password")); 
		// $sex    		= $this->input->post("Sex"); 
	///	 $status    	= $this->input->post("Status"); 
		// $email    		= $this->input->post("Email"); 
		 
		 $activatedOn    =NULL;
         $InActivatedOn    =NULL;
		 /*
		 if($this->input->post('Status')=='Active')
                    $activatedOn =date('Y-m-d H:i:s');
         else
                    $InActivatedOn =date('Y-m-d H:i:s');
		 
		 */
		 //$profileImage =NULL;
		 
		// $userGroupId			= $this->input->post("UserGroup");
		// $userGroup				= $db->getFieldValueById("sys_user_group", "UserGroup", "Id='{$userGroupId}'");
		 
		 
		 $data = array(
				//'Id' => $this->input->post('Id'),
				//'Sex' => $sex,
			'ScheduleId'=>$scheduleId,
				'ElectionName'=>$electionName,
				'ElectionDate'=>$electionDate,
				'StartTime'=>$startTime,
				'EndTime'=>$endTime,
				//'Email' => $email,
				//'UserGropId' =>$userGroupId,
				//'UserType' =>$userGroup,
			//	'Status' => $status,
              //  'ActivatedOn' => $activatedOn,
                //'InActivatedOn' => $InActivatedOn,
              //  'ProfilePhoto' => $profileImage
								
				);
				/*
		if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
			    $data['password']	= $password;
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		 */
		$insert = $this->ScheduleModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		}
		
		 
		 
		 
	 }
	
	
	 public function getEditSchedule($id)  // getting data for editing
	{
			
			$data = $this->ScheduleModel->getById($id);
			echo json_encode($data);
	
	}
	
	
	 public function deleteSchedule($id)
   {
	   $data = $this->ScheduleModel->deleteData($id);
	   echo "success";
	   
	   
   }
   
    public function scanFingerprint()
   {
 echo "Not available";
   }
   
   
   
   
   
   public function staff()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}
	
		$this->load->view('admin/staff');
	}
	
	
	
   
   public function staffList()
   {
    
        $list = $this->StaffModel->getDataTables();
		
		$data = array();
		$data1 = array();
		$no = $_POST['start']; // commented for working
		//$no=1;
		
		foreach ($list as $data) {
		
			$statusClass = "label label-success";
			$showClass	 = "label label-success";
			$statusText	 = "Active";
			/*
			$avatar 	 = $data->ProfilePhoto;
			
			if(empty($avatar))
			{
					$sex = $data->Sex;
					if($sex=="Male")
						$avatar = "../img/avatars/male.jpg";
					else if($sex=="Female")
						$avatar = "../img/avatars/female.jpg";
					

				
					
				
			}
			else
			{
						$avatar = "../img/avatars/{$avatar}";
			}
			 $avatar = "<div class='avatar' style='text-align:center'><img src='{$avatar}' class='img-avatar' alt='cand'></div>";	
			  */
			$no++;
			$row = array();
			//$row[] = $avatar;
			$row[] = $data->StaffID;
			$row[] = $data->AadharID;
			$row[] = $data->StaffName;
			$row[] = $data->DOB;
			$row[] = $data->Address;
			$row[] = $data->Gender;
			$row[] = $data->BoothID;
			$row[] = $data->MobNo;
			/*
			if($data->LastLoginDate!=NULL)
                            $row[] = date('d/m/Y h:i:s a', strtotime($data->LastLoginDate)); // converting to indian format with 12hr.
			else
                            $row[] ="Not yet login.";
                        $row[] = date('d/m/Y h:i:s a', strtotime($data->CreatedOn));
                      
			*/
		/*if($data->Status=="Inactive")
			 {
				$statusClass = "label label-danger";
				$statusText  = "Inactive";
			  }
			  */
			 
			//$row[] = "<span class='" . $statusClass . "'>" . $data->Status . "</span>";
			//add html for action
			$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
			<a  id="fingerButton" class="btn btn-warning" href="addfinger/'.$data->Id.'/staff" title="Click to Add Finger"><i class="fas fa-fingerprint"></i> Fingers</a>
			
				  <a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
	//	<a id="revokeButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to Revoke" onclick="revokeVoter('."'". $data->Id ."'".')"><i class="fas fa-ban"></i>Revoke</a>
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->StaffModel->countAll(),
						"recordsFiltered" => $this->StaffModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }
	
	
   
   /*
   	 public function saveStaff()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $staffId    	= $this->input->post("Staff_Id"); 
		 $aadharId    	= $this->input->post("Aadhar_Id"); 
		 $db	  		= new Database();	
		 $where   		="StaffID='{$staffId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("staff", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		 
		$staffName = $this->input->post("Staff_Name"); 
		$dob = $this->input->post("DOB");
		$address = $this->input->post("Address");
		$gender = $this->input->post("Gender");
		$boothId = $this->input->post("Booth_Id");
		$mobNo = $this->input->post("Mob_No");
		// $password      = encriptPassword($this->input->post("Password")); 
		// $sex    		= $this->input->post("Sex"); 
		 //$status    	= $this->input->post("Status"); 
		// $email    		= $this->input->post("Email"); 
		 /*
		 $activatedOn    =NULL;
         $InActivatedOn    =NULL;
		 
		 if($this->input->post('Status')=='Active')
                    $activatedOn =date('Y-m-d H:i:s');
         else
                    $InActivatedOn =date('Y-m-d H:i:s');
		 
		 */
		 //$profileImage =NULL;
		 
		// $userGroupId			= $this->input->post("UserGroup");
		// $userGroup				= $db->getFieldValueById("sys_user_group", "UserGroup", "Id='{$userGroupId}'");
		 /*
		 
		 $data = array(
				'StaffID' => $this->input->post('Staff_Id'),
				'AadharID' => $this->input->post('Aadhar_Id'),
				//'Sex' => $sex,
				'StaffName'=>$staffName,
				'DOB'=>$dob,
				'Address'=>$address,
				'Gender'=>$gender,
				'BoothID'=>$boothId,
				'MobNo'=>$mobNo,
				//'VotersCount'=>$votersCount,
				//'Email' => $email,
				//'UserGropId' =>$userGroupId,
				//'UserType' =>$userGroup,
				//'Status' => $status,
                //'ActivatedOn' => $activatedOn,
                //'InActivatedOn' => $InActivatedOn,
              //  'ProfilePhoto' => $profileImage
								
				);
		/*if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
			    $data['password']	= $password;
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		 
		$insert = $this->StaffModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		} 
		 
	 }
	*/
	
	
	
	 public function saveStaff()
	 {
		 $response = array();
		 
		 $id			= $this->input->post("hidID");
		 $staffId    	= $this->input->post("Staff_Id"); 
		 
		 $db	  		= new Database();	
		 $where   		="StaffID='{$staffId}' AND Id!='{$id}'";
		 $res = $db->checkExistance("staff", $where); //  if exists id value will be returnd.
	
		if($res)
			{
				 $response["status"] = false;
				 $response["msg"] 	 = "Sorry! This user id already exists.";
				 
				 echo json_encode($response);
				 exit; 	
			}	
		 
		  
		 
		$aadharId    	= $this->input->post("Aadhar_Id"); 
		$staffName = $this->input->post("Staff_Name"); 
		$dob = $this->input->post("DOB");
		// $password      = encriptPassword($this->input->post("Password")); 
		$address = $this->input->post("Address");
		$gender = $this->input->post("Gender");
		$boothId = $this->input->post("Booth_Id");
		$mobNo = $this->input->post("Mob_No");
		 
		 $activatedOn    =NULL;
         $InActivatedOn    =NULL;
		 /*
		 if($this->input->post('Status')=='Active')
                    $activatedOn =date('Y-m-d H:i:s');
         else
                    $InActivatedOn =date('Y-m-d H:i:s');
		 
		 
		 $profileImage =NULL;
		
		 $userGroupId			= $this->input->post("UserGroup");
		 $userGroup				= $db->getFieldValueById("sys_user_group", "UserGroup", "Id='{$userGroupId}'");
		 
		  */
		 $data = array(
				'StaffID' => $this->input->post('Staff_Id'),
				'AadharID' => $this->input->post('Aadhar_Id'),
				//'Sex' => $sex,
				'StaffName'=>$staffName,
				'DOB'=>$dob,
				'Address'=>$address,
				'Gender'=>$gender,
				'BoothID'=>$boothId,
				'MobNo'=>$mobNo
				);
		/*
		if($id==0)
		{	
		        $data['CreatedBy'] = $_SESSION['ADMIN_ID'];
				$data['CreatedOn'] = date('Y-m-d H:i:s');
				
			    $data['password']	= $password;
        } 
		else
		{
				$data['ModifiedBy'] = $_SESSION['ADMIN_ID'];
				$data['ModifiedOn'] = date('Y-m-d H:i:s');
			
		}
		 */
		$insert = $this->StaffModel->saveData($data, $id);	
		
		if($insert)
		{	
			$response["status"] = true;
			$response["msg"] 	 = "success";
			echo json_encode($response);
			exit;
		}

		else
		{
				$response["status"] = false;
				$response["msg"] 	 = "Sorry! there where some error while saving data.";
				echo json_encode($response);
				exit;
			
		}
		
		 
		 
		 
	 }
	
	
	
	
	
	  public function deleteStaff($id)
   {
	   $data = $this->StaffModel->deleteData($id);
	   echo "success";
	   
	   
   }
   
   
   	public function getEditStaff($id)  // getting data for editing
	{
			
			$data = $this->StaffModel->getById($id);
			echo json_encode($data);
	}
   
   
   
   public function addFinger($id,$type)
   {
	   if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		
		$this->load->view('admin/fingerprint',array('Id'=>$id,"Type"=>$type));
   }
   function addFingerToDb()
   {
	   $id=$this->input->post('Id');
	   $fingerId=$this->input->post('Finger');
	   $fingerData=$this->input->post('Data');
	   $table=$this->input->post('Type');
	   $updCol="";
	   if($fingerId=="1")
			$updCol="LeftThumb";
	   else if($fingerId=="2")
			$updCol="LeftIndex";
	   else if($fingerId=="3")
			$updCol="RightThumb";
	   else
			$updCol="RightIndex";
		$this->db->update($table,array($updCol=>$fingerData),"Id = '$id'");
		if($this->db->affected_rows()>0)
			echo "Successfully Saved...";
		else
			echo "Error Saving Data, Please Try Again";
   }
   function deVote($enc)
   {
	   echo crypt_data($enc,"d");
   }
	function resetFirebase()
	{
		$firebase=json_encode($this->db->select("Id,CandidateId,CandidateName,Photo,VoteCount")->get("candidate")->result_array());
		echo json_encode(array('status'=>TRUE,'firebase'=>$firebase));
	}
}
?>