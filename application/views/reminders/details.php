<html>
<head>
	<title>Document Details</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
		.container-fluid {
			padding: 15px 10%;
		}
		.table td{
			padding-bottom: 25px;
		}
		.mb25 {
			margin-bottom: 25px;
		}
	</style>
	<?php 
		$docData = $data[0]; 
		$MessageHeader = json_decode($docData['MessageHeader']);
		// var_dump(json_decode($MessageHeader));
		$from = $MessageHeader->From;
		$to = $MessageHeader->To;
		$subject = $MessageHeader->Subject;
	?>
</head>

<body>
	<section class="container-fluid">
		<div class="data-div">
			<button class="btn btn-primary mb25" onclick="history.back()"><- Back</button>
			<table class="table table-borderless" id="paper_wise_table">
				<tbody>
					<tr>
						<th width="30%">Document Type</th>
						<th width="30%">Document Number</th>
						<th width="40%">Document Title</th>
					</tr>
					<tr>
						<td><?php echo $docData['DocumentType'] ?></td>
						<td><?php echo $docData['UniqueName'] ?></td>
						<td><?php echo $docData['DocumentTitle'] ?></td>
					</tr>
					<tr>
						<th>From</th>
						<th>To</th>
						<th>Subject</th>
					</tr>
					<tr>
						<td><?php echo $from ?></td>
						<td><?php echo $to ?></td>
						<td><?php echo $subject ?></td>
					</tr>
					<tr>
						<th>Received On</th>
						<th>Forwarded On</th>
						<th>Deadline</th>
					</tr>
					<tr>
						<td><?php echo $docData['Received'] ?></td>
						<td><?php echo $docData['Forwarded'] ?></td>
						<td><?php echo $docData['Deadline'] ?></td>
					</tr>
					<tr>
						<th>Department</th>
						<th>File Tags</th>
						<th>Document</th>
					</tr>
					<tr>
						<td><?php echo $docData['DepartmentId'] ?></td>
						<td><?php echo $docData['Filestag'] ?></td>
						<td><a href="<?php echo $docData['googledrive_view_link'] ?>" target="_blank" class="btn btn-primary">View</a></td>
					</tr>
				</tbody>
			</table>
			<div class="pagination">
				<span class="page-info"></span>
				<span class="pagination-nav"></span>
			</div>
		</div>
	</section>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>