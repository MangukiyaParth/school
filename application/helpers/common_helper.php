<?php

function uploadCSVFile($input_file_name, $folder="CSV")
{
	$tmp_file_path = "";

	$resultArr = array();
	$resultArr['success'] = false;
	$resultArr['error'] = "";

	try {
		$ci = get_instance();

		$resultArr['success'] = false;
		$resultArr['error'] = "";


		$uploadFile = $input_file_name;
		$fileName = "";

		// file is not selected. so ignore it.
		if (isset($_FILES[$uploadFile]) && $_FILES[$uploadFile]['size'] == 0) {
			$resultArr['error'] = "You did not select a file to upload.";
			return array($tmp_file_path, $resultArr);
		}

		// print_r($_FILES[$uploadFile]); die();

		$fileinfo = pathinfo($_FILES[$uploadFile]['name']);
		// print_r($fileinfo); die();
		if (!in_array(strtoupper($fileinfo['extension']), array("CSV"))) {
			$resultArr['success'] = false;
			$resultArr['error'] = "Invalid file selection. only .CSV is allowed.";
		} else {
			/*  $tmp_file_path = $_FILES['import_file']['tmp_name']; */
			$config['max_size'] = '1024000';
			$config['allowed_types'] = '*'; // allow all type as some files are .ad, .mnt extension
			if (strpos($folder, 'application/views') !== false) {
				$config['upload_path'] = './' . $folder . '/';
			} else {
				$config['upload_path'] = './media/uploads/' . $folder . '/';
			}

			if ($fileName != "") {
				$config['file_name'] = $fileName;
			}

			//echo $config['upload_path'];
			if (!is_dir($config['upload_path'])) {
				mkdir($config['upload_path'], 0777, true);
			}

			$ci->load->library('upload', $config);
			$ci->upload->initialize($config);

			if (!$ci->upload->do_upload($uploadFile)) {
				$resultArr['success'] = false;
				$resultArr['error'] = $ci->upload->display_errors();
			} else {
				$resArr = $ci->upload->data();
				$resultArr['success'] = true;

				if (strpos($folder, 'application/views') !== false) {
					$resultArr['path'] = $folder . "/" . $resArr['file_name'];
				} else {
					$resultArr['path'] = "media/uploads/" . $folder . "/" . $resArr['file_name'];
				}

				$tmp_file_path = FCPATH.$resultArr['path'];
				/* chmod($resArr['full_path'], 0777); */
				if (is_file($tmp_file_path)) {
					chmod($resArr['full_path'], 0777);
				}

				$tmp_file_path = str_replace("\\", "/", $tmp_file_path);
			}
		}
	} catch (Exception $e) {
		print_r($e);
		die();
	}

	return array($tmp_file_path, $resultArr);
}

function get_formatted_error()
{
	$msg = "";
	$ci = get_instance();
	$error = $ci->db->error();
	if (is_array($error)) {
		$error_code = $error["code"];
		$error_msg = $error["message"];
		if ($error_code > 0) {
			$msg = "Error Code: ".$error_code. "Error Message: ".$error_msg;
		}
	}
	return $msg;
}

function parseCSVFile($csv_file_path)
{
	global $cron_status, $cron_message;

	$csvColumnCount = 0;
	$csvColumnsString = "";
	$csvColumnsNameString = "";
	$csvColumnArray = array();
	$csvColumnsSeparator = "";

	if (!file_exists($csv_file_path)) {
		$cron_status = "ERROR";
		$cron_message = "System can not process CSV. File does not exist";
		return array($csvColumnArray, $csvColumnCount, $csvColumnsString, $csvColumnsSeparator, $csvColumnsNameString);
	}

	$file_handle = fopen($csv_file_path, "r");
	$first_line = fgets($file_handle);
	fclose($file_handle);

	if (strpos($first_line, ",") !== false) {
		$csvColumnsSeparator = ",";
	} elseif (strpos($first_line, "|") !== false) {
		$csvColumnsSeparator = "|";
	}

	if ($csvColumnsSeparator == null || $csvColumnsSeparator == "") {
		$cron_status = "ERROR";
		$cron_message = "CSV file must be comman separated or Pipe separated.";
		return array($csvColumnArray, $csvColumnCount, $csvColumnsString, $csvColumnsSeparator, $csvColumnsNameString);
	}

	$csvColumnArray = explode($csvColumnsSeparator, $first_line);
	$csvColumnCount = count($csvColumnArray);

	for ($i=0; $i <= $csvColumnCount; $i++) {
		if(isset($csvColumnArray[$i]) && !empty($csvColumnArray[$i]))
		{
			if ($csvColumnsString != "") {
				$csvColumnsString .= ",";
				$csvColumnsNameString .= ",";
			}
			// $csvColumnsString .= "@Col".$i;
			$csvColumnsString .= "`".trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $csvColumnArray[$i]))."` TEXT";
			$csvColumnsNameString .= "`".trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $csvColumnArray[$i]))."`";
		}
	}
	return array($csvColumnArray, $csvColumnCount, trim($csvColumnsString), $csvColumnsSeparator, trim($csvColumnsNameString));
}

