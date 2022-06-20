<html>
    
<head>
	<title>Add Reminder</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fm.tagator.jquery.min.css">
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
	</style>
	<script>
		<?php if( isset($data) ){
			?>	var UpdateData = <?php print_r(json_encode($data)) ?>; <?php
		} 
		else {
			?>	var UpdateData = []; <?php
		}
		if( isset($students) ){
			?>	var studentsData = <?php print_r(json_encode(explode(',',$students))) ?>;	<?php
		} 
		else {
			?>	var studentsData = [];	<?php
		}
		if( isset($staff) ){
			?>	var staffData = <?php print_r(json_encode(explode(',',$staff))) ?>;	<?php
		} 
		else {
			?>	var staffData = [];	<?php
		}?>
	</script>
</head>

<body>
	<section class="container-fluid">
		
		<nav class="pt-5" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Dashboard</li>
				<li class="breadcrumb-item"><a href="javascript:0" onclick="history.back()">Reminders</a></li>
			</ol>
		</nav>
		<div class="data-div">
			<form action="<?php echo base_url(); ?>reminders/save-reminder" method="post">
				<input type="hidden" class="form-control" id="id" name="id" value="<?php if( isset($data) ){ echo $data['id']; }?>">
				<div class="mb-3">
					<label for="title" class="form-label">Title</label>
					<input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="<?php if( isset($data) ){ echo $data['title']; } ?>">
				</div>
				<div class="mb-3">
					<label for="reminder_cnt" class="form-label">Total Reminder</label>
					<input type="text" class="form-control" id="reminder_cnt" name="reminder_cnt" value="<?php if( isset($data) ){ echo $data['reminder_cnt']; } ?>">
				</div>
				<div class="mb-3">
					<label for="description" class="form-label">Description</label>
					<textarea type="text" class="form-control" id="description" name="description"><?php if( isset($data) ){ echo $data['description']; } ?></textarea>
					
				</div>
				<div class="mb-3">
					<label for="subject" class="form-label">Subject</label>
					<input type="text" class="form-control" id="subject" name="subject" value="<?php if( isset($data) ){ echo $data['subject']; } ?>">
				</div>
				<div class="mb-3 row">
					<div class="col-sm-4">
						<label for="valid_from" class="form-label">Valid From</label>
						<input type="text" class="form-control datepicker" autocomplete="off" id="valid_from" name="valid_from" value="<?php if( isset($data) ){ echo $data['valid_from']; } ?>">
					</div>
					<div class="col-sm-4">
						<label for="valid_to" class="form-label">Valid To</label>
						<input type="text" class="form-control datepicker" autocomplete="off" id="valid_to" name="valid_to" value="<?php if( isset($data) ){ echo $data['valid_to']; } ?>">
					</div>
					<div class="col-sm-4">
						<label class="form-label">Reminder For</label><br/>
						<input type="radio" name="reminder_for" class="form-radio-input" id="student" value="1" checked>  <label class="form-radio-label" for="student">Student</label>
						<input type="radio" name="reminder_for" class="form-radio-input" id="staff" value="2">  <label class="form-radio-label" for="staff">Staff</label>
					</div>
				</div>
				<div class="mb-3 row staff-div hide">
					<div class="col-sm-4">
						<label class="form-label">Department</label><br/>
						<?php 
						if( isset($department) ){
							foreach($department as $departmentData)
							{
								?>
								<input type="checkbox" name="department[]" class="form-check-input" id="<?php echo $departmentData['Name'] ?>" value="<?php echo $departmentData['Name'] ?>">  <label class="form-check-label" for="<?php echo $departmentData['Name'] ?>"><?php echo $departmentData['Name'] ?></label><br/>
								<?php
							}
						}
						?>
					</div>
					<div class="col-sm-8">
						<label class="form-label">Individual</label><br/>
						<input type="text" name="staff_list" class="form-control" id="staff_list" value="<?php if( isset($data) ){ echo $data['staff_list']; } ?>">
					</div>
				</div>
				<div class="mb-3 row student-div">
					<div class="col-sm-6 reminder-type-div">
						<label class="form-label">Reminder Type</label><br/>
						<input type="radio" name="reminder_type" class="form-radio-input" id="group" value="1" checked>  <label class="form-radio-label" for="group">Group</label>
						<input type="radio" name="reminder_type" class="form-radio-input" id="individual" value="2">  <label class="form-radio-label" for="individual">Individual</label>
					</div>
					<div class="col-sm-6 group-div">
						<label class="form-label">Type</label><br/>
						<input type="radio" name="student_type" class="form-radio-input" id="bachelor" value="1" checked>  <label class="form-radio-label" for="bachelor">Bachelor</label>
						<input type="radio" name="student_type" class="form-radio-input" id="master" value="2">  <label class="form-radio-label" for="master">Master</label>
						<input type="radio" name="student_type" class="form-radio-input" id="junior" value="3">  <label class="form-radio-label" for="junior">Junior</label>
					</div>
					<div class="col-sm-8 individual-div hide">
						<label class="form-label">Individual</label><br/>
						<input type="text" name="student_list" class="form-control" id="student_list" value="<?php if( isset($data) ){ echo $data['student_list']; } ?>">
					</div>
				</div>
				<div class="mb-3 row student-div group-div">
					<div class="col-sm-6">
						<label class="form-label">Specialization</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="DC" value="DC">  <label class="form-check-label" for="DC">Developmental Counselling</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="ECCE" value="ECCE">  <label class="form-check-label" for="ECCE">Early Childhood Care And Education</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="FND" value="FND">  <label class="form-check-label" for="FND">Food Nutrition And Dietetics</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="HTM" value="HTM">  <label class="form-check-label" for="HTM">Hospitality And Tourism Management</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="IDRM" value="IDRM">  <label class="form-check-label" for="IDRM">Interior Design And Resource Management</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="MCE" value="MCE">  <label class="form-check-label" for="MCE">Mass Communication And Extension</label><br/>
						<input type="checkbox" name="specialization[]" class="form-check-input" id="TAD" value="TAD">  <label class="form-check-label" for="TAD">Textiles And Apparel Designing</label><br/>
					</div>
					<div class="col-sm-6">
						<label class="form-label">Year</label><br/>
						<input type="radio" name="year" class="form-radio-input" id="firstyear" value="FY" checked>  <label class="form-radio-label" for="firstyear">First Year</label></br>
						<input type="radio" name="year" class="form-radio-input" id="secondyear" value="SY">  <label class="form-radio-label" for="secondyear">Second Year</label></br>
						<span class="third-year"><input type="radio" name="year" class="form-radio-input" id="thirdyear" value="TY">  <label class="form-radio-label" for="thirdyear">Third Year</label></br></span>
					</div>
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
<script src="<?php echo base_url()?>assets/js/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/js/fm.tagator.jquery.js"></script>

