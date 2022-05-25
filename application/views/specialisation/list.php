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
	</style>
</head>

<body>
	<section class="container-fluid">
		<div class="search-div row justify-content-center" style="margin-bottom: 25px; margin-top: 25px;">
			<div class="col-sm-offset-3 col-sm-2">
				<select name="year" id="year" class="form-select">
					<option value="" selected hidden disabled>Select year</option>
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
			<div class="col-sm-2">
				<select name="semester" id="semester" class="form-select">
					<option value="" selected hidden disabled>Select Semester</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
				</select>
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary" id="apply_filter">Apply</button>
			</div>
		</div>
		<div class="data-div">
			<table class="table table-striped" id="paper_wise_table">
				<thead>
					<tr>
						<th>Specialisation</th>
						<th>Enrolled</th>
						<th>Appered</th>
						<th>ABS</th>
						<th>O+</th>
						<th>O</th>
						<th>A+</th>
						<th>A</th>
						<th>B+</th>
						<th>B</th>
						<th>C</th>
						<th>P</th>
						<th>F</th>
						<th>Overall Pass</th>
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
			var semester = $("#semester").val();
			var toastLiveExample = document.getElementById('liveToast');
			if(year == '' || year == null)
			{
				$("#tost_msg").html("Please, Select year!");
				var toast = new bootstrap.Toast(toastLiveExample);
    			toast.show();
				return false;
			}
			else if (semester == "" || semester == null)
			{
				$("#tost_msg").html("Please, Select semester!");
				var toast = new bootstrap.Toast(toastLiveExample);
    			toast.show();
				return false;
			}
			else
			{
				var data = {"year": year, "semester": semester};
				__onLoadSpecialisationData(data);
			}
		});


	});

	function __onLoadSpecialisationData(data) {
		$.ajax({
			type:"POST",
			url :BASE_URL+"specialisation/specialisation-summary/"+start,
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
				
					var total_pass = +listArr[i].op + +listArr[i].o + +listArr[i].ap + +listArr[i].a + +listArr[i].bp + +listArr[i].b + +listArr[i].c + +listArr[i].p; 
					var pass_per = (total_pass * 100) / (listArr[i].enrolled - listArr[i].abscnt);

					tableData += '<td>'+listArr[i].specialisation+'</td>';
					tableData += '<td>'+listArr[i].enrolled+'</td>';
					tableData += '<td>'+(listArr[i].enrolled - listArr[i].abscnt)+'</td>';
					tableData += '<td>'+listArr[i].abscnt+'</td>';
					tableData += '<td>'+listArr[i].op+'</td>';
					tableData += '<td>'+listArr[i].o+'</td>';
					tableData += '<td>'+listArr[i].ap+'</td>';
					tableData += '<td>'+listArr[i].a+'</td>';
					tableData += '<td>'+listArr[i].bp+'</td>';
					tableData += '<td>'+listArr[i].b+'</td>';
					tableData += '<td>'+listArr[i].c+'</td>';
					tableData += '<td>'+listArr[i].p+'</td>';
					tableData += '<td>'+listArr[i].f+'</td>';
					tableData += '<td>'+pass_per.toFixed(2)+'</td>';
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