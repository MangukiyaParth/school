<?php

/**
 * 
 */
class AssignStaff extends CI_Controller
{
	
	public function __construct() {
		parent::__construct();
	}


	/** 
	 *	@getDashboardData 
	 *  get Dashboard counter
	 */ 
	public function index() {
		$data = [];
		$this->load->view('assignstaff/list', $data);
	}
	
	public function AddStaff($id) {
		if($id > 0)
		{
			$this->db->where('paperassignstaff.id',$id);
			$getData = $this->db->get('paperassignstaff')->result_array();
			$data['data'] = $getData[0];
		}
		else
		{
			$data = [];
		}
		$this->db->select('id, username, department');
		$staffData = $this->db->get('staff_details')->result_array();
		$data['staff'] = json_encode($staffData);
		
		$getData = $this->db->get('department')->result_array();
		$data['department'] = $getData;

		$this->load->view('assignstaff/add', $data);
	}


	/** 
	 *	@getDashboardData 
	 *  get Dashboard counter
	 */ 
	public function SaveStaffAssign() {
		
		$postData =  $this->input->post();
		$paperid = [];
		$papername = [];
		foreach($postData['paper_id'] as $paper)
		{
			$paper = explode('~', $paper);
			$paperid[] = $paper[0];
			$papername[] = $paper[2];
		}
		if($postData['id'] > 0)
		{
			$query_insert_data = "
				UPDATE paperassignstaff	SET 
				`paper` = '".implode(',',$postData['paper_id'])."'
				`paperid` = '".implode(',',$paperid)."'
				`papername` = '".implode(', ',$papername)."',
				`year` = '".$postData['year']."'
				WHERE id = '".$postData['id']."'
			";
			$this->db->query($query_insert_data);
		}
		else
		{
			$query_insert_data = "
			INSERT INTO `paperassignstaff` (`staffid`, `semester`, `department`, `paper`, `paperid`, `papername`, `year`) 
			VALUES 
			('".$postData['teacher_id']."','".$postData['semester']."','".implode(',',$postData['department_id'])."','".implode(',',$postData['paper_id'])."','".implode(',',$paperid)."','".implode(', ',$papername)."', '".$postData['year']."')			
			";
			$this->db->query($query_insert_data);
		}
		
		redirect('AssignStaff/list');
		
	}

	public function list()
	{
		$this->load->view('assignstaff/list');
	}

	public function AssignStaffList($start = 0) {
		
		$postData =  $this->input->post();
		$this->db->select('paperassignstaff.*, CONCAT(IFNULL(staff_details.firstname,"")," ",IFNULL(staff_details.lastname,"")) AS fullname');
		if(isset($postData['search']) && !empty($postData['search'])) {
			$this->db->where('paperassignstaff.semester',$postData['search']);
			$this->db->or_like('CONCAT(IFNULL(staff_details.firstname,"")," ",IFNULL(staff_details.lastname,""))',$postData['search']);
			$this->db->or_like('paperassignstaff.paper',$postData['search']);
		}
		$this->db->join('staff_details','staff_details.id=paperassignstaff.staffid');
		$getData = $this->db->get('paperassignstaff')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('assignstaff/AssignStaffList/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);
		

	}


	public function DeleteAssignStaff($id = 0) {
		$this->db->where('reminder.id', $id);
		$this->db->delete('reminder');
		$this->load->view('reminders/list', []);
	}

	public function GetPapers() {
		$postData =  $this->input->post();
		$semester = $postData['semester'];
		$selectedpaper = '';
		if(isset($postData['selectedpaper']) && !empty($postData['selectedpaper'])) {
			$selectedpaper = $postData['selectedpaper'];
		}
		$path = FCPATH;
		$path .= "media\bsc";

		if (!is_dir($path) && !file_exists($path)) {
			$oldmask = umask(0);
			if(!file_exists($path)){
				@mkdir($path, 0777, true); //  Warning: mkdir(): File exists
			}
			umask($oldmask);
		}

		$data = [];
		$data['selectedpaper'] = $selectedpaper;
		$data['paperData'] = file_get_contents($path."\sem".$semester.".json");
		$data['specializationMapDepartment'] = file_get_contents($path."\specializationMapDepartment.json");
		echo json_encode($data);
	}
}