<?php

/**
 * 
 */
class Specialisation extends CI_Controller
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
		$this->load->view('specialisation/list', $data);
	}

	public function SpecialisationSummary($start = 0) {
		
		$postData =  $this->input->post();
		$this->db->select('studentdetails.specialisation, COUNT(marksdetails.id) AS enrolled, 
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
		$this->db->join('studentdetails','studentdetails.id=marksdetails.studentid');
		$this->db->group_by('studentdetails.specialisation');
		$getData = $this->db->get('marksdetails')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('specialisation/specialisation-summary/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}
}