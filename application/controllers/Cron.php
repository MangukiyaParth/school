<?php

/**
 * 
 */
class Cron extends CI_Controller
{
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$data = [];

		$this->db->select("reminder.subject, reminder.description, reminder_manage.user_id, reminder_manage.reminder_for, reminder_manage.id");
		$this->db->WHERE("status", 0);
		$this->db->WHERE(" remind_time < '".date('Y-m-d H:i:s')."'");
		$this->db->join('reminder','reminder.id= reminder_manage.reminder_id');
		$getData = $this->db->get('reminder_manage')->result_array();

		foreach($getData as $reminder) {
			
			if($reminder['reminder_for'] == 1) // Students
			{
				$this->db->select("email_id");
				$studentList = explode(',',$reminder['user_id']);
				$studentList = implode("' ,'",$studentList);
				$this->db->WHERE(" userID IN ('".$studentList."') ");
				$getUserData = $this->db->get('student_details')->result_array();

				foreach($getUserData as $user) {
					// sendMailSMTP($user['email_id'], $reminder['subject'], $reminder['description'], $file_attach = "");
					sendMailSMTP($user['email_id'], $reminder['subject'], $reminder['description'], $file_attach = "");
				}
				$this->db->WHERE("id".$reminder['id']);
				$this->db->update('reminder_manage', array("status"=> 1));

			}
			else
			{
				$this->db->select("email");
				$studentList = explode(',',$reminder['user_id']);
				$studentList = implode("' ,'",$studentList);
				$this->db->WHERE(" id IN ('".$studentList."') ");
				$getUserData = $this->db->get('staff_details')->result_array();

				foreach($getUserData as $user) {
					// sendMailSMTP($user['email'], $reminder['subject'], $reminder['description'], $file_attach = "");
					// sendMailSMTP( 'sidhdhapara2412@gmail.com', $user['email']." ".$reminder['subject'], $reminder['description'], $file_attach = "");
					$data = sendMailUsingMailler('sidhdhapara2412@gmail.com', $user['email']." ".$reminder['subject'], $reminder['description'], $senderId = "", $rpl_to_email = '');
					echo "<pre>";
					// print_r($data);
				}
				// $this->db->WHERE("id".$reminder['id']);
				// $this->db->update('reminder_manage', array("status"=> 1));
			}
		}
	}
}