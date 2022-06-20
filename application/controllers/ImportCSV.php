<?php

/**
 * 
 */
class ImportCSV extends CI_Controller
{
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$index_data = array();
		$index_data['load_page'] = "importCSV";
		$index_data['sub_load_page'] = "";
		$index_data['headerTitle'] = "ImportCSV";
		$index_data['pageTitle']   = "ImportCSV";
		$index_data['contentPage'] = "import_csv/list";
		$this->load->view('includes/layout',$index_data);
		
 	}

 	/*
		@importStudentData
	*/
	public function importStudentData() {

		if($this->input->post()) {
			list($tmp_file_path, $resultArr) = uploadCSVFile("import_students_file");

			$errorMessage = array();
			$successMessage = "";
			if($resultArr['success'] && $this->input->post('btn_import_students_file') !== null) {
				
				$tmp_file_path = str_replace("\\", "/", $tmp_file_path);
				$filename=$_FILES['import_students_file']['name'];
				$filename_details = explode('_', $filename);
				$semester = str_replace('sem', '', $filename_details[1]);
				$year = $filename_details[2];
				
				list($csvColumnArray, $csvColumnCount, $csvColumnsString, $csvColumnsSeparator, $csvColumnsNameString) = parseCSVFile($tmp_file_path);


				$query_1_drop_tmp_table = "DROP TABLE `students_tmp`";
				$this->db->query($query_1_drop_tmp_table);

				$create_tmp_table_qry = "CREATE TABLE `students_tmp` (`id` INT NOT NULL AUTO_INCREMENT, ".$csvColumnsString.", PRIMARY KEY (`id`))";
				$this->db->query($create_tmp_table_qry);
				$errorMessage[] = "create_tmp_table_qry";
				$errorMessage[] = get_formatted_error();
				/*echo "<pre>";

				print_r($csvColumnArray);
				print_r($csvColumnCount);
				print_r($csvColumnsString);
				print_r($csvColumnsSeparator);
				die;*/

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
					INTO TABLE students_tmp
					FIELDS TERMINATED BY ','
					ENCLOSED BY '\"'
					LINES TERMINATED BY '\n'
					IGNORE 1 ROWS
					(".$csvColumnsNameString.")
					SET ".$set_qry;

				$query_3_import_newly_students_data = "
					INSERT IGNORE INTO `studentdetails` ( `admissionyear`, `collegeregistrationnumber`, `prn`, `lastname`, `firstname`, `fathername`, `mothername`, `stream`, `course`, `specialisation`)
					SELECT students_tmp.Year,students_tmp.College_Registration_No_,replace(students_tmp.PRN,\"'\",\"\"),students_tmp.LastName,students_tmp.FirstName,students_tmp.FatherName,students_tmp.MotherName,TRIM(SUBSTRING_INDEX(students_tmp.course, '-', -1)),students_tmp.course,students_tmp.Specialisation
					FROM students_tmp WHERE students_tmp.College_Registration_No_ <> '' AND students_tmp.College_Registration_No_ NOT IN (SELECT students.collegeregistrationnumber FROM studentdetails students)
				";

				$query_4_update_students_data = "
					UPDATE studentdetails students
					INNER JOIN students_tmp
					ON students.collegeregistrationnumber = students_tmp.College_Registration_No_ AND students_tmp.College_Registration_No_ <> ''
					SET 
					students.collegeregistrationnumber = students_tmp.College_Registration_No_, 
					students.prn = replace(students_tmp.PRN,\"'\",\"\"), 
					students.lastname = students_tmp.LastName, 
					students.firstname = students_tmp.FirstName, 
					students.fathername = students_tmp.FatherName, 
					students.mothername = students_tmp.MotherName, 
					students.stream = TRIM(SUBSTRING_INDEX(students_tmp.course, '-', -1)), 
					students.course = students_tmp.course, 
					students.specialisation = students_tmp.Specialisation
					
				";

				$this->db->query($query_2_import_to_temp_table);
				$errorMessage[] = $this->db->affected_rows(). " total rows processed";
				$errorMessage[] = get_formatted_error();

				$this->db->query($query_3_import_newly_students_data);
				$errorMessage[] = $this->db->affected_rows(). " insert new rows processed";
				$errorMessage[] = get_formatted_error();

				$this->db->query($query_4_update_students_data);
				$errorMessage[] = $this->db->affected_rows(). " update rows processed";
				$errorMessage[] = get_formatted_error();

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
				WHERE `TABLE_SCHEMA`='db_hrishi' 
				AND `TABLE_NAME`='students_tmp' AND `COLUMN_NAME` LIKE 'Code%'";
				$query_students_column_data = $this->db->query($query_select_students_column_data);	
				$column_data =  $query_students_column_data->result_array();
				
				$query_select_students_data = "
				SELECT students_tmp.*, students.id AS student_id FROM students_tmp
				INNER JOIN studentdetails students	ON students.collegeregistrationnumber = students_tmp.College_Registration_No_
				WHERE students_tmp.College_Registration_No_ <> ''";
				$query = $this->db->query($query_select_students_data);	
				$getData =  $query->result_array();
				$i = 1;
				foreach($getData as $data)
				{
					$key = array_search($data['Specialisation'], $course_codes);

					$paperDetails = $jsonData[$key]['paperDetails'];
					$paper_codes = array_column($paperDetails, 'code');
					
					foreach($column_data as $column)
					{
						$columnname = $column['COLUMN_NAME'];
						$column_arr = explode("Code",$column['COLUMN_NAME']);
						$columnno = $column_arr[1];
						
						$paper_key = array_search($data[$columnname], $paper_codes);
						if($data[$columnname] != "")
						{
							$credits = $paperDetails[$paper_key]['credits'];
							$isGrade = ($paperDetails[$paper_key]['isGrade'] == 'Yes') ? 1 : 0;
							$paperCode = $paperDetails[$paper_key]['code'];
							$paperTitle = $paperDetails[$paper_key]['paperTitle'];
							$paperType = $paperDetails[$paper_key]['paperType'];
							$isElective = ($paperDetails[$paper_key]['isElective'] == 'Yes') ? 1 : 0;
							$theoryInternalPassing = $paperDetails[$paper_key]['theoryInternalPassing'];
							$internalmarksobtained = $data['InternalC'.$columnno];
							$theoryExternalPassing = $paperDetails[$paper_key]['theoryExternalPassing'];
							$theoryInternalMax = $paperDetails[$paper_key]['theoryInternalMax'];
							$externalsection1marks = $data['ExternalSection1C'.$columnno];
							$externalsection2marks = $data['ExternalSection2C'.$columnno];
							$externaltotalmarks = $data['ExternalTotalC'.$columnno];
							$externalMaxMarks = (int)$paperDetails[$paper_key]['theoryExternalSection1Max'] + (int)$paperDetails[$paper_key]['theoryExternalSection2Max'];
							$practicalmarksobtained = $data['PracticalMarksC'.$columnno];
							$practicalMaxMarks = $paperDetails[$paper_key]['practicalMaxMarks'];
							$gracemarks = $data['GraceC'.$columnno];
							$attempt = $data['Attempt'.$columnno];
							$remarks = $data['Remarks'.$columnno];
							// $year = $data['Year'];
							// $semester = $data['Semester'];

							// $total_marks = (int)$internalmarksobtained + (int)$externalsection1marks + (int)$externalsection2marks + (int)$practicalmarksobtained;
							$total_marks = (int)$internalmarksobtained + (int)$externaltotalmarks + (int)$practicalmarksobtained;
							if($total_marks >= 90)
							{
								$grade = "O+";
							}
							else if($total_marks >= 80)
							{
								$grade = "O";
							}
							else if($total_marks >= 70)
							{
								$grade = "A+";
							}
							else if($total_marks >= 60)
							{
								$grade = "A";
							}
							else if($total_marks >= 55)
							{
								$grade = "B+";
							}
							else if($total_marks >= 50)
							{
								$grade = "B";
							}
							else if($total_marks >= 45)
							{
								$grade = "C";
							}
							else if($total_marks >= 40)
							{
								$grade = "P";
							}
							else 
							{
								$grade = "F";
							}



							$insert_marks_qry = "INSERT IGNORE INTO `marksdetails`(`studentid`, `examid`, `seatnumber`, `credit`, `isgrade`, `papercode`, `papertitle`, `papertype`, `iselective`, `internalpassingmarks`, `internalmarksobtained`, `externalpassingmarks`, 
								`internaltotalmarks`, `externalsection1marks`, `externalsection2marks`, `externaltotalmarks`, `practicalmarksobtained`, `practicalmaxmarks`, `gracemarks`, `paperresult`, `gp`, `grade`, `attempt`, `remarks`, 
								`externalmaxmarks`, `RetryCount`, `semester`, `year`, `collegeregistrationnumber`) 
								VALUES 
								('".$data['student_id']."','','".$data['SeatNumber']."','".$credits."',".$isGrade.",'".$paperCode."','".$paperTitle."','".$paperType."',".$isElective.",'".$theoryInternalPassing."','".$internalmarksobtained."','".$theoryExternalPassing."',
								'".$theoryInternalMax."','".$externalsection1marks."','$externalsection2marks','".$externaltotalmarks."','$practicalmarksobtained','".$practicalMaxMarks."','".$gracemarks."','','','".$grade."','".$attempt."','".$remarks."',
								'".$externalMaxMarks."','','".$semester."','".$year."','".$data['College_Registration_No_']."')
								ON DUPLICATE KEY UPDATE 
								`studentid` = '".$data['student_id']."', 
								`examid` = '', 
								`seatnumber` = '".$data['SeatNumber']."', 
								`credit` = '".$credits."', 
								`isgrade` = ".$isGrade.", 
								`papercode` = '".$paperCode."', 
								`papertitle` = '".$paperTitle."', 
								`papertype` = '".$paperType."', 
								`iselective` = ".$isElective.", 
								`internalpassingmarks` = '".$theoryInternalPassing."', 
								`internalmarksobtained` = '".$internalmarksobtained."', 
								`externalpassingmarks` = '".$theoryExternalPassing."', 							
								`internaltotalmarks` = '".$theoryInternalMax."', 
								`externalsection1marks` = '".$externalsection1marks."', 
								`externalsection2marks` = '$externalsection2marks', 
								`externaltotalmarks` = '".$externaltotalmarks."', 
								`practicalmarksobtained` = '$practicalmarksobtained', 
								`practicalmaxmarks` = '".$practicalMaxMarks."', 
								`gracemarks` = '".$gracemarks."', 
								`paperresult` = '', 
								`gp` = '', 
								`grade` = '".$grade."', 
								`attempt` = '".$attempt."', 
								`remarks` = '".$remarks."',
								`externalmaxmarks` = '".$externalMaxMarks."', 
								`RetryCount` = '', 
								`semester` = '".$semester."', 
								`year` = '".$year."',
								`collegeregistrationnumber` = '".$data['College_Registration_No_']."'";

							$this->db->query($insert_marks_qry);
						}
					}	
				}			
				/************************************/

				
			} else {
				$errorMessage = $resultArr['error'];
			}
			$layout_data = array();
			$layout_data['load_page'] = "importCSV";
			$layout_data['sub_load_page'] = "";
			$layout_data['errorMessage'] = $errorMessage;
			$layout_data['successMessage'] = $successMessage;
			$layout_data['headerTitle'] = "ImportCSV";
			$layout_data['pageTitle']   = "ImportCSV";
			$layout_data['contentPage'] = "import_csv/list";
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