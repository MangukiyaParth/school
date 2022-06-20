<html>
    
<head>
	<title>Document List</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
	</style>
</head>

<body>
	<section class="container-fluid">
		<div class="search-div row justify-content-center" style="margin-bottom: 25px; margin-top: 25px;">
			<div class="col-sm-offset-2 col-sm-4">
				<label class="form-label"> Document Title </label>
				<input type="text" name="search" id="search" class="form-control" />
			</div>
			<div class="col-sm-2">
				<label class="form-label"> Date Range </label>
				<input type="text" name="date" id="date" class="form-control" value="" />
			</div>
			<div class="col-sm-1">
				<br/>
				<button class="btn btn-primary" id="apply_filter">Apply</button>
			</div>
		</div>
		<div class="data-div">
			<table class="table table-striped" id="paper_wise_table">
				<thead>
					<tr class="table-primary">
						<th>Document No</th>
						<th>Document Type</th>
						<th>Document Status</th>
						<th>Document Title</th>
						<th>Updated Date</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
			<div class="pagination">
				<span class="page-info"></span>
				<span class="pagination-nav"></span>
			</div>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var start = 0;
	$(document).ready(function() {
		var data = {"search": "", "daterange": ""};
		__onLoadStudentData(data);
		
		$("#apply_filter").on("click", function(){
			var search = $("#search").val();
			var daterange = $("#date").val();			
			var data = {"search": search, "daterange": daterange};
			__onLoadStudentData(data);
		});

		$('#date').daterangepicker({
			autoUpdateInput: false,
		}, function(start, end, label) {
			console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});

		$('#date').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
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
				if(listArr[i].UniqueName.startsWith("I"))
				{
					var docType = "Inward";
				}
				else
				{
					var docType = "Outward";
				}

				tableData += '<tr class="rowId_'+listArr[i].id+'">';
					tableData += '<td><a href="'+BASE_URL+'document/documentDetails/'+listArr[i].id+'">'+listArr[i].UniqueName+'</a></td>';
					tableData += '<td>'+docType+'</td>';
					tableData += '<td>'+listArr[i].DocumentStatus+'</td>';
					tableData += '<td>'+listArr[i].DocumentTitle+'</td>';
					tableData += '<td>'+listArr[i].LastModified_format+'</td>';
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