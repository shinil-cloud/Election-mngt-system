<?php

// This controller is going to be the client side controller.

defined('BASEPATH') OR exit('No direct script access allowed');


class Pba extends CI_Controller {


 function __construct() {
        parent::__construct();
        
		$this->load->helper('common_functions_helper'); // for password encription/ verification
		$this->load->helper('send_email_helper');
		$this->load->helper('sms');
		$this->load->helper('dbclass');
				
        
        session_start();
		$this->load->model("AdminModel");
		$this->load->model("PbaModel");
		$this->load->model("UserModel");
		$this->load->model("BoothModel");
		$this->load->model("CandModel");
		$this->load->model("VoteScreenModel");
		$this->load->model("VModel");
		$this->load->model("ScheduleModel");
		$this->load->model("VerifyModel");
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
	if(!isset($_SESSION['BOOTH_ID']))
	   return FALSE;
	else
	    return TRUE;	
   }
	
	
	
	public function index()
	{
		$this->load->view('public/pba/login');
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
		//$data["UserCount"]  = $db->totalCount("Id", "voter");
		$query=$this->db->query("select * from  voter where BoothId = '{$_SESSION['BOOTH_ID']}' ");
		$data['count']=$query->num_rows();
		$data['poll']=$this->db->get_where('polling',"BoothId = '{$_SESSION['BOOTH_ID']}' and Status = 'Inactive'")->num_rows();
		$data['remaining']=$data['count']-$data['poll'];
		$sId=$this->db->select('ScheduleId')->get_where('booth',"BoothId = '{$_SESSION['BOOTH_ID']}'")->row('ScheduleId');
		$data['electionTitle']=$this->db->select('ElectionName')->get_where('voting_config',"ScheduleId='$sId'")->row('ElectionName');
		$this->load->view('public/pba/index', $data);
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
		
		
		$this->load->view('public/pba/users', $data);
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
		
		
		$this->load->view('public/pba/changepassword', $data);
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
	/*	$logData= array (
								'LoginStatus' => '0'
							);
		
		$this->db->where('Id',$_SESSION['ADMIN_LOGIN_ID']);
        $this->db->update('login',$logData);	*/
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
   
  private function uploadFile($id,$path,$types,$width,$height)
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
			$this->resizeImage();
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
			'quality'		=> 100,
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
			$row[] = $data->AdminName;
			$row[] = $data->VotersCount;
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
		$votersCount = $this->input->post("Voters_Count");
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
				'VotersCount'=>$votersCount,
				//'Email' => $email,
				//'UserGropId' =>$userGroupId,
				//'UserType' =>$userGroup,
				'Status' => $status,
                'ActivatedOn' => $activatedOn,
                'InActivatedOn' => $InActivatedOn,
              //  'ProfilePhoto' => $profileImage
								
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
	
		$this->load->view('public/pba/candidates', $data);
	}


public function candidateList()
   {
    
        $list = $this->CandModel->getDataTables();
		
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
			//$row[] = $data->VoteCount;
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
			//$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  //<a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
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
	
		$this->load->view('public/pba/voters', $data);
	}
	
	
	
	
	public function voterList()
   {
    
        $list = $this->VModel->getDataTables();
		
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
			$row[] = $data->VoterId;
			$row[] = $data->AadharId;
			$row[] = $data->VoterName;
			$row[] = $data->DOB;
			$row[] = $data->Address;
			$row[] = $data->Gender;
			$row[] = $data->BoothId;
			$row[] = $data->VoteStatus;
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
			//$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  //<a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
			//<a id="viewButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to edit" onclick="edit_menu('."'". $menu->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->VModel->countAll(),
						"recordsFiltered" => $this->VModel->countFiltered(),
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
		//$votersCount = $this->input->post("Voters_Count");
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
		 
		 
		 $data = array(
				'VoterId' => $this->input->post('Voter_Id'),
				//'Sex' => $sex,
				'VoterName'=>$voterName,
				'DOB'=>$dob,
				'Address'=>$address,
				'Gender'=>$gender,
				'BoothId'=>$boothId,
				
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
		 */
		$insert = $this->VModel->saveData($data, $id);	
		
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
	   $data = $this->VModel->deleteData($id);
	   echo "success";
	   
	   
   }
	
	
	public function getEditVoter($id)  // getting data for editing
	{
			
			$data = $this->VModel->getById($id);
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
		$data=array("ElectionDate"=>$this->input->post('ElectionDate'),"StartTime"=>$this->input->post('StartTime'),"EndTime"=>$this->input->post('EndTime'));
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
		  		  
			//<a id="viewButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to edit" onclick="edit_menu('."'". $menu->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
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
   
   public function verify()
   
   {
	   $id			= $this->input->post("hidID");
	  $voterid=$this->input->post('VoterId');
	  $aadharid=$this->input->post('AadharId');
	  //$res = $this->VerifyModel->voterProceedCheck($voterid, $aadharid);
	  $res=$this->db->get_where('voter', "AadharId = '$aadharid' AND VoterId = '$voterid' AND BoothId = '{$_SESSION['BOOTH_ID']}'");
  // $data['user']=$this->db->get_where('voter', "VoterId = '$voterid'")->row_array();
	   if($res->num_rows()<1)
		   echo json_encode(array("status"=>FALSE,"msg"=>"Invalid"));
	   else if($res->row('VoteStatus')=="Voted")
		   echo json_encode(array("status"=>FALSE,"msg"=>"Already Voted"));
	   else
	   {
		   $_SESSION['VOTER_TABLE_ID']=$res->row('Id');
	   echo json_encode(array("status"=>TRUE,"msg"=>"SUCCESS"));
	   }
   }


public function verification()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}	
		if(!isset($_SESSION['VOTER_TABLE_ID']))
		{
			$this->adminHome();
			return;
		}
	    $data['user']=$this->db->get_where('voter', "Id = '{$_SESSION['VOTER_TABLE_ID']}' and BoothId= '{$_SESSION['BOOTH_ID']}'")->row_array();
		$this->load->view('public/pba/verify',$data);
	}
	private function generateLink($voterId)
	{
		$link=$_SESSION['BOOTH_ID']."_".$voterId;
		return crypt_data($link);
	}