function pagiationData($str, $num, $start, $segment, $perpage = 20) {

    $CI = & get_instance();
    $config['base_url'] = site_url('/') . $str;
    $config['total_rows'] = $num;
    if ($perpage) {
        $config['per_page'] = $perpage;
    } else {
        $config['per_page'] = $CI->session->userdata('per_page') ? $CI->session->userdata('per_page') : $perpage;
    }
    $config["reuse_query_string"] = TRUE;
    $config['uri_segment'] = $segment;
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['cur_page'] = $start;
    $config['first_tag_open'] = '<li class="first paginate_button page-item">';
    $config['first_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="paginate_button page-item">';
    $config['next_tag_close'] = '</li>';
    $config['num_tag_open'] = '<li class="paginate_button page-item">';
    $config['num_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="last paginate_button page-item">';
    $config['last_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="paginate_button page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="paginate_button page-item active"><a href="javascript:;">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_links'] = 1;

    $CI->pagination->initialize($config);
    $query = $CI->db->last_query() . " LIMIT " . $start . " , " . $config['per_page'];
    //print_r($query);die;
    $res = $CI->db->query($query);
    $data['listArr'] = $res->result_array();
    $data['data'] = $res->result_array();
    $data['num'] = $res->num_rows();
    $data['Total'] = $num;
    $data['start'] = $start;
    $data['links'] = $CI->pagination->create_links();
    $ofpage = ($start + $data['num']);
    $data['pageinfo'] = 'Showing ' . $start . ' to ' . $ofpage . ' of ' . $data['Total'] . ' entries';
    return $data;
}

function sendMailSMTP($toEmailId, $subject, $mail_body, $file_attach = "")
{
    $ci = & get_instance();
    $ci->load->library('email');
    $ci->load->library('parser');
    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'ssl://smtp.gmail.com';
    $config['smtp_port']    = '465';
    //$config['smtp_timeout'] = '7';
    $config['smtp_user']    = 'test@gmail.com';
    $config['smtp_pass']    = 'Test';
 
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['mailtype']     = 'html';
    //$config['validation']   = TRUE;
    $config['wordwrap'] = true;

    $ci->email->initialize($config);
    $from_email = "school@gmail.com";
    
    $ci->email->from($from_email, 'School');
    $ci->email->to($toEmailId);
    //$ci->email->cc('');
    
    $ci->email->subject($subject);
    
    //$msg = $this->load->view($htmltemplate,$data,TRUE);
    //$msg = stripslashes($mail_body);
    $ci->email->message($mail_body);
    if (isset($file_attach) && !empty($file_attach)) {
        $ci->email->attach($file_attach);
    }
    $ci->email->send();
    echo  $ci->email->print_debugger();
    /* if ($ci->email->send()) {
        return 1;
    } else {
        return 0;
    } */
}

function sendMailUsingMailler($emailId, $subject, $mail_body, $senderId = "", $rpl_to_email = '', $cc) {
    $C = & get_instance();
    
    $C->load->library('PHPMailer_Lib');

    $mail = $C->phpmailer_lib->load();
    $mail->IsSMTP();
    $mail->IsHTML(true); // send as HTML	
    $mail->SMTPAuth = true;   
	$mail->SMTPKeepAlive = true;   
    $mail->SMTPDebug = 3;   // Enable verbose debug output               // enable SMTP authentication
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	// $mail->Host = "ssl://smtp.gmail.com"; 
    $mail->Port = 465;
    $mail->Username = "test@gmail.com";
    $mail->Password = "test";
    
    $mail->setFrom('test@gmail.com', 'Test');
    $mail->addAddress($emailId);

    $mail->addCC($cc);
    //$mail->addBCC('bcc@example.com');
    $mail->Subject = $subject;
    $mail->Body = $mail_body;
    $response = $mail->Send();
	echo  $C->email->print_debugger();  
    if (!$response) {
        return 'Mailer Error: ' . $mail->ErrorInfo;
    }
    
    return $response;
}

function sendEmail($email,$subject,$message,$cc = "",$file = "")
{
	$C = & get_instance();
	$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://smtp.googlemail.com',
		'smtp_port' => 465,
		'smtp_user' => 'abc@gmail.com', 
		'smtp_pass' => 'passwrd', 
		'mailtype' => 'html',
		'charset' => 'iso-8859-1',
		'wordwrap' => TRUE
	);


	$C->load->library('email', $config);
	$C->email->set_newline("\r\n");
	$C->email->from('abc@gmail.com');
	$C->email->to($email);
	$C->email->cc($cc);
	$C->email->subject($subject);
	$C->email->message($message);
	$C->email->attach($file);
	if($C->email->send())
	{
		echo 'Email send.';
	}
	else
	{
		show_error($C->email->print_debugger());
	}

}
?>