<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var start = 0;
	$(document).ready(function() {

		var student_list = $('#student_list');
		student_list.tagator({
			autocomplete: studentsData,
			useDimmer: true
		});
		
		var staff_list = $('#staff_list');
		staff_list.tagator({
			autocomplete: staffData,
			useDimmer: true
		});

		if(!$.isEmptyObject(UpdateData))
		{
			$("[name='reminder_for'][value='"+UpdateData.reminder_for+"']").prop('checked', true);
			$("[name='student_type'][value='"+UpdateData.student_type+"']").prop('checked', true);
			$("[name='year'][value='"+UpdateData.year+"']").prop('checked', true);
			$("[name='reminder_type'][value='"+UpdateData.reminder_type+"']").prop('checked', true);

			var specialization = jQuery.parseJSON(UpdateData.specialization);
			$.each(specialization, function( index, value ) {
				$("[name='specialization[]'][value='"+value+"']").prop('checked', true);
			});
			
			var department = jQuery.parseJSON(UpdateData.department);
			$.each(department, function( index, value ) {
				$("[name='department[]'][value='"+value+"']").prop('checked', true);
			});

			if(UpdateData.reminder_type == 1)
			{
				$(".individual-div").addClass('hide');
				$(".group-div").removeClass('hide');
				$(".reminder-type-div").removeClass('col-sm-4').addClass('col-sm-6');
			}
			else
			{
				$(".group-div").addClass('hide');
				$(".individual-div").removeClass('hide');
				$(".reminder-type-div").removeClass('col-sm-6').addClass('col-sm-4');
			}

			if(UpdateData.reminder_for == 1)
			{
				$(".staff-div").addClass('hide');
				$(".student-div").removeClass('hide');
			}
			else
			{
				$(".student-div").addClass('hide');
				$(".staff-div").removeClass('hide');
			}

			if(UpdateData.student_type == 1)
			{
				$(".third-year").removeClass('hide');
			}
			else
			{
				$(".third-year").addClass('hide');
			}
		}

		var data = {"search": "", "daterange": ""};
		__onLoadStudentData(data);
		
		$( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
		ClassicEditor
			.create( document.querySelector( '#description' ),{
				toolbar: {
					items: [
						'fontfamily', 'fontsize', '|',
						'fontColor', 'fontBackgroundColor', '|',
						'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|',
						'link', '|',
						'outdent', 'indent', '|',
						'alignment','bulletedList', 'numberedList', '|',
						'code', 'codeBlock', '|',
						'insertTable', '|',
						/*'uploadImage',*/ 'blockQuote', '|',
						'undo', 'redo'
					],
					shouldNotGroupWhenFull: true
				}
			})
			.catch( error => {
				console.error( error );
			} );
			ClassicEditor.editorConfig = function( config ) {
				ClassicEditor.config.extraPlugins = 'autogrow';
				ClassicEditor.config.autoGrow_minHeight = 450;
				ClassicEditor.config.autoGrow_maxHeight = 600;
			};
		
		$("#apply_filter").on("click", function(){
			var search = $("#search").val();
			var daterange = $("#date").val();			
			var data = {"search": search, "daterange": daterange};
			__onLoadStudentData(data);
		});

		$("input[name='reminder_type']").on('change', function(){
			var reminder_type = $("input[name='reminder_type']:checked").val();
			if(reminder_type == 1)
			{
				$(".individual-div").addClass('hide');
				$(".group-div").removeClass('hide');
				$(".reminder-type-div").removeClass('col-sm-4').addClass('col-sm-6');
			}
			else
			{
				$(".group-div").addClass('hide');
				$(".individual-div").removeClass('hide');
				$(".reminder-type-div").removeClass('col-sm-6').addClass('col-sm-4');
			}
		});
		
		$("input[name='reminder_for']").on('change', function(){
			var reminder_for = $("input[name='reminder_for']:checked").val();
			if(reminder_for == 1)
			{
				$(".staff-div").addClass('hide');
				$(".student-div").removeClass('hide');
			}
			else
			{
				$(".student-div").addClass('hide');
				$(".staff-div").removeClass('hide');
			}
		});

		$("input[name='student_type']").on('change', function(){
			var student_type = $("input[name='student_type']:checked").val();
			if(student_type == 1)
			{
				$(".third-year").removeClass('hide');
			}
			else
			{
				$(".third-year").addClass('hide');
				var year = $("input[name='year']:checked").val();
				if(year == 3)
				{
					$("input[name='year'][value='1']").prop('checked', true);
				}
			}
		});

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

</script>

</html>