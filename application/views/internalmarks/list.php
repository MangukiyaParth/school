<html>
    
<head>
	<title>Internal Marks List</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<style>
		li.paginate_button.page-item {
			padding: 7px 15px;
			border: 1px solid #EEE;
			border-radius: 50%;
			margin: 5px;
		}
		li.paginate_button.page-item a{
			color: #000000;
			text-decoration: none;
		}
		li.paginate_button.page-item.active {
			background-color: #0d6efd;
		}	
		li.paginate_button.page-item.active a{
			color: #FFFFFF;
		}	
		.pagination li.first ,
		.pagination li.last {
			border-radius: 20px;
		}
		.pagination {
			justify-content: space-between;
		}
		.text-right {
			text-align: right;
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
		.select2-container--default .select2-results__option[aria-disabled=true] {
			display: none;
		}
	</style>
</head>

<body>
	<section class="container-fluid">
		<form action="<?php echo base_url(); ?>internalmarks/paper-wise-student" method="POST">
			<div class="search-div row" style="margin-bottom: 25px; margin-top: 25px;">
				<div class="offset-sm-2 col-sm-8 mb-5">
					<h1>Internal Marks Entry</h1>
				</div>
				<div class="offset-sm-2 col-sm-4 mb-3">
					<label class="form-label"> Stream </label>
					<select class="form-select select2" id="stream" name="stream">
						<option value="BSc">BSc</option>
					</select>
				</div>
				<div class="col-sm-4 mb-3">
					<label class="form-label"> Course </label>
					<select class="form-select select2" id="course" name="course">
						<option value="Regular" selected>Regular</option>
						<option value="Honors">Honors</option>
					</select>
				</div>
				<div class="offset-sm-2 col-sm-4 mb-3">
					<label class="form-label"> Semester </label>
					<select class="form-select select2" id="semester" name="semester">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
					</select>
				</div>
				<div class="col-sm-4 mb-3">
					<label class="form-label"> Exam Type </label>
					<select class="form-select select2" id="exam_type" name="exam_type">
						<option value="Regular" selected>Regular</option>
					</select>
				</div>
				<div class="offset-sm-2 col-sm-4 mb-3">
					<label class="form-label"> Specialization </label>
					<select class="form-select select2" id="specialization" name="specialization">
						<option value="DC">Developmental Counselling</option>
						<option value="ECCE">Early Childhood Care And Education</option>
						<option value="FND">Food Nutrition And Dietetics</option>
						<option value="HTM">Hospitality And Tourism Management</option>
						<option value="IDRM">Interior Design And Resource Management</option>
						<option value="MCE">Mass Communication And Extension</option>
						<option value="TAD">Textiles And Apparel Designing</option>
					</select>
				</div>
				<div class="col-sm-4 mb-3">
					<label class="form-label"> Paper </label>
					<select class="form-select" id="paper_id" name="paper_id">
						
					</select>
				</div>
				<div class="offset-sm-2 col-sm-4 mb-3">
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
				<div class="col-sm-3">
					<button class="btn btn-primary" type="submit" id="apply_filter">Apply</button>
				</div>
			</div>
		</form>
		
	</section>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var start = 0;
	$(document).ready(function() {
		$('.select2').select2();
		
		fillpaper();
		$("#semester,#year").on('change', function(){
			fillpaper();
		});
		$("#specialization").on('change', function(){
			fillpaper();
			//autoSelectPapers();
		});

		// $("#apply_filter").on("click", function(){
		// 	var stream = $("#stream").val();		
		// 	var course = $("#course").val();		
		// 	var semester = $("#semester").val();		
		// 	var exam_type = $("#exam_type").val();		
		// 	var specialization = $("#specialization").val();		
		// 	var paper_id = $("#paper_id").val();		
		// 	var data = {
		// 		"stream": stream,
		// 		"course": course,
		// 		"semester": semester,
		// 		"exam_type": exam_type,
		// 		"specialization": specialization,
		// 		"paper_id": paper_id
		// 	};
		// 	__LoadStudentData(data);
		// });

	});

	function fillpaper(paper=''){
		var semester = $("#semester").val();
		var specialization = $("#specialization").val();
		var year = $("#year").val();
		var data = {"semester": semester, "specialization": specialization, "year": year};
		$.ajax({
			type:"POST",
			url :BASE_URL+"internalmarks/get-papers/",
			async:false,
			data:data,
			success:function(response) {
				var resultArr = JSON.parse(response);
				if (!$.isEmptyObject(resultArr.paperData) && !$.isEmptyObject(resultArr.assigned_paper_list)) {
					var papers = JSON.parse(resultArr.paperData);
					var assigned_paper_list = JSON.parse(resultArr.assigned_paper_list);
					
					console.log(papers);
					var optData = "";
					if (papers.length > 0) {
						for (var i = 0; i < papers.length; i++) {
							var specializationData = papers[i];
							var specialization = specializationData.specialisationCode;
							var paperDetails = specializationData.paperDetails;
							if (paperDetails.length > 0) {
								for (var j = 0; j < paperDetails.length; j++) {
									var paperData = paperDetails[j];
									console.log((paperData.code, assigned_paper_list));
									if(jQuery.inArray(paperData.code, assigned_paper_list) !== -1) {
										optData+="<option value='"+paperData.code+"' data-specialization='"+ specialization +"'>"+paperData.paperTitle+" - "+paperData.code+" ("+paperData.paperType+")</option>";
									}
								}
							}
						}
					}
				}

				$("#paper_id").html(optData);
				$('#paper_id').select2();
				autoSelectPapers()
			}
		});
	}

	function autoSelectPapers(){
		
		var specialization = $("#specialization").val();
		var selectval = [];
		$('#paper_id').val(selectval);
		$('#paper_id').trigger('change');
		if (specialization.length > 0) {
			$("#paper_id option").attr("disabled", "disabled");
			$("#paper_id option").each(function(){
				if($(this).attr("data-specialization") == specialization){
					$(this).removeAttr("disabled");
				}
			});
		}
		$('#paper_id').trigger('change');
	}

	function __LoadStudentData(data) {
		$.ajax({
			type:"POST",
			url :BASE_URL+"internalMarks/paper-wise-student/"+start,
			async:false,
			data:data,
			success:function(response) {
				var resultArr = JSON.parse(response);
				setTableFormatData(start, data, resultArr); 
				
			}
		});
	}

</script>

</html>