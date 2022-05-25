<?php

/**
 * 
 */
class Student extends CI_Controller
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
		$this->load->view('student/list', $data);
	}


	/** 
	 *	@getDashboardData 
	 *  get Dashboard counter
	 */ 
	public function getStudentData($start = 0) {
		
		$postData =  $this->input->post();

		// $this->db->select('fieldname1,fieldname2');
		// if(isset($postData['search']) && !empty($postData['search'])) {
		// 	$this->db->where('fieldname',$postData['search']);
		// }

		// if(isset($postData['search']) && !empty($postData['search'])) {
		// 	$this->db->where('table1.fieldname',$postData['search']);
		// 	$this->db->like('table1.fieldname',$postData['search']);
		// }

		// if(isset($postData['search']) && !empty($postData['search'])) {
		// 	$this->db->where('',$postData['search']);
		// }
		
		// $this->db->join('table','table1.id=tabel2.id');
		$getData = $this->db->get('studentdetails')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('student/getStudentData/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}
	public function PaperSummary($start = 0) {
		
		$postData =  $this->input->post();
		$this->db->select('marksdetails.papercode, marksdetails.papertitle, COUNT(marksdetails.id) AS enrolled');
		$this->db->join('studentdetails','studentdetails.id=marksdetails.studentid');
		$this->db->group_by('papercode');
		$getData = $this->db->get('marksdetails')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('student/paper-summary/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}
}