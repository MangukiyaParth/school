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

	for ($i=1; $i <= $csvColumnCount; $i++) {
		if(isset($csvColumnArray[$i]) && !empty($csvColumnArray[$i]))
		{
			if ($csvColumnsString != "") {
				$csvColumnsString .= ",";
				$csvColumnsNameString .= ",";
			}
			// $csvColumnsString .= "@Col".$i;
			$csvColumnsString .= "`".trim($csvColumnArray[$i])."` TEXT";
			$csvColumnsNameString .= "`".trim($csvColumnArray[$i])."`";
		}
	}
	return array($csvColumnArray, $csvColumnCount, $csvColumnsString, $csvColumnsSeparator, $csvColumnsNameString);
}

?>