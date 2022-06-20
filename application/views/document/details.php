<html>
<head>
	<title>Document Details</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
		.container-fluid {
			padding: 15px 10%;
		}
		.table td,
		.table th{
			vertical-align: middle;
			padding: 10px 25px;
		}
		.mb25 {
			margin-bottom: 25px;
		}
		.breadcrumb-item+.breadcrumb-item::before {
			padding-top: 10px;
		}
		nav {
			padding-bottom: 5px;
			border-bottom: 1px solid #DDD;
			margin-bottom: 20px;
		}
	</style>
	<?php 
		$docData = $data[0]; 
		$MessageHeader = json_decode($docData['MessageHeader']);
		// var_dump(json_decode($MessageHeader));
		$from = $MessageHeader->From;
		$to = $MessageHeader->To;
		$subject = $MessageHeader->Subject;

		function startsWith ($string, $startString)
		{
			$len = strlen($startString);
			return (substr($string, 0, $len) === $startString);
		}
	?>
</head>

<body>
	<section class="container-fluid">
		<div class="data-div">
			<nav class="pt-5" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">Dashboard</li>
					<li class="breadcrumb-item"><a href="javascript:0" onclick="history.back()">Documents</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?php echo $docData['DocumentTitle'] ?></li>
				</ol>
			</nav>
			<table class="table table-striped table-hover" id="paper_wise_table">
				<tbody>
					<tr>
						<th width="30%">Document Type</th>
						<td><?php if(startsWith( $docData['UniqueName'], 'I' )) { echo 'Inward'; } else { echo 'Outward'; } ?></td>
					</tr>
					<tr>
						<th>Document Number</th>
						<td><?php echo $docData['UniqueName'] ?></td>
					</tr>
					<tr>
						<th>Document Title</th>
						<td><?php echo $docData['DocumentTitle'] ?></td>
					</tr>
					<tr>
						<th>From</th>
						<td><?php echo $from ?></td>
					</tr>
					<tr>
						<th>To</th>
						<td><?php echo $to ?></td>
					</tr>
					<tr>
						<th>Subject</th>
						<td><?php echo $subject ?></td>
					</tr>
					<tr>
						<th>Received On</th>
						<td><?php echo $docData['Received_format'] ?></td>
					</tr>
					<tr>
						<th>Forwarded On</th>
						<td><?php echo $docData['Forwarded_format'] ?></td>
					</tr>
					<tr>
						<th>Deadline</th>
						<td><?php echo $docData['Deadline_format'] ?></td>
					</tr>
					<tr>
						<th>Department</th>
						<td><?php echo $docData['DepartmentId'] ?></td>
					</tr>
					<tr>
						<th>File Tags</th>
						<td><?php echo $docData['Filestag'] ?></td>
					</tr>
					<tr>
						<th>Document</th>
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