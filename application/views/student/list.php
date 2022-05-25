<html>
    
<head>
	<title>Students Report</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
	<section class="container-fluid">
		<div class="search-div">

		</div>
		<div class="data-div">
			<table class="table table-striped" id="paper_wise_table">
				<thead>
					<tr>
						<th>Paper Code</th>
						<th>Paper Title</th>
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
		</div>
	</section>
</body>

<script type="text/javascript">
	var BASE_URL = "<?php echo base_url()?>";
	var start = 0;
	$(document).ready(function() {

		var data = {};
		__onLoadStudentData(data);


	});

	function __onLoadStudentData(data) {
		$.ajax({
			type:"POST",
			url :BASE_URL+"student/paper-summary/"+start,
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
					$("#paper_wise_table tbody").html("<tr><td colspan='3'>No data found...</td></tr>");
				} else {
					category_arr = listArr;
					$("#paper_wise_table tbody").html(tableData);
				}
			} else {
				tableData += '<tr class="rowId_'+listArr[i].category_id+'">';
				
					var total_pass = +listArr[i].op + +listArr[i].o + +listArr[i].ap + +listArr[i].a + +listArr[i].bp + +listArr[i].b + +listArr[i].c + +listArr[i].p; 
					var pass_per = (total_pass * 100) / (listArr[i].enrolled - listArr[i].abscnt);

					tableData += '<td>'+listArr[i].papercode+'</td>';
					tableData += '<td>'+listArr[i].papertitle+'</td>';
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
					setTableFormatData(start,resultArr);
				}
			});
			return false;
		});
	}

</script>

</html>