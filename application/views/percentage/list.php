<html>
    
<head>
	<title>Paper Wise Summary Report</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
			justify-content: flex-end;
		}
		.data-div tbody tr td a {
			color: #0d6efd !important;
			text-decoration: underline !important;
		}
		.text-right {
			text-align: right;
		}
	</style>
</head>

<body>
	<section class="container-fluid">
		<div class="search-div row justify-content-center" style="margin-bottom: 25px; margin-top: 25px;">
			<div class="col-sm-offset-3 col-sm-2">
				<select name="year" id="year" class="form-select">
					<option value="" selected hidden disabled>Select Admission year</option>
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
					<option value="2016">2016</option>
					<option value="2017">2017</option>
					<option value="2018">2018</option>
					<option value="2019">2019</option>
					<option value="2020">2020</option>
					<option value="2021">2021</option>
					<option value="2022">2022</option>
					<option value="2023">2023</option>
					<option value="2024">2024</option>
				</select>
			</div>
			<div class="col-sm-3">
				<select name="specialisation" id="specialisation" class="form-select">
					<option value="" selected hidden disabled>Select Specialisation</option>
					<option value="DC">Developmental Counselling</option>
					<option value="ECCE">Early Childhood Care And Education</option>
					<option value="FND">Food Nutrition And Dietetics</option>
					<option value="HTM">Hospitality And Tourism Management</option>
					<option value="IDRM">Interior Design And Resource Management</option>
					<option value="MCE">Mass Communication And Extension</option>
					<option value="TAD">Textiles And Apparel Designing</option>
				</select>
			</div>
			<div class="col-sm-2">
				<select name="course" id="course" class="form-select">
					<option value="" selected hidden disabled>Select Course</option>
					<option value="Regulers">Regulers</option>
					<option value="Honors">Honors</option>
				</select>
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary" id="apply_filter">Apply</button>
			</div>
		</div>
		<div class="data-div">
			<table class="table table-striped" id="paper_wise_table">
				<thead>
					<tr class="table-primary">
						<th class="text-right">Sr No.</th>
						<th class="text-right">CRN</th>
						<th>Full Name</th>
						<th class="text-right">Sem 1</th>
						<th class="text-right">Sem 2</th>
						<th class="text-right">Sem 3</th>
						<th class="text-right">Sem 4</th>
						<th class="text-right">Sem 5</th>
						<th class="text-right">Sem 6</th>
						<th class="text-right">Weighted Per.</th>
						<th class="text-right">Progress</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
			<div class="pagination"></div>
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
<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var start = 0;
	$(document).ready(function() {
		$("#apply_filter").on("click", function(){
			var year = $("#year").val();
			var specialisation = $("#specialisation").val();
			var course = $("#course").val();
			var toastLiveExample = document.getElementById('liveToast');
			if(year == '' || year == null)
			{
				$("#tost_msg").html("Please, Select year!");
				var toast = new bootstrap.Toast(toastLiveExample);
    			toast.show();
				return false;
			}
			else if(specialisation == '' || specialisation == null)
			{
				$("#tost_msg").html("Please, Select specialisation!");
				var toast = new bootstrap.Toast(toastLiveExample);
    			toast.show();
				return false;
			}
			else if (course == "" || course == null)
			{
				$("#tost_msg").html("Please, Select course!");
				var toast = new bootstrap.Toast(toastLiveExample);
    			toast.show();
				return false;
			}
			else
			{
				var data = {"year": year, "specialisation": specialisation, "course": course};
				__onLoadPercentageData(data);
			}
		});


	});

	function __onLoadPercentageData(data) {
		$.ajax({
			type:"POST",
			url :BASE_URL+"percentage/percentage-wise-summary/"+start,
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
					$(".pagination").html("");
				} else {
					category_arr = listArr;
					$("#paper_wise_table tbody").html(tableData);
					$(".pagination").html(resultArr.links);
				}
			} else {
				tableData += '<tr class="rowId_'+listArr[i].category_id+'">';
				
					var avg_per = (+listArr[i].sem1 + +listArr[i].sem2 + +listArr[i].sem3 + +listArr[i].sem4 + +listArr[i].sem5 + +listArr[i].sem6) / 6;

					tableData += '<td class="text-right">'+(+i + 1)+'</td>';
					tableData += '<td class="text-right">'+listArr[i].collegeregistrationnumber+'</td>';
					tableData += '<td>'+listArr[i].fullname+'</td>';
					tableData += '<td class="text-right"><a href="javascript:void(0)" onclick="showPerDetails('+listArr[i].id+',1,\''+ data.year +'\',\''+ data.specialisation +'\',\''+ data.course +'\')">'+listArr[i].sem1+'</a></td>';
					tableData += '<td class="text-right"><a href="javascript:void(0)" onclick="showPerDetails('+listArr[i].id+',2,\''+ data.year +'\',\''+ data.specialisation +'\',\''+ data.course +'\')">'+listArr[i].sem2+'</a></td>';
					tableData += '<td class="text-right"><a href="javascript:void(0)" onclick="showPerDetails('+listArr[i].id+',3,\''+ data.year +'\',\''+ data.specialisation +'\',\''+ data.course +'\')">'+listArr[i].sem3+'</a></td>';
					tableData += '<td class="text-right"><a href="javascript:void(0)" onclick="showPerDetails('+listArr[i].id+',4,\''+ data.year +'\',\''+ data.specialisation +'\',\''+ data.course +'\')">'+listArr[i].sem4+'</a></td>';
					tableData += '<td class="text-right"><a href="javascript:void(0)" onclick="showPerDetails('+listArr[i].id+',5,\''+ data.year +'\',\''+ data.specialisation +'\',\''+ data.course +'\')">'+listArr[i].sem5+'</a></td>';
					tableData += '<td class="text-right"><a href="javascript:void(0)" onclick="showPerDetails('+listArr[i].id+',6,\''+ data.year +'\',\''+ data.specialisation +'\',\''+ data.course +'\')">'+listArr[i].sem6+'</a></td>';
					tableData += '<td class="text-right">'+avg_per.toFixed(2)+'</td>';
					tableData += '<td class="text-right"></td>';
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