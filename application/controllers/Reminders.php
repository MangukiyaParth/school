<?php

/**
 * 
 */
class Reminders extends CI_Controller
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
		$this->load->view('reminders/list', $data);
	}
	
	public function addReminder($id) {
		if($id > 0)
		{
			$this->db->where('reminder.id',$id);
			$getData = $this->db->get('reminder')->result_array();
			$data['data'] = $getData[0];
		}
		else
		{
			$data = [];
		}

		$this->db->select('GROUP_CONCAT(collegeregistrationnumber) as collegeregistrationnumber');
		$studentData = $this->db->get('studentdetails')->row_array();
		$data['students'] = $studentData['collegeregistrationnumber'];

		$this->db->select('GROUP_CONCAT(username) as username');
		$staffData = $this->db->get('staff_details')->row_array();
		$data['staff'] = $staffData['username'];
		
		$getData = $this->db->get('department')->result_array();
		$data['department'] = $getData;

		$this->load->view('reminders/add', $data);
	}


	/** 
	 *	@getDashboardData 
	 *  get Dashboard counter
	 */ 
	public function saveReminder() {
		
		$postData =  $this->input->post();
		$specialization = '[]';
		if(isset($postData['specialization']) && !empty(($postData['specialization'])))
		{
			$specialization = json_encode($postData['specialization']);
		} 
		$department = '[]';
		if(isset($postData['department']) && !empty(($postData['department'])))
		{
			$department = json_encode($postData['department']);
		} 
		if($postData['id'] > 0)
		{
			$query_insert_data = "
				UPDATE reminder	SET 
				`title` = '".$postData['title']."', 
				`reminder_cnt` = '".$postData['reminder_cnt']."', 
				`description` = '".$postData['description']."', 
				`subject` = '".$postData['subject']."', 
				`valid_from` = '".$postData['valid_from']."', 
				`valid_to` = '".$postData['valid_to']."', 
				`reminder_for` = '".$postData['reminder_for']."', 
				`student_type` = '".$postData['student_type']."', 
				`specialization` = '".$specialization."', 
				`year` = '".$postData['year']."',
				`reminder_type` = '".$postData['reminder_type']."', 
				`student_list` = '".$postData['student_list']."', 
				`department` = '".$department."', 
				`staff_list` = '".$postData['staff_list']."',
				WHERE id = '".$postData['id']."'
			";
		}
		else
		{

			
			$query_insert_data = "
			INSERT INTO `reminder` (`title`, `reminder_cnt`, `description`, `subject`, `valid_from`, `valid_to`, `reminder_for`, `student_type`, `specialization`, `year`, `reminder_type`, `student_list`, `department`, `staff_list`) 
			VALUES 
			('".$postData['title']."','".$postData['reminder_cnt']."','".$postData['description']."','".$postData['subject']."','".$postData['valid_from']."','".$postData['valid_to']."','".$postData['reminder_for']."','".$postData['student_type']."','".$specialization."','".$postData['year']."', '".$postData['reminder_type']."', '".$postData['student_list']."','".$department."', '".$postData['staff_list']."')			
			";
			$this->db->query($query_insert_data);
			$remider_id = $this->db->insert_id();

			$valid_from = explode("/",$postData['valid_from']);
			$start_date = date_create($valid_from[2]."-".$valid_from[1]."-".$valid_from[0]);
			$sDate = strtotime($valid_from[2]."-".$valid_from[1]."-".$valid_from[0]);
			
			$valid_to = explode("/",$postData['valid_to']);
			$end_date = date_create($valid_to[2]."-".$valid_to[1]."-".$valid_to[0]);
			$diff=date_diff($start_date,$end_date);
			$day_diff = $diff->format("%a");
			$hour_diff = ($day_diff * 24);
			$hour_gap = $hour_diff / $postData['reminder_cnt'];

			if($postData['reminder_for'] == 1) // Students
			{
				if($postData['student_type'] == 1)
				{
					$this->db->select('GROUP_CONCAT(userID) AS user_id');
					$this->db->WHERE("roll_number > 0");
					if(isset($postData['year']))
					{
						$this->db->WHERE(" '".$postData['year']."' IN (course_name) ");
					}
					if(isset($postData['specialization']) && !empty(($postData['specialization'])))
					{
						$specialization = implode("' ,'",json_decode($specialization));
						$this->db->WHERE(" specialization IN ('".$specialization."') ");
					}
					$studentData = $this->db->get('student_details')->row_array();
					$user_id = $studentData['user_id'];
				}
				else
				{
					$this->db->select('GROUP_CONCAT(userID) AS user_id');
					$this->db->WHERE("roll_number > 0");
					if(isset($postData['student_list']) && !empty($postData['student_list']))
					{
						$studentList = explode(',',$postData['student_list']);
						$studentList = implode("' ,'",$studentList);
						$this->db->WHERE(" userID IN ('".$studentList."') ");
					}
					$studentData = $this->db->get('student_details')->row_array();
					$user_id = $studentData['user_id'];
				}
			}
			else //Staff
			{
				$this->db->select('GROUP_CONCAT(id) AS user_id');
				if(isset($postData['staff_list']) && !empty($postData['staff_list']))
				{
					$staffList = explode(',',$postData['staff_list']);
					$staffList = implode("' ,'",$staffList);
					$this->db->WHERE(" username IN ('".$staffList."') ");
				}
				if(isset($postData['department']) && !empty($postData['department']))
				{
					$department = implode("' ,'",json_decode($department));
					$this->db->WHERE(" department IN ('".$department."') ");
				}
				$studentData = $this->db->get('staff_details')->row_array();
				$user_id = $studentData['user_id'];
			}

			for($i = 1; $i <= $postData['reminder_cnt']; $i++)
			{
				$new_time = date("Y-m-d H:i:s", strtotime("+$hour_gap hours", $sDate));
				$inserQry = "INSERT INTO `reminder_manage`(`reminder_id`, `start_date`, `end_date`, `reminder_count`, `remind_time`, `status`, `reminder_for`, `user_id`) 
					VALUES 
					('$remider_id','".$postData['valid_from']."','".$postData['valid_to']."','".$postData['reminder_cnt']."','$new_time', 0,'".$postData['reminder_for']."','$user_id')";
				$this->db->query($inserQry);
				$sDate = strtotime($new_time);
				
			}
		}
		
		redirect('Reminders/list');
	}

	public function list()
	{
		$this->load->view('reminders/list');
	}

	public function ReminderList($start = 0) {
		
		$postData =  $this->input->post();
		if(isset($postData['search']) && !empty($postData['search'])) {
			$this->db->like('reminder.title',$postData['search']);
		}
		$getData = $this->db->get('reminder')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('student/getStudentData/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}


	public function DeleteReminder($id = 0) {
		$this->db->where('reminder.id', $id);
		$this->db->delete('reminder');
		$this->load->view('reminders/list', []);
	}
}