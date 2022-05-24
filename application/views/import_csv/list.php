<div>
    <form id="frmStudent" name="frmStudent" method="post" enctype="multipart/form-data" action="<?php echo base_url('ImportCSV/importStudentData')?>">
        
        <div>
            <?php
                if(isset($errorMessage) && !empty($errorMessage)) {
              
                    if(isset($errorMessage) && is_string($errorMessage)){
                        echo $errorMessage;
                    }
                    else if(isset($errorMessage) && is_array($errorMessage) && count($errorMessage) > 0){
                        echo '<ul class="error_ul"><span><b>Result</b>:</span>';
                        foreach ($errorMessage as $key => $error) {
                            echo '<li>'.($error).'</li>';
                        }
                        echo '</ul>';
                    }
                
                } else if(isset($successMessage) && !empty($successMessage)) {
            ?>
                <div style="color:green;"><?php echo $errorMessage?></div>
            <?php
                }
            ?>
        </div>
        <div>
            <label>Select CSV File: </label>
            <input type="file" name="import_students_file" id="import_students_file"/>
            <input type="submit" id="btn_import_students_file" name="btn_import_students_file" class="" value="Import CSV"/>
        </div>
        
    </form>
</div>