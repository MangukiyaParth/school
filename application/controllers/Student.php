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
		$this->db->select('marksdetails.papercode, marksdetails.papertitle, COUNT(marksdetails.id) AS enrolled, 
			SUM(CASE WHEN (
				(internaltotalmarks = "NP" OR internaltotalmarks = "AB" OR internaltotalmarks = "ABS" OR internaltotalmarks = "") AND
				(externaltotalmarks = "NP" OR externaltotalmarks = "AB" OR externaltotalmarks = "ABS" OR externaltotalmarks = "") AND
				(practicalmarksobtained = "NP" OR practicalmarksobtained = "AB" OR practicalmarksobtained = "ABS" OR practicalmarksobtained = "" ))
				THEN 1 ELSE 0 END)
			AS abscnt,  
			SUM(CASE WHEN (grade = "O+") THEN 1 ELSE 0 END) AS op, 
			SUM(CASE WHEN (grade = "O") THEN 1 ELSE 0 END) AS o, 
			SUM(CASE WHEN (grade = "A+") THEN 1 ELSE 0 END) AS ap, 
			SUM(CASE WHEN (grade = "A") THEN 1 ELSE 0 END) AS a, 
			SUM(CASE WHEN (grade = "B+") THEN 1 ELSE 0 END) AS bp, 
			SUM(CASE WHEN (grade = "B") THEN 1 ELSE 0 END) AS b, 
			SUM(CASE WHEN (grade = "C") THEN 1 ELSE 0 END) AS c, 
			SUM(CASE WHEN (grade = "P") THEN 1 ELSE 0 END) AS p, 
			SUM(CASE WHEN (grade = "F") THEN 1 ELSE 0 END) AS f');
		$this->db->join('studentdetails','studentdetails.id=marksdetails.studentid');
		$this->db->group_by('papercode');
		$getData = $this->db->get('marksdetails')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('student/paper-summary/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}
}