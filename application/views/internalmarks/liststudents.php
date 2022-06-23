<html>
    
<head>
	<title>Student Internal Marks List</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<style>
		.container-fluid {
			padding: 0 15%;
		}
	</style>
	<script>
		var SelectedpaperCode = '<?php echo $SelectedpaperCode; ?>';
		var Selectedspecialisation = '<?php echo $specialisation; ?>';
		var Selectedyear = '<?php echo $year; ?>';
		var InternalMarksData = <?php print_r(json_encode($markDetails)); ?>
	</script>
</head>

<body>
	<section class="container-fluid">
		<div class="search-div row" style="margin-bottom: 25px; margin-top: 25px;">
			<div class="col-sm-8 mb-3">
				<h3>Internal Marks For the Subject <?php echo $SelectedpaperTitle; ?></h3>
			</div>
			<div class="col-sm-4 mb-3">
				<input type="button" class="btn btn-primary float-end btn_save" id="btn_save" value="Save"/>
				<?php
				if($json_exist == 1)
				{
					?>
						<input type="button" class="btn btn-info float-end me-2" id="" onclick="downloadPDF()" value="Download PDF"/>
						<input type="button" class="btn btn-success float-end me-2" id="" onclick="downloadExcel()" value="Download CSV"/>
					<?php
				}
				?>
			</div>
		</div>
		<div class="data-div">
			<table class="table table-striped" id="tblInternalMarks">
				<thead>
					<tr class="table-primary">
						<th>Roll No</th>
						<th>Name</th>
						<th width="10%">Internal Marks</th>
						<th width="10%">Option</th>
					</tr>
				</thead>
				<tbody>
					<?php
						//print_r($markDetails);
						$i = 0;
						foreach($markDetails as $studentMarks)
						{
							$absselect = "";
							$readonlymarks = "";
							if($studentMarks['isabs'] == 1)
							{
								$absselect = "selected";
								$readonlymarks = "readonly";
							}
							?>
							<tr data-index="<?php echo $i; ?>">
								<td><?php echo $studentMarks['RollNumber']; ?></td>
								<td><?php echo $studentMarks['fullname']; ?></td>
								<td><input type="number" class="form-control marks" tabindex="<?php echo $i+1; ?>" <?php echo $readonlymarks; ?> value="<?php echo $studentMarks['internalmarks']; ?>"/></td>
								<td>
									<select class="form-select status" name="status">
										<option value="P">P</option>
										<option value="ABS" <?php echo $absselect; ?>>ABS</option>
									</select>
								</td>
							</tr>
							<?php
							$i++;
						}
					?>
				</tbody>
			</table>
			<div class="col-sm-12 mb-3">
				<input type="button" class="btn btn-primary float-end btn_save" value="Save"/>
			</div>
		</div>
	</section>

	
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var start = 0;
	$(document).ready(function() {
		$("#tblInternalMarks input.marks").on('input', function(){
			var index = $(this).parent('TD').parent('TR').attr('data-index');
			var theoryInternalMax = InternalMarksData[index].theoryInternalMax;
			if(!$.isEmptyObject($(this).val()))
			{
				if($(this).val() < 0)
				{
					var marks = 0;
					$(this).val(0);
				}
				else if($(this).val() > theoryInternalMax)
				{
					var marks = theoryInternalMax;
					$(this).val(marks);
					alert("Please enter marks bellow"+ theoryInternalMax);
				}
				else
				{
					var marks = $(this).val();
				}
			}
			else
			{
				marks = "0";
			}
			InternalMarksData[index].internalmarks = marks;
		});
		
		$("#tblInternalMarks select.status").on('change', function(){
			var index = $(this).parent('TD').parent('TR').attr('data-index');
			if($(this).val() == 'ABS')
			{
				$(this).parent('TD').parent('TR').find('input.marks').val('ABS').prop('readonly', true);
				InternalMarksData[index].internalmarks = "ABS";
				InternalMarksData[index].isabs = 1;
			}
			else
			{
				$(this).parent('TD').parent('TR').find('input.marks').val("0").prop('readonly', false);
				InternalMarksData[index].internalmarks = 0;
				InternalMarksData[index].isabs = 0;
			}
			
		});

	});

	
	$(".btn_save").on('click', function(){
		var isinvalid = 0;
		var invalidrollno = [];
		$("#tblInternalMarks tbody tr").each(function(index, tr) {
			var status = $(this).find('select.status').val();
			var marks = $(this).find('input.marks').val();
			if(marks == '' && status == 'P')
			{
				isinvalid = 1;
				invalidrollno.push(InternalMarksData[index].RollNumber);
			}
		});

		if(isinvalid == 1){
			alert("Please select ABS status with empty Internal Marks")
		}
		else if($("#tblInternalMarks tbody tr").length > 0)
		{
			var data = {
				"year": Selectedyear,
				"papercode": SelectedpaperCode, 
				"specialisation" : Selectedspecialisation, 
				"marksData": JSON.stringify(InternalMarksData)
			};
			$.ajax({
				type:"POST",
				url :BASE_URL+"internalmarks/save",
				async:false,
				data: data,
				success:function(response) {
					window.location = BASE_URL+"internalmarks/";
					
				}
			});
		}
		else
		{
			alert("No Data to save");
		}
	});

	function downloadExcel(){
		var data = {
			"year": Selectedyear,
			"papercode": SelectedpaperCode, 
			"specialisation" : Selectedspecialisation, 
			"marksData": JSON.stringify(InternalMarksData)
		};
		$.ajax({
			type:"POST",
			url :BASE_URL+"internalmarks/download-excel",
			async:false,
			data: data,
			success:function(response) {
				var resultArr = JSON.parse(response);
				// console.log(resultArr.filepath);
				window.open(resultArr.filepath, '_blank').focus();
			}
		});
	}
	
	function downloadPDF(){
		var data = {
			"year": Selectedyear,
			"papercode": SelectedpaperCode, 
			"specialisation" : Selectedspecialisation, 
			"marksData": JSON.stringify(InternalMarksData)
		};
		$.ajax({
			type:"POST",
			url :BASE_URL+"internalmarks/download-pdf",
			async:false,
			data: data,
			success:function(response) {
				var resultArr = JSON.parse(response);
				// console.log(resultArr.filepath);
				window.open(resultArr.filepath, '_blank').focus();
			}
		});
	}

</script>

</html>