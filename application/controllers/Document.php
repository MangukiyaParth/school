<?php

/**
 * 
 */
class Document extends CI_Controller
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
		$this->load->view('document/list', $data);
	}


	/** 
	 *	@getDashboardData 
	 *  get Dashboard counter
	 */ 
	public function UserWiseDocument($start = 0) {
		
		$postData =  $this->input->post();
		$userid=18;
		$Department="HD";
		$this->db->select("documents.*, DATE_FORMAT(documents.LastModified, '%d/%m/%Y %H:%i:%s') AS LastModified_format");
		$this->db->where("(FIND_IN_SET('".$userid."', TaggedUser) OR FIND_IN_SET('".$Department."', DepartmentId))");
		if(isset($postData['search']) && !empty($postData['search'])) {
			$this->db->like('DocumentTitle',$postData['search']);
		}
		if(isset($postData['daterange']) && !empty($postData['daterange'])) {
			$daterange = explode('-', $postData['daterange']);
			$startDateformat = explode('/',trim($daterange[0]));
			$startDate = $startDateformat[2].'-'.$startDateformat[0].'-'.$startDateformat[1];
			
			$endDateformat = explode('/', trim($daterange[1]));
			$endDate = $endDateformat[2].'-'.$endDateformat[0].'-'.$endDateformat[1];
			$this->db->where("DATE_FORMAT(LastModified, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate'");
		}
		$this->db->order_by('LastModified','desc');
		// $this->db->get('documents');
		// echo $this->db->last_query(); die;
		$getData = $this->db->get('documents')->result_array();
		$perPage = 25;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('document/UserWiseDocument/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);

	}
	public function documentDetails($id = 0) {
		$this->db->select("documents.*, DATE_FORMAT(documents.LastModified, '%d/%m/%Y %H:%i:%s') AS LastModified_format, 
			DATE_FORMAT(documents.Received, '%d/%m/%Y') AS Received_format,
			DATE_FORMAT(documents.Forwarded, '%d/%m/%Y') AS Forwarded_format,
			DATE_FORMAT(documents.Deadline, '%d/%m/%Y') AS Deadline_format
		");
		$this->db->where('id',$id);
		$getData['data'] = $this->db->get('documents')->result_array();
		$this->load->view('document/details', $getData);

	}
}