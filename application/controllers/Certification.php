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
		// require_once FCPATH. 'vendor/tecnickcom/tcpdf/tcpdf.php';
		ini_set('MAX_EXECUTION_TIME', '-1'); 
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
					(@col1,@col2,@col3,@col4,@col5,@col6,@col7,@col8,@col9)
					set
					student_name = @col1, 
					gender = @col2, 
					grade = @col3, 
					percent = @col4, 
					date = @col5, 
					course_name = @col6, 
					subject = @col7, 
					no_of_hours = @col8, 
					certificate_template_id = @col9
					";
				$this->db->query($query_2_import_to_temp_table);
				$errorMessage[] = $this->db->affected_rows(). " total rows processed";
				$errorMessage[] = get_formatted_error();

				$max_limit = ceil($this->db->affected_rows() / 50);
				if($max_limit == 0)
				{
					$max_limit = 1;
				}
				// for ($i=0; $i < $max_limit; $i++) { 
				{
					$start = $i * 50;
					$limit = 50;
					// $query_select_students_data = "SELECT * FROM certification_tmp LIMIT $start, $limit";
					$query_select_students_data = "SELECT * FROM certification_tmp";
					$query = $this->db->query($query_select_students_data);	
					$getData =  $query->result_array();
					//print_r($getData);
					$i = 1;
					foreach($getData as $data)
					{
						//foreach(array_chunk($getData, 50) as $data)
						{
							if($data['student_name'] != '')
							{
								$options = new \Dompdf\Options();
								$options->set('isRemoteEnabled', true);
								$dompdf = new \Dompdf\Dompdf($options);
								$html = $this->load->view( 'certification/Generatecertification' , $data, true );
								$dompdf->loadHtml( $html );
								$dompdf->setPaper('A4', 'landscape');
								$dompdf->render();
								//$dompdf->stream( $data['student_name']."-".$data['course_name'] );
								$output = $dompdf->output();
								$filepath = FCPATH."media\certificate\\".$data['student_name']."-".$data['course_name'].".pdf";
								file_put_contents($filepath, $output);
								//$data = sendEmail('sidhdhapara2412@gmail.com', "Certificate", "", "cc@gmail.com", $filepath);
								unset($dompdf);

								
								// // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
								// $pdf = new TCPDF('L', 'mm', array(  595,  842), true, 'UTF-8', false);
								// // remove default header/footer
								// $pdf->setPrintHeader(false);
								// $pdf->setPrintFooter(false);
								// // set default monospaced font
								// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
								// // set margins
								// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
								// // set auto page breaks
								// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
								// //$pdf->SetFont('times', '', 10);// Font Name, Style, Size, Other file name
								// // $pdf->SetFont('times', '', 12);
								// // Add a page
								// $pdf->AddPage();
								// // set text shadow effect
								// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

								// $html = $this->load->view( 'certification/Generatecertification' , $data, true );

								// // $pdf->AddPage('L', 'A4');
								// // $pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');

								// // output the HTML content
								// $pdf->writeHTML($html, true, false, true, false, '');
								// $pdf->lastPage();
								// $filepath = FCPATH."media\certificate\\".$data['student_name']."-".$data['course_name'].".pdf";
								// $output = $pdf->Output($filepath,'F');
								// //file_put_contents($filepath, $output);
							}
						}
					}
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
			redirect('Certification');
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