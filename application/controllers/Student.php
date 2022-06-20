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
		$this->db->select('marksdetails.papercode, marksdetails.papertype, marksdetails.papertitle, COUNT(marksdetails.id) AS enrolled, 
			SUM(CASE WHEN (
				(internalmarksobtained = "NP" OR internalmarksobtained = "AB" OR internalmarksobtained = "ABS" OR internalmarksobtained = "") AND
				(externalsection1marks = "NP" OR externalsection1marks = "AB" OR externalsection1marks = "ABS" OR externalsection1marks = "") AND
				(externalsection2marks = "NP" OR externalsection2marks = "AB" OR externalsection2marks = "ABS" OR externalsection2marks = "") AND
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
		if(isset($postData['year']) && !empty($postData['year'])) {
			$this->db->where('marksdetails.year',$postData['year']);
		}
		if(isset($postData['semester']) && !empty($postData['semester'])) {
			$this->db->where('marksdetails.semester',$postData['semester']);
		}
		if(isset($postData['specialisation']) && !empty($postData['specialisation'])) {
			$this->db->where('studentdetails.specialisation',$postData['specialisation']);
		}
		$this->db->join('studentdetails','studentdetails.id=marksdetails.studentid','inner');
		$this->db->group_by('papercode');
		$getData = $this->db->get('marksdetails')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        // $data = pagiationData('student/PaperSummary/', count($getData) , $start, 3, $perPage); // seet pagination
		$data['listArr'] = $getData;
		echo json_encode($data);

	}
}