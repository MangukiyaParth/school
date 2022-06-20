<html>
    
<head>
	<title>Map Teacher / Paper</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<style>
		.container-fluid{
			padding: 15px 20%;
		}
		.hide {
			display: none !important;
		}
		.ck-editor__editable {
			min-height: 500px;
		}
		nav {
			padding-bottom: 5px;
			border-bottom: 1px solid #DDD;
			margin-bottom: 20px;
		}
		.breadcrumb-item+.breadcrumb-item::before {
			padding-top: 10px;
		}

		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 38px;
			right: 10px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin: 5px;
		}
		.select2-container .select2-selection--single {
			height: 40px;
		}
		.select2-container--default .select2-selection--single {
			border: 1px solid #ccc;
		}
	</style>
	<script>
		<?php if( isset($data) ){
			?>	var UpdateData = <?php print_r(json_encode($data)) ?>; <?php
		} 
		else {
			?>	var UpdateData = []; <?php
		}
		?>
	</script>
</head>

<body>
	<section class="container-fluid">
		
		<nav class="pt-5" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Dashboard</li>
				<li class="breadcrumb-item"><a href="javascript:0" onclick="history.back()">AssignStaff</a></li>
			</ol>
		</nav>
		<div class="data-div">
			<form action="<?php echo base_url(); ?>assignstaff/save" method="post">
				<input type="hidden" class="form-control" id="id" name="id" value="<?php if( isset($data) ){ echo $data['id']; }?>">
				<div class="mb-3">
					<label for="teacher" class="form-label">Teacher</label>
					<select class="form-select select2" id="teacher_id" name="teacher_id" <?php if( isset($data) ){ echo 'readonly disabled '; }?>>
						<?php
						if( isset($staff) ){
							$staffList = json_decode($staff);
							foreach($staffList as $staffData)
							{
								echo '<option value="'.$staffData->id.'" data-department="'.$staffData->department.'">'.$staffData->username.'</option>';
							}
						}
						?>
					</select>
				</div>
				<div class="mb-3">
					<label for="reminder_cnt" class="form-label">Semester</label>
					<select class="form-select select2" id="semester" name="semester" <?php if( isset($data) ){ echo 'readonly disabled '; }?>>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label"> year </label>
					<select class="form-select select2" id="year" name="year">
						<?php
						$curryear = date('Y');
						for($i = 2016; $i<= $curryear; $i++)
						{
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						?>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">Department</label><br/>
					<select class="form-select select2" id="department_id" name="department_id[]" multiple <?php if( isset($data) ){ echo 'readonly disabled '; }?>>
						<?php
						if( isset($department) ){
							foreach($department as $departmentData)
							{
								echo "<option value='".$departmentData['Name']."'>".$departmentData['Name']."</option>";
							}
						}
						?>
					</select>
				</div>
				<div class="mb-3">
					<label for="paperid" class="form-label">Paper</label>
					<select class="form-select select2" id="paper_id" name="paper_id[]" multiple>
					</select>
				</div>
				
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>			
		</div>
	</section>

	<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
		<div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
				<svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#FF0000"></rect></svg>
				<strong class="me-auto">Error</strong>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body" id="tost_msg">
				Hello, world! This is a toast message.
			</div>
		</div>
	</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var specializationMapDepartment = [];
	var start = 0;
	$(document).ready(function() {

		$('#teacher_id').select2();
		$('#department_id').select2({
			closeOnSelect: false,
			multiple: true
		});

		changeTeacher();
		$("#teacher_id").on('change', function(){
			changeTeacher();
		});
		
		$("#semester").on('change', function(){
			fillpaper();
		});
		// $("#department_id").on('change', function(){
		// 	autoSelectPapers();
		// });
		
		var data = {"search": "", "daterange": ""};
		__onLoadStudentData(data);
		
		$("#apply_filter").on("click", function(){
			var search = $("#search").val();
			var daterange = $("#date").val();			
			var data = {"search": search, "daterange": daterange};
			__onLoadStudentData(data);
		});
		
		if(!$.isEmptyObject(UpdateData))
		{
			$("#teacher_id").val(UpdateData.staffid);
			$("#semester").val(UpdateData.semester);

			var department = UpdateData.department.split(',');
			$('#department_id').val(department);
			$('#department_id').trigger('change');
			fillpaper(UpdateData.paper);
		}
		else
		{
			fillpaper();
		}
	});

	function __onLoadStudentData(data) {
		$.ajax({
			type:"POST",
			url :BASE_URL+"document/user-wise-document/"+start,
			async:false,
			data:data,
			success:function(response) {
				var resultArr = JSON.parse(response);
				setTableFormatData(start, data, resultArr); 
				
			}
		});
	}

	function setTableFormatData(start, data, resultArr) {

		pageNo = start;
		var listArr = resultArr.listArr;
		var datatable_pageinfo = resultArr.pageinfo;
		var paginate_links = resultArr.links;

		$(".datatable_pageinfo").html(datatable_pageinfo);
		$(".paginate_links").html(paginate_links);

		//fill data in to table

		var tableData = '';
		for (var i = 0; i <= listArr.length; i++) {
			if (i == listArr.length) {
				
				if (listArr.length == 0) {
					$("#paper_wise_table tbody").html("<tr><td colspan='15'>No data found...</td></tr>");
					$(".pagination-nav").html("");
					$(".page-info").html("");
				} else {
					category_arr = listArr;
					$("#paper_wise_table tbody").html(tableData);
					$(".pagination-nav").html(resultArr.links);
					$(".page-info").html(resultArr.pageinfo);
				}
			} else {
				tableData += '<tr class="rowId_'+listArr[i].id+'">';
					tableData += '<td>'+listArr[i].UniqueName+'</td>';
					tableData += '<td><a href="'+BASE_URL+'document/documentDetails/'+listArr[i].id+'">'+listArr[i].DocumentTitle+'</a></td>';
					tableData += '<td>'+listArr[i].LastModified+'</td>';
				tableData += '</tr>';
			}
		}
		
		$(".loader").hide();
		$(".pagination .paginate_button a").click(function() {
		
			$.ajax({
				type: "POST",
				url: $(this).attr("href"),
				async:true,
				data: data,
				success: function(response) {
					var resultArr = JSON.parse(response);
					pageNo = parseInt(resultArr.start);
					var start = pageNo;
					setTableFormatData(start, data, resultArr);
				}
			});
			return false;
		});
	}

	function changeTeacher(){
		var dept = $("#teacher_id :selected").attr('data-department');
		$('#department_id').val(dept);
		$('#department_id').trigger('change');
	}

	function fillpaper(selectedpaper){
		var semester = $("#semester").val();
		var data = {"semester": semester, "selectedpaper": selectedpaper};
		$.ajax({
			type:"POST",
			url :BASE_URL+"assignstaff/get-papers/",
			async:false,
			data:data,
			success:function(response) {
				var resultArr = JSON.parse(response);
				var papers = JSON.parse(resultArr.paperData);
				var selectedpaper = resultArr.selectedpaper;
				selectedpaper = selectedpaper.split(',');
				var selecteddepartment = $('#department_id').val();
				specializationMapDepartment = JSON.parse(resultArr.specializationMapDepartment);
				
				var optData = "";
				if (papers.length > 0) {
					for (var i = 0; i < papers.length; i++) {
						var specializationData = papers[i];
						var specialization = specializationData.specialisationCode;
						var paperDetails = specializationData.paperDetails;
						if (paperDetails.length > 0) {
							for (var j = 0; j < paperDetails.length; j++) {
								var paperData = paperDetails[j];
								var dept = specializationMapDepartment[0][specialization];
								var selected = '';
								if(!$.isEmptyObject(selectedpaper) && jQuery.inArray(paperData.code+"~"+specialization+"~"+paperData.paperTitle, selectedpaper) !== -1)
								{
									selected = 'selected';
								}
								optData+="<option value='"+paperData.code+"~"+specialization+"~"+paperData.paperTitle+"' "+selected+" data-specialization='"+ specialization +"' data-dept='"+ dept +"'>"+paperData.paperTitle+" - "+paperData.code+" ("+paperData.paperType+")</option>";
							}
						}
					}
				}

				$("#paper_id").html(optData);
				$('#paper_id').select2({
					closeOnSelect: false,
					multiple: true
				});
				// var selectedpaper = resultArr.selectedpaper;
				// console.log(selectedpaper);
				// if(typeof selectedpaper != 'undefined' && selectedpaper != '')
				// {
				// 	var selectedpaper = selectedpaper.split(',');
				// 	$('#paper_id').val(selectedpaper);
				// 	$('#paper_id').trigger('change');
				// }
				// else
				// {
				// 	autoSelectPapers();
				// }
			}
		});
	}

	function autoSelectPapers(){
		
		// var department_id = $("#department_id").val();
		// var selectval = [];
		// $('#paper_id').val(selectval);
		// $('#paper_id').trigger('change');
		// if (department_id.length > 0) {
		// 	$("#paper_id option").each(function(){
		// 		if(jQuery.inArray($(this).attr("data-dept"), department_id) !== -1 && selectval.indexOf($(this).val()) === -1){
		// 			$(this).attr("selected", "selected");
		// 		}
		// 	});
		// }
		// $('#paper_id').trigger('change');
	}
</script>

</html>