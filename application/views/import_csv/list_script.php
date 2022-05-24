<script type="text/javascript">
    var BASE_URL = "<?php echo base_url()?>";
    $(document).ready(function(){
        onLoadDashboardDataGet();
    });

    function onLoadDashboardDataGet() {
        $.ajax({
            type : "POST",
            url  : BASE_URL+"dashboard/getDashboardData",
            success:function(response) {
                var resultArr = JSON.parse(response);
                
                if(resultArr.status == 1) {
                    $("#totalUser").text(resultArr.totalUser);
                    $("#totalLabUser").text(resultArr.totalLabUser);
                    $("#totalNormalUser").text(resultArr.totalNormalUser);
                    $("#totalCategory").text(resultArr.totalCategory);
                    $("#totalTest").text(resultArr.totalTest);
                    $("#totalBooking").text(resultArr.totalBooking);
                } else if(resultArr.status == 2) {
                    location.reload();
                }
            }
        });
    }
</script>