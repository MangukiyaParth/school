<?php

/**
 * 
 */
class Percentage extends CI_Controller
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
		$this->load->view('percentage/list', $data);
	}

	public function PercentageWiseSummary($start = 0) {
		
		$postData =  $this->input->post();
		$this->db->select('studentdetails.id, studentdetails.collegeregistrationnumber, CONCAT(IFNULL(studentdetails.firstname,"")," ",IFNULL(studentdetails.lastname,"")," ",IFNULL(studentdetails.fathername,"")) AS fullname, 
			(SELECT ROUND(IFNULL((SUM(((internalmarksobtained + externalsection1marks + externalsection2marks + practicalmarksobtained) * 100) / (internaltotalmarks + externaltotalmarks + practicalmaxmarks)) / COUNT(id)),0),2) AS per FROM `marksdetails` md WHERE md.studentid = marksdetails.studentid AND md.`year` = "'.$postData['year'].'" AND md.semester = 1) AS sem1,
			(SELECT ROUND(IFNULL((SUM(((internalmarksobtained + externalsection1marks + externalsection2marks + practicalmarksobtained) * 100) / (internaltotalmarks + externaltotalmarks + practicalmaxmarks)) / COUNT(id)),0),2) AS per FROM `marksdetails` md WHERE md.studentid = marksdetails.studentid AND md.`year` = "'.($postData['year'] + 1).'" AND md.semester = 2) AS sem2,
			(SELECT ROUND(IFNULL((SUM(((internalmarksobtained + externalsection1marks + externalsection2marks + practicalmarksobtained) * 100) / (internaltotalmarks + externaltotalmarks + practicalmaxmarks)) / COUNT(id)),0),2) AS per FROM `marksdetails` md WHERE md.studentid = marksdetails.studentid AND md.`year` = "'.($postData['year'] + 2).'" AND md.semester = 3) AS sem3,
			(SELECT ROUND(IFNULL((SUM(((internalmarksobtained + externalsection1marks + externalsection2marks + practicalmarksobtained) * 100) / (internaltotalmarks + externaltotalmarks + practicalmaxmarks)) / COUNT(id)),0),2) AS per FROM `marksdetails` md WHERE md.studentid = marksdetails.studentid AND md.`year` = "'.($postData['year'] + 3).'" AND md.semester = 4) AS sem4,
			(SELECT ROUND(IFNULL((SUM(((internalmarksobtained + externalsection1marks + externalsection2marks + practicalmarksobtained) * 100) / (internaltotalmarks + externaltotalmarks + practicalmaxmarks)) / COUNT(id)),0),2) AS per FROM `marksdetails` md WHERE md.studentid = marksdetails.studentid AND md.`year` = "'.($postData['year'] + 4).'" AND md.semester = 5) AS sem5,
			(SELECT ROUND(IFNULL((SUM(((internalmarksobtained + externalsection1marks + externalsection2marks + practicalmarksobtained) * 100) / (internaltotalmarks + externaltotalmarks + practicalmaxmarks)) / COUNT(id)),0),2) AS per FROM `marksdetails` md WHERE md.studentid = marksdetails.studentid AND md.`year` = "'.($postData['year'] + 5).'" AND md.semester = 6) AS sem6
			');
		if(isset($postData['specialisation']) && !empty($postData['specialisation'])) {
			$this->db->where('studentdetails.specialisation',$postData['specialisation']);
		}
		if(isset($postData['course']) && !empty($postData['course'])) {
			$this->db->where('studentdetails.stream',$postData['course']);
		}
		$this->db->join('studentdetails','studentdetails.id=marksdetails.studentid');
		$this->db->group_by('marksdetails.studentid');
		$getData = $this->db->get('marksdetails')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('percentage/percentage-wise-summary/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}
}