	public function voteScreen()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}
		$voter=$this->db->get_where('polling',"BoothId = '{$_SESSION['BOOTH_ID']}' AND Status = 'Active'")->row_array();
		if(count($voter)<1 OR !isset($_SESSION['VOTER_TABLE_ID']))
		{
			$this->load->view('public/pba/votescreen-pre');
			return;
		}
		$data['voter']=$this->db->get_where("voter","VoterId = '{$voter['VoterId']}'")->row_array();
		$data['cands']=$this->db->get('candidate')->result_array();
		$this->load->view('public/pba/votescreen',$data);
	}
	public function voteScreenContent()
   {
    
        $list = $this->VoteScreenModel->getDataTables();
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
			//$row[] = $data->CandidateId;
			$row[] = $data->Photo;
			$row[] = $data->CandidateName;
			//$row[] = $data->DOB;
			//$row[] = $data->Gender;
			$row[] = $data->PartyName;
			$row[] = $data->Symbol;
			//$row[] = $data->VoteCount;
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
			//$row[] = '<a  id="editButton" class="btn btn-info" href="javascript:void(0)" title="Click to Edit" onclick="editData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-edit"></i> Edit</a>
				  //<a  id="deleteButton" class="btn btn-sm btn-danger" href="javascript:void(0)" title="Click to Delete" onclick="deleteData('."'".$data->Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		  		  
			//<a id="viewButton" class="btn btn-small btn-info" href="javascript:void(0)" title="Click to edit" onclick="edit_menu('."'". $menu->Id ."'".')"><i class="fa fa-sticky-note-o"></i> View</a>';	  
		
			$data1[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->VoteScreenModel->countAll(),
						"recordsFiltered" => $this->VoteScreenModel->countFiltered(),
						"data" => $data1,
				);
		//output to json format
		echo json_encode($output);
   }
	function testEnc($string)
	{
		echo crypt_data($string);
	}
	function testDec($string)
	{
		echo crypt_data($string,"d");
	}
	function testDB($id)
	{
		// $res=$this->db->get('candidate')->result_array();
		// echo json_encode($res);
		// foreach($res as $row)
			// echo "<br>".$row['CandidateName'];
		//$res=$this->db->get('candidate')->row_array();
		//$res=$this->db->get('candidate')->row();
		//echo json_encode($res);
		//foreach($res as $row)
		//echo "<br>".$res['CandidateName'];
		//echo "<br>".$res->CandidateName;
		echo $this->db->get_where('candidate',"Id = '$id'")->row('Symbol');
	}
	function testForeach()
	{
		$eg=array(
			array("Name"=>"Akhil","Roll"=>"ST5","Age"=>"25"),
			array("Name"=>"Shijil","Roll"=>"ST1","Age"=>"24"),
			array("Name"=>"Anil","Roll"=>"ST45","Age"=>"19"),
		
		);
		 $i=0;
		// foreach($eg as $key=>$value)
		// {
			// $i++;
			// echo "Student - $i<br/>";
			// echo "Name : ".$value['Name']."<br/>";
			// echo "Roll : ".$value['Roll']."<br/>";
			// echo "Age : ".$value['Age']."<br/>";
			// echo "-------------------------------<br/>";
		// }
		foreach($eg as $key=>$value)
		{
			$i++;
			echo "Student - $i<br/>";
			foreach($value as $innerkey=>$innervalue)
				echo "$innerkey : ".$innervalue."<br/>";
			echo "-------------------------------<br/>";
		}
	}
	function intoPolling($id)
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}
		$voter=$this->db->select('VoterId,BoothId')->get_where('voter',"Id = '$id'")->row_array();
		if($this->db->get_where('polling',"VoterId = '{$voter['VoterId']}'")->num_rows()<1)
			$this->db->insert('polling',$voter);
		header('location:'.base_url('pba/verification'));
	}
	
	
	function setPolling($id)
	{
		$voter=$this->db->select('VoterId,BoothId')->get_where('voter',"Id = '$id'")->row_array();
		if($this->db->get_where('polling',"VoterId = '{$voter['VoterId']}'")->num_rows()<1)
			{
			$this->db->insert('polling',$voter);
			echo json_encode(array('status'=>TRUE,'message'=>"Polling Session Initialized"));
			}
		else
			echo json_encode(array('status'=>FALSE,'message'=>"Already Initialized Session! Please Proceed for Voting"));
	}
	
	
	
	
	
	
	
	function casteVote()
	{
		$voterId=$this->input->post('voter');
		$candId=$this->input->post('cand');
		if($this->db->get_where('voter',"VoteStatus = 'Active' AND VoterId = '$voterId'")->num_rows()<1)
		{
			echo json_encode(array('status'=>TRUE,'msg'=>"Your Vote Already Casted..."));
			exit;
		}
		$count=$this->db->select('VoteCount')->get_where('candidate',"Id = '$candId'")->row('VoteCount');
		$candName=$this->db->select('CandidateName')->get_where('candidate',"Id = '$candId'")->row('CandidateName');
		$count=crypt_data($count,"d");
		$count++;
		$count=crypt_data($count);
		$this->db->update('voter',array('VoteStatus'=>'Voted'),"VoterId = '$voterId'");
		$this->db->update('polling',array('Status'=>'Inactive'),"VoterId = '$voterId'");
		$this->db->update('candidate',array('VoteCount'=>$count),"Id = '$candId'");
		$firebase=json_encode($this->db->select("Id,CandidateId,CandidateName,Photo,VoteCount")->get("candidate")->result_array());
		echo json_encode(array('status'=>TRUE,'msg'=>"Your Vote Successfully Casted to $candName...",'firebase'=>$firebase));
	}
	function getFingers($id)
	{
		$voter=$this->db->get_where("voter","Id = '$id'")->row();
		echo json_encode(array(
			$voter->LeftThumb,
			$voter->LeftIndex,
			$voter->RightThumb,
			$voter->RightIndex
		));
	}

	
	
	
	
	function sendBoothOTP()
	{
		$res=$this->db->join('booth AS BT','BT.BoothId = ST.BoothId')->get_where("staff AS ST",array(
			'BT.Password'=>encriptPassword($this->input->post('Password')),
			'BT.BoothId'=>$this->input->post('Id'),
			'ST.StaffId'=>$this->input->post('StaffId')
		))->row_array();
		if(count($res)<1)
		{
			echo "Entered Data is Invalid....";
			exit;
		}
		if(!isset($_SESSION['OTP']))
			$_SESSION['OTP']=rand(100000,999999);
		$message="Your OVS OTP is {$_SESSION['OTP']} Valid for 20 mins.";
		if(sendSMS($res['MobNo'],$message))
			echo "OTP ".$_SESSION['OTP']." Send to *******".substr($res['MobNo'],7);
	    else
	    	echo "Error Sending OTP Please Try Again....";
	}
	function checkOTP()
	{
		if($_SESSION['OTP']==$this->input->post('OTP'))
		{
			$staff=$this->db->get_where("staff","StaffId = '{$this->input->post('StaffId')}'")->row();
			echo json_encode(
				array(
					'status'=>TRUE,
					'fingers'=>array(
						$staff->LeftThumb,
						$staff->LeftIndex,
						$staff->RightThumb,
						$staff->RightIndex
					)
				)
			);
		}
		else
			echo json_encode(array('status'=>FALSE,'msg'=>"Invalid OTP"));
	}
	function setLogin()
	{
		$_SESSION['BOOTH_ID']=$this->input->post('Id1');
		$_SESSION['STAFF_ID']=$this->input->post('Id2');
		echo base_url("pba/preHome");
	}
	function preHome()
	{
		if(!$this->isSessionSet())
		{
			$this->index();
			return;
		}
		$this->load->view('public/pba/pre-home');
	}
}
?>