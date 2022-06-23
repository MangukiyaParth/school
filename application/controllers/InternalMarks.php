<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * 
 */
class InternalMarks extends CI_Controller
{
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$data = [];
		$this->load->view('internalmarks/list', $data);
	}
	
	public function ListInternalMarks($start = 0) {
		
		$postData =  $this->input->post();
		$this->db->select('paperassignstaff.*, CONCAT(IFNULL(staff_details.firstname,"")," ",IFNULL(staff_details.lastname,"")) AS fullname');
		if(isset($postData['search']) && !empty($postData['search'])) {
			$this->db->where('paperassignstaff.semester',$postData['search']);
			$this->db->or_like('CONCAT(IFNULL(staff_details.firstname,"")," ",IFNULL(staff_details.lastname,""))',$postData['search']);
			$this->db->or_like('paperassignstaff.paper',$postData['search']);
		}
		$this->db->join('staff_details','staff_details.id=paperassignstaff.staffid');
		$getData = $this->db->get('paperassignstaff')->result_array();

		$perPage = 10;
        $this->session->set_userdata('start', $start);
        $data = pagiationData('internalmarks/ListInternalMarks/', count($getData) , $start, 3, $perPage); // seet pagination
		echo json_encode($data);
		

	}

	public function GetPapers() {
		$postData =  $this->input->post();
		$semester = $postData['semester'];
		$specialization = $postData['specialization'];
		$path = FCPATH;
		$path .= "media\bsc";

		if (!is_dir($path) && !file_exists($path)) {
			$oldmask = umask(0);
			if(!file_exists($path)){
				@mkdir($path, 0777, true); //  Warning: mkdir(): File exists
			}
			umask($oldmask);
		}

		$this->db->where('paperassignstaff.semester',$postData['semester']);
		$this->db->where('paperassignstaff.year',$postData['year']);
		$getData = $this->db->get('paperassignstaff')->result_array();

		$assigned_paper_list = [];
		if($getData)
		{
			$assignData = $getData[0];
			$paperlist = explode(",", $assignData['paper']);
			foreach($paperlist as $paperData)
			{
				$paperDetails = explode("~", $paperData);
				$paperspecialization = $paperDetails[1];
				if($paperspecialization == $specialization)
				{
					array_push($assigned_paper_list,  $paperDetails[0]);
				}
			}
		}

		$data = [];
		$data['assigned_paper_list'] = json_encode($assigned_paper_list);
		$data['paperData'] = file_get_contents($path."\sem".$semester.".json");
		echo json_encode($data);
	}

	public function GetPaperWiseStudents(){

		$postData =  $this->input->post();
		$stream = $postData['stream'];
		$course = $postData['course'];
		$semester = $postData['semester'];
		$exam_type = $postData['exam_type'];
		$specialization = $postData['specialization'];
		$paper_id = $postData['paper_id'];
		$year = $postData['year'];
		$json_exist = 0;
		$data = [];

		$jsonpath = FCPATH."media\internalMarks\\".$paper_id.'_'.$specialization.'_'.$year.'.json';
		$SelectedpaperTitle = "";
		if (file_exists($jsonpath)) 
		{
			$marksDetails = json_decode(file_get_contents($jsonpath),true);
			$SelectedpaperTitle = $marksDetails[0]['paperTitle']." (".$paper_id.")";
			$json_exist = 1;
		}
		else
		{

			$tmp_file_path = FCPATH."media\csv\\".$stream."-".$course."_sem".$semester."_".$year."_".$exam_type.".csv";
			list($csvColumnArray, $csvColumnCount, $csvColumnsString, $csvColumnsSeparator, $csvColumnsNameString) = parseCSVFile($tmp_file_path);
			
			$query_1_drop_tmp_table = "DROP TABLE IF EXISTS `internal_marks_tmp`";
			$this->db->query($query_1_drop_tmp_table);

			$create_tmp_table_qry = "CREATE TABLE `internal_marks_tmp` (`id` INT NOT NULL AUTO_INCREMENT, ".$csvColumnsString.", PRIMARY KEY (`id`))";
			$this->db->query($create_tmp_table_qry);
			
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
			
			$SelectedpaperCode = "";
			$Selectedspecialisation = "";
			
			$newpath = str_replace('\\', '/', $tmp_file_path);
			$query_2_import_to_temp_table = "
				LOAD DATA LOCAL INFILE '$newpath' IGNORE
				INTO TABLE internal_marks_tmp
				FIELDS TERMINATED BY ','
				ENCLOSED BY '\"'
				LINES TERMINATED BY '\n'
				IGNORE 1 ROWS
				(".$csvColumnsNameString.")
				SET ".$set_qry;

			$this->db->query($query_2_import_to_temp_table);
			
			/******************** Get Data from Json ********************/
			$path = FCPATH;
			$path .= "media\bsc";

			if (!is_dir($path) && !file_exists($path)) {
				$oldmask = umask(0);
				if(!file_exists($path)){
					@mkdir($path, 0777, true); //  Warning: mkdir(): File exists
				}
				umask($oldmask);
			}

			$jsonData = json_decode(file_get_contents($path."\sem".$semester.".json"),true);
			$course_codes = array_column($jsonData, 'specialisationCode');
			

			$query_select_students_column_data = "
			SELECT `COLUMN_NAME` 
			FROM `INFORMATION_SCHEMA`.`COLUMNS` 
			WHERE `TABLE_SCHEMA`='".DB_NAME."' 
			AND `TABLE_NAME`='internal_marks_tmp' AND `COLUMN_NAME` LIKE 'Code%'";
			$query_students_column_data = $this->db->query($query_select_students_column_data);	
			$column_data =  $query_students_column_data->result_array();
			
			$query_select_students_data = "SELECT *, CONCAT(IFNULL(LastName,''),' ',IFNULL(FirstName,''),' ',IFNULL(FatherName,''),' ',IFNULL(MotherName,'')) AS fullname FROM internal_marks_tmp ORDER BY CAST(RollNumber AS UNSIGNED)";
			$query = $this->db->query($query_select_students_data);	
			$getData =  $query->result_array();
			$i = 1;
			$marksDetails = [];
			foreach($getData as $data)
			{
				$key = array_search($data['Specialisation'], $course_codes);
				$crn = $data['College_Registration_No_'];
				$RollNumber = $data['RollNumber'];
				$fullname = $data['fullname'];
				$specialisation = $data['Specialisation'];
				
				$paperDetails = $jsonData[$key]['paperDetails'];
				$paper_codes = array_column($paperDetails, 'code');
				
				foreach($column_data as $column)
				{
					$columnname = $column['COLUMN_NAME'];
					$column_arr = explode("Code",$column['COLUMN_NAME']);
					$columnno = $column_arr[1];
					
					$paper_key = array_search($data[$columnname], $paper_codes);
					$paperCode = $paperDetails[$paper_key]['code'];
					if($data[$columnname] != "" && $paperCode == $paper_id)
					{
						$paperTitle = $paperDetails[$paper_key]['paperTitle'];
						$paperType = $paperDetails[$paper_key]['paperType'];
						$theoryInternalMax = $paperDetails[$paper_key]['theoryInternalMax'];
						$internalmarksobtained = $data['InternalC'.$columnno];					
						// if(is_numeric($data['InternalC'.$columnno]))
						// {
						// 	$internalmarksobtained = $data['InternalC'.$columnno];							
						// }
						// else
						// {
						// 	$internalmarksobtained = 0;
						// }
						
						$tmpmarksDetails = [];
						$tmpmarksDetails['crn'] = $crn;
						$tmpmarksDetails['RollNumber'] = $RollNumber;
						$tmpmarksDetails['fullname'] = $fullname;
						// $tmpmarksDetails['specialisation'] = $specialisation;
						// $tmpmarksDetails['paperCode'] = $paperCode;
						$tmpmarksDetails['paperTitle'] = $paperTitle;
						// $tmpmarksDetails['paperType'] = $paperType;
						// $tmpmarksDetails['internalmarks'] = $internalmarksobtained;
						$tmpmarksDetails['theoryInternalMax'] = $theoryInternalMax;

						if($internalmarksobtained == '' || $internalmarksobtained == 'ABS' || $internalmarksobtained == "AB" || $internalmarksobtained == "left")
						{
							$tmpmarksDetails['internalmarks'] = '';
						}
						else
						{
							$tmpmarksDetails['internalmarks'] = $internalmarksobtained;
						}
						$tmpmarksDetails['isabs'] = 0;
						array_push($marksDetails,$tmpmarksDetails);

						$SelectedpaperTitle = $paperTitle." (".$paper_id.")";
					}
				}	
			}

		}

		$resData = [];
		$resData['markDetails'] = $marksDetails;
		$resData['SelectedpaperTitle'] = $SelectedpaperTitle;
		$resData['SelectedpaperCode'] = $paper_id;
		$resData['specialisation'] = $specialization;
		$resData['year'] = $year;
		$resData['json_exist'] = $json_exist;
		$this->load->view('internalmarks/liststudents', $resData);

	}

	public function SaveInternalMarks()
	{
		$postData =  $this->input->post();
		$paperCode = $postData['papercode'];
		$specialisation = $postData['specialisation'];
		$year = $postData['year'];
		$marksData = json_decode($postData['marksData']);
		$jsonpath = FCPATH;
		$jsonpath .= "media\internalMarks\\";	
		$testjsonpath = $jsonpath.$paperCode.'_'.$specialisation.'_'.$year.'.json';
		if (file_exists($testjsonpath)) 
		{
			rename($testjsonpath, $jsonpath."backup\\".$paperCode.'_'.$specialisation.'_'.$year."_".date("Y_m_d_H_i_s").'.json');
		}
		file_put_contents($jsonpath.$paperCode.'_'.$specialisation.'_'.$year.'.json', json_encode($marksData, JSON_PRETTY_PRINT));
	}

	public function downloadExcel() {
		
		$postData =  $this->input->post();
		// $stream = $postData['stream'];
		// $course = $postData['course'];
		// $semester = $postData['semester'];
		// $exam_type = $postData['exam_type'];
		$specialization = $postData['specialisation'];
		$paper_id = $postData['papercode'];
		$year = $postData['year'];
		$data = [];

		$fileName = $paper_id.'_'.$specialization.'_'.$year.'.csv';
		$jsonpath = FCPATH."media\internalMarks\\".$paper_id.'_'.$specialization.'_'.$year.'.json';
		if (file_exists($jsonpath)) 
		{
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Roll No');
			$sheet->setCellValue('B1', 'Name');
			$sheet->setCellValue('C1', 'Internal Marks');
			$sheet->setCellValue('D1', 'Option');
			$rows = 2;
			$marksDetails = json_decode(file_get_contents($jsonpath),true);
			foreach($marksDetails as $marksData)
			{
				$abs = "P";
				if($marksData['isabs'] == 1)
				{
					$abs = "ABS";
				}
				$sheet->setCellValue('A' . $rows, $marksData['RollNumber']);
				$sheet->setCellValue('B' . $rows, $marksData['fullname']);
				$sheet->setCellValue('C' . $rows, $marksData['internalmarks']);
				$sheet->setCellValue('D' . $rows, $abs);
				$rows++;
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save("upload/".$fileName);
			// header("Content-Type: application/vnd.ms-excel");
			// redirect(base_url()."/upload/".$fileName);
			$data['filepath'] = base_url()."upload/".$fileName;
			print_r(json_encode($data));
		}           
    } 

	public function downloadPDf() {
		
		$postData =  $this->input->post();
		// $stream = $postData['stream'];
		// $course = $postData['course'];
		// $semester = $postData['semester'];
		// $exam_type = $postData['exam_type'];
		$specialization = $postData['specialisation'];
		$paper_id = $postData['papercode'];
		$year = $postData['year'];
		$data = [];

		$fileName = $paper_id.'_'.$specialization.'_'.$year.'.pdf';
		$jsonpath = FCPATH."media\internalMarks\\".$paper_id.'_'.$specialization.'_'.$year.'.json';
		if (file_exists($jsonpath)) 
		{
			$html = "
				<style>
					th,td {
						border: 1px solid #CCC;
						padding: 3px 5px;
					}
				</style>
				<table style='border: 1px solid; border-collapse: collapse; width: 100%;'>
					<thead>
						<tr>
							<th style='width: 10%'>Roll No</th>
							<th style='width: 70%'>Name</th>
							<th style='width: 10%'>Internal Marks</th>
							<th style='width: 10%'>Option</th>
						</tr>
					</thead>
			";
			$marksDetails = json_decode(file_get_contents($jsonpath),true);
			foreach($marksDetails as $marksData)
			{
				$abs = "P";
				if($marksData['isabs'] == 1)
				{
					$abs = "ABS";
				}
				$html.= "
					<tbody>
						<tr>
							<td>".$marksData['RollNumber']."</td>
							<td>".$marksData['fullname']."</td>
							<td>".$marksData['internalmarks']."</td>
							<td>".$abs."</td>
						</tr>
					</tbody>
				";
			}
			$html.= "</table>";
			$options = new \Dompdf\Options();
			$options->set('isRemoteEnabled', true);
			$dompdf = new \Dompdf\Dompdf($options);
			$dompdf->loadHtml( $html );
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$output = $dompdf->output();
			$filepath = FCPATH."upload\\".$fileName;
			file_put_contents($filepath, $output);
			unset($dompdf);
			
			$data['filepath'] = base_url()."upload/".$fileName;
			$data['html'] = $html;
			print_r(json_encode($data));
		}
		  
                      
    } 
}