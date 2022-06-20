<?php

/**
 * 
 */
class Certification extends CI_Controller
{
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$index_data = array();
		$index_data['load_page'] = "certification";
		$index_data['sub_load_page'] = "";
		$index_data['headerTitle'] = "Certification";
		$index_data['pageTitle']   = "Certification";
		$index_data['contentPage'] = "certification/list";
		$this->load->view('includes/layout',$index_data);
		
 	}

	public function importCertificationData() {

		if($this->input->post()) {
			list($tmp_file_path, $resultArr) = uploadCSVFile("import_certification_file");

			$errorMessage = array();
			$successMessage = "";
			if($resultArr['success'] && $this->input->post('btn_import_certification_file') !== null) {
				
				$tmp_file_path = str_replace("\\", "/", $tmp_file_path);				
				list($csvColumnArray, $csvColumnCount, $csvColumnsString, $csvColumnsSeparator, $csvColumnsNameString) = parseCSVFile($tmp_file_path);


				$query_1_drop_tmp_table = "TRUNCATE `certification_tmp`";
				$this->db->query($query_1_drop_tmp_table);

				$set_qry = "";
				for ($i=1; $i <= $csvColumnCount; $i++) {
					if(isset($csvColumnArray[$i]) && !empty($csvColumnArray[$i]))
					{
						if ($set_qry != "") {
							$set_qry .= ",";
						}
						$set_qry .= $csvColumnArray[$i]." = ".$csvColumnArray[$i];
					}
				}
				
				
				$query_2_import_to_temp_table = "
					LOAD DATA LOCAL INFILE '$tmp_file_path' IGNORE
					INTO TABLE certification_tmp
					FIELDS TERMINATED BY ','
					ENCLOSED BY '\"'
					LINES TERMINATED BY '\n'
					IGNORE 1 LINES
					(student_name, gender, grade, percent, date, course_name, subject, no_of_hours, certificate_template_id)";
				$this->db->query($query_2_import_to_temp_table);
				$errorMessage[] = $this->db->affected_rows(). " total rows processed";
				$errorMessage[] = get_formatted_error();

				$query_select_students_data = "SELECT * FROM certification_tmp";
				$query = $this->db->query($query_select_students_data);	
				$getData =  $query->result_array();
				//print_r($getData);
				$i = 1;
				foreach($getData as $data)
				{
					$dompdf = new \Dompdf\Dompdf();
					$dompdf->loadHtml( $this->load->view( 'certification/Generatecertification' , $data, true ) );
					$dompdf->setPaper('A4', 'landscape');
					$dompdf->render();
					//$dompdf->stream( $data['student_name']."-".$data['course_name'] );
					$output = $dompdf->output();
					$filepath = FCPATH."media\certificate\\".$data['student_name']."-".$data['course_name'].".pdf";
    				file_put_contents($filepath, $output);
					//$data = sendEmail('sidhdhapara2412@gmail.com', "Certificate", "", "cc@gmail.com", $filepath);
					unset($dompdf);
				}
				
			} else {
				$errorMessage = $resultArr['error'];
			}
			$layout_data = array();
			$layout_data['load_page'] = "certification";
			$layout_data['sub_load_page'] = "";
			$layout_data['errorMessage'] = $errorMessage;
			$layout_data['successMessage'] = $successMessage;
			$layout_data['headerTitle'] = "Certification";
			$layout_data['pageTitle']   = "Certification";
			$layout_data['contentPage'] = "certification/list";
			$this->load->view('includes/layout',$layout_data);
		} else { 
			redirect('importCSV');
		}
	}

	/** 
	 *	@getDashboardData 
	 *  get Dashboard counter
	 */ 
	public function getCSVData() {

		$response = array();
		
		echo json_encode($response);
	}

	/*
		@read_json
	*/
	public function read_json() {

		$path = FCPATH;
        $path .= "media/bsc/";

        if (!is_dir($path) && !file_exists($path)) {
            $oldmask = umask(0);
            if(!file_exists($path)){
                @mkdir($path, 0777, true); //  Warning: mkdir(): File exists
            }
            umask($oldmask);
        }

        $getData = json_decode(file_get_contents($path."sem6.json"),true);
		return $getData;
        /*echo "<pre>";
        print_r($getData);*/

        
	}

}