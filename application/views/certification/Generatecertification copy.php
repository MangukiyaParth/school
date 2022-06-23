<!DOCTYPE html>
<html>
  <head>
    <title>Certificate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    
    <style>
      html,body{      
      border: solid #375376;
      border-width: 18px;
      overflow:hidden;
      display:block;
      margin-bottom: -18px !important;
      }
      @page {       
        margin: 0;
      }
      
      *{
        padding:0;
        margin:0;
        list-style:none;
         -webkit-box-sizing: border-box;
          -moz-box-sizing: border-box;
             box-sizing: border-box;
      }
      .clearfix::after {
        content: "";
        clear: both;
        display: table;
      }
      ul,ol{margin:0px;padding:0px;}
      h1,h2,h3,h4,h5,h6,p{margin:0px; padding:0px;}
      a, a:hover, a:focus{text-decoration:none;}
      img{width:100%;}
      .page_wrapper{
        border: 18px solid #375376;
        height:724px;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      .logospace{
        padding-left: 5px;
      }
      .logo{
        padding: 0px 20px 0px 0px;
      }
      .heading h1{
        font-size: 36px;
        font-weight: bold;
      }
      .mid_heading{
        text-align:center;
      }
      .mid_heading h2{
        font-size:35px; 
        color:#eeb741;
        font-weight:bold;
        letter-spacing: 2px;
      }
      .mid_heading  img{
        padding: 0px 50px;
      }
      .content h6{
        color:#a8a8a8;
        font-size:45px;
        padding-bottom: 15px;
        letter-spacing: 2px;
      }
      .content p{
        color: #486282;
        font-size: 22px;
        font-weight: 500;
      }
      .content span{
        color: #375376;
        font-size: 22px;
        font-weight: bold;
      }
      .sign { 
        padding-top: 35px;
      }
      h4{
        font-weight: bold;
        color: #375376;
        font-size: 18px;
        text-align:center;
      }
      .sign p{
        font-size: 14px;
        padding-bottom: 14px;
        color:#486282;
        font-weight: 600;
      }
      .heading1 h6 {
        color: #486282;
        font-size: 22px;
        padding-top: 18px;
      }
      .bordertop1{
        padding:4px 0px;
      }
      .bordertop2{
        padding:4px 0px;
      }
      .bordertop1 span{
        padding:4px 0px;
        text-align:center;
        position:relative;
      }
      
        
      .bordertop1 span:after{
        border-top:2px solid #375376;
        position:absolute;
        width:170px;
        left:132px;
      }
      .bordertop2 span{
        padding:4px 0px;
        text-align:center;
        position:relative;
      }
      span.first_border {
        display: block;
        max-width: 50%;
        margin: 15px auto 0;
        border-top: 2px solid #375376;
        font-weight: bold;
        color: #375376;
        font-weight:bold;
      }
      .bordertop2 span:after{
        border-top:2px solid #375376;
        position:absolute;
        width:200px;
        left:116px;
      }
      @media only screen and (max-width: 768px) {
        
      } 
    </style>
  </head>
  <body>
    <table>
          <tr>
            <td width="20%">
            <img src="<?php echo base_url() ?>public/dist/img/logo11.jpg" alt="img" style="width:190px;margin-left:20px;"></td>
            <td class="heading" style="">
              <h1 style="text-align:center;display:inline-block;width:100%;margin-top:10px;">
                <span style="font-weight:900;letter-spacing:1px;text-transform:uppercase;"><span style="color:#018ccd">MOO</span><span style="color:#bcd34f">C</span> ACADEMY</span>
                <br>
                <span style="font-size:20px;"> Enable Education,Online.</span>
                 <br>
                 <span style="font-size:28px; text-transform:uppercase;">Maniben Nanavati Women`s college</span>
                  <br>
                  <span style="font-size:20px;text-transform:uppercase;"> Best College 2018-2019</span>
                  <br>
                  <span style="font-size:14px;">(A Gujarati Lingustice Minority College,Affiliated To SNDT Women`s University, Mumbai)</span>
                  </h1>
                </td> 
            <td class="logo" style="width:20%; text-align:right;"><img src="<?php echo base_url() ?>public/dist/img/logo2.jpg" alt="img" style="width:200px;"></td>
          </tr>
          <tr>
            <td></td>
            <td class="mid_heading"><h2 style="padding-top:10px;margin-top:20px;">CERTIFICATE</h2>
            <img src="<?php echo base_url() ?>public/dist/img/midlogo.jpg" alt="img" style="width:400px; text-align:center;"></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class="content">
            <p style="font-weight:normal; font-size:20px; text-align:center; margin-left:175px; margin-right:175px;">
              This is to certify that Ms. <?php echo $student_name ?> of <?php echo $course_name ?> has successfully completed <i>"<?php echo $subject ?>"</i> </p>
            </td>
          </tr>
          <tr>
            <td colspan="3" class="heading1"><h6 style="text-align:center;margin-top:20px; font-weight:normal;">
              <?php if($gender == 'Male') { echo 'He'; } else { echo 'She'; } ?> has completed the course with <?php echo $no_of_hours ?> hours training, securing <?php echo $percent ?>%
  with <?php echo $grade ?> Grade.</h6>
            </td>
          </tr>
          
          <tr>
            <td colspan="3" style="text-align:center;padding-top:50px;">
              <table style="text-align:center;">
                <tr>
                  <td width="33%">
                    <table style="text-align:center;">
                      <tr><td><img src="<?php echo base_url() ?>public/dist/img/Signature.jpg" alt="img" style="width:150px;"></td></tr>
                      <tr class="border_bottom"><td><span class="first_border">Dr. Rajshree Trivedi</span></td></tr>
                      <tr><td>Principal</td></tr>
                      <tr><td>M.N.W.C</td></tr>
                    </table>
                  </td>
                  <td width="33%">
                    <table style="text-align:center;">
                      <tr><td>&nbsp;</td></tr>
                      <tr><td>&nbsp;</td></tr>
                      <tr><td>&nbsp;<br></td></tr>
                      <tr><td>Date: <?php echo $date ?></td></tr>
                    </table>
                  </td>
                  <td width="33%">
                    <table style="text-align:center;">
                      <tr><td><img src="<?php echo base_url() ?>public/dist/img/Viren-Signature.jpg" alt="img" style="width:150px; "></td></tr>
                      <tr class="border_bottom"><td><span class="first_border">Viren Shah</span></td></tr>
                      <tr><td>Course Coordinator</td></tr>
                      <tr><td>MOOC Academy</td></tr>
                    </table>              
                  </td>
                </tr>           
              </table>            
            </td>
          </tr>
        </table>      
  </body>
</html>