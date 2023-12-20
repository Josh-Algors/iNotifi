<?php   
session_start();  
require __DIR__ . '\vendor\autoload.php';
use Twilio\Rest\Client;

$conn=mysqli_connect('localhost','root','','iNotify') or die('Could not Connect My Sql:'.mysql_error());
if(!isset($_SESSION["admin"])){  
    header("location:../login/index.htm");  
}if(isset($_SESSION["login"]) && (!isset($_SESSION["admin"]))){  
    header("location:../dashboard/index.php");  
}
if(isset($_SESSION["login"]) && (isset($_SESSION["admin"]))){  
    header("location:../dashboard/logout.php");  
}
if(isset($_SESSION["admin"]) && !isset($_SESSION["login"])) {  

$_SESSION["registered"]="admin";
if(isset($_POST['sendnote'])){
  $dept = $_POST['dept'];
  $lev = $_POST['lev'];
  $va = mysqli_query($conn,"select * from student WHERE `department`='$dept' AND `level`='$lev'");
  $seatno = $_POST['seat'];
  
  
  while($row = mysqli_fetch_array($va)){
                
    $seatvals[] = rand(1,$seatno);
    $surnames[] = $row['matric no'];
    }
    sort($seatvals);
    sort($surnames);
    $curdate = date("Y-M-d, h:i:sa");
    for ($y = 0; $y < mysqli_num_rows($va); $y++) {
      $curseat = $seatvals[$y];
      $curname = $surnames[$y];
      $que = mysqli_query($conn,"INSERT INTO `exam`(`name`, `seat`, `date`) VALUES ('$curname','$curseat','$curdate')");
      $examdetails = "EXAMINATION DETAILS\nCourse Code/Title: " . $_POST['course'] . "\nExamination Venue: " .$_POST['venue'].
      "\nExamination Date and Time: " .$_POST['examtime']. "\nDuration: " .$_POST['duration']. 
      "minutes\nDepartment: " .$_POST['dept']."\nLevel: " .$_POST['lev']. "\nExamination Seat: " .$curseat;
     
      $ques = mysqli_query($conn,"SELECT  `phone` FROM `student` WHERE `matric no` = '$curname'");
      $rows = mysqli_fetch_array($ques);
      $rowss = "+" . $rows['phone'];

      //config for mails 
      $quest = mysqli_query($conn,"SELECT  `email` FROM `student` WHERE `matric no` = '$curname'");
      $rowmail = mysqli_fetch_array($quest);
      $to = $rowmail['email'];
      $sub = "Examination Info for " .$_POST['course'];
      $message = $examdetails;
      $from = "From: inotify6@gmail.com";
      mail($to,$sub,$message,$from);
          
      // Your Account SID and Auth Token from twilio.com/console
      $account_sid = 'AC039121e48fa58c46c42f3f97ea5bb80c';
      $auth_token = 'ee0460591e3ed7d65746dcd9badcc0e3';
      // In production, these should be environment variables. E.g.:
      // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
      
      // A Twilio number you own with SMS capabilities
      $twilio_number = "+12065043061";
      
      $client = new Client($account_sid, $auth_token);
      $client->messages->create(
          // Where to send a text message (your cell phone?)
          $rowss,
          array(
              'from' => $twilio_number,
              'body' => $examdetails
          )
      );


            }
            echo '<script>alert("Info Sent!")</script>';

}
if(isset($_POST['sendmessage'])){
  $vals = mysqli_query($conn,"select * from student ");
  $message = $_POST['message'];
  $curdate = date("Y-M-d, h:i:sa");
  $que = mysqli_query($conn,"INSERT INTO `messages`(`message`,  `date`) VALUES ('$message','$curdate')");
    for ($y = 0; $y < mysqli_num_rows($vals); $y++) {
      $rowval = mysqli_fetch_array($vals);
      $rownum = "+" . $rowval['phone'];

      //config for mails 
      $to = $rowval['email'];
      $sub = "Important Notice!!";
      $message = $message;
      $from = "From: inotify6@gmail.com";
      mail($to,$sub,$message,$from);
          
      // Your Account SID and Auth Token from twilio.com/console
      $account_sid = 'AC039121e48fa58c46c42f3f97ea5bb80c';
      $auth_token = 'ee0460591e3ed7d65746dcd9badcc0e3';
      // In production, these should be environment variables. E.g.:
      // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
      
      // A Twilio number 
      $twilio_number = "+12065043061";
      
      $client = new Client($account_sid, $auth_token);
      $client->messages->create(
          // Where to send a text message (your cell phone?)
          $rownum,
          array(
              'from' => $twilio_number,
              'body' => $message
          )
      );


            }
            echo '<script>alert("Info Sent!")</script>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
      
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162222857-3"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'UA-162222857-3');
        </script>

      
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="description" content="iTrans DATA. Recharge Smarter.">
    <meta name="keywords" content="">


    <meta name="google-site-verification" content="">
    <meta name="naver-site-verification" content="">


    <title> iNotify </title>

    <link rel="icon" type="image/jpg" href="../yaba.png">

      
    

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap_1680.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style_front_1680.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

    <script type="text/javascript" async="" src="../js/_Incapsula_Resource.js"></script>
    <script async="" src="../js/analytics.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/svg4everybody.legacy.min.js"></script>

    <!-- Open graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Largest Recharging website ">
    <meta property="og:description" content="">
            <meta property="og:url" content="">
        <meta property="og:image" content="../yaba.png">

<link href="css/widget.css" rel="stylesheet">
</head>

<body >

<br />
<div style="background-image:url('datap.jpg'); filter:blur(8px); -webkit-filter:blur(8px); height:100%; background-position:center; background-repeat:no-repeat; background-size:cover;">
</div>
<div  class="container">
    <div class="row splash-main">
    
        <div class="col-xs-6 col-xs-offset-3 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <h4>Welcome, <?=$_SESSION['admin'];?>!  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<a href="logout.php">Logout</a></h4>
            <br/>
                        <span>STUDENT INFO SYSTEM</span>

            <form action="" id="UserLoginNowForm" method="post" accept-charset="utf-8">
	            <div style="display:none;"><input type="hidden" name="_method" value="POST">
                 <input type="hidden" name="key" value="6Lfwu8sUAAAAAGi3hFs-D8F8o2ZLI1mzBA2fIRiS" id="Token1532892527">
	            </div>
              <div class="form-group required">
            <select name="dept" class="form-control" id="pet-select" required>
              <option value="">--Choose Your Department--</option>
              <option value="Computer Technology">Computer Technology</option>
              <option value="Food Technology">Food Technology</option>
              <option value="Polymer & Textile">Polymer & Textile</option>
              <option value="Hospitality Management">Hospitality Management</option>
              <option value="Nutrition & Dietics">Nutrition & Dietics</option>
              
          </select>
          </div>
          <div class="form-group required">
            <select name="lev" class="form-control" id="pet-select" required>
              <option value="">--Choose Your Level--</option>
              <option value="nd1">ND1</option>
              <option value="nd2">ND2</option>
              <option value="hnd1">HND1</option>
              <option value="hnd2">HND2</option>
              
              
          </select>
          </div>
          <div class="form-group required">
                 <input name="course" class="form-control" placeholder="Course Code and Title" type="text" id="course" required="required">
	            </div>

                <div class="form-group required">
                 <input name="examtime" class="form-control" placeholder="Exam Date" type="datetime-local" id="terminal1" required="required">
	            </div>

              <div class="form-group required">
                    
	            	<input name="duration" class="form-control" placeholder="Exam Duration" type="text" id="duration" required="required">
	            </div>

                <div class="form-group required">
                    
	            	<input name="venue" class="form-control" placeholder="Exam Venue" type="text" id="venue" required="required">
	            </div>
              <div class="form-group required">
                    
                    <input name="seat" class="form-control" placeholder="Hall Size" type="text" id="seat" required="required">
                  </div>
	            
	            <div class="form-group captcha-box">
	                                                <!--<div class="g-recaptcha" data-sitekey="6Lfwu8sUAAAAAGi3hFs-D8F8o2ZLI1mzBA2fIRiS" data-callback="enableBtn"></div>--> 
                                                    <div class="submit">
		            		<input class="btn btn-primary" style="color:#ffffff; text-align:center;" name="sendnote" id="signInButton1" type="submit" value="Send Notfication!" disabled="">
		            	</div>
                        
                       

		            	<div style="display:none;">
		            		<input type="hidden" name="data[_Token][fields]" value="a8f894205ef2839927d5ad906c061462f4424136%3A" id="TokenFields2092665347">
		            		<input type="hidden" name="data[_Token][unlocked]" value="User.form_type%7Cg-recaptcha-response" id="TokenUnlocked836959454">
		            	</div>
		        </div>
            </form>
<br/>
            <form method="post" action="">
           
            <div class="form-group required">
                    
                    <textarea rows="5" cols="40" name="message" class="form-control" placeholder="Send Message" type="text" id="message" required="required"></textarea>
                  </div>
    
<div class="submit">
		            		<input class="btn btn-primary" style="color:#ffffff; text-align:center;" name="sendmessage" id="signInButton2" type="submit" value="Send Message" disabled="">
		            	</div>
</form>
            <form method="post" action="registered.php">
            <div class="submit">
		            		<input class="btn btn-primary" style="color:#ffffff; text-align:center;" name="viewreg" id="signInButton" type="submit" value="View Registered Students" disabled="">
		            	</div>
</form>

        </div>
    </div>

</div>
<script type="text/javascript">
              document.getElementById("signInButton").disabled = false;
            function enableBtn(){
        document.getElementById("signInButton").disabled = false;
      }

      document.getElementById("signInButton1").disabled = false;
            function enableBtn(){
        document.getElementById("signInButton1").disabled = false;
      }
      document.getElementById("signInButton2").disabled = false;
            function enableBtn(){
        document.getElementById("signInButton2").disabled = false;
      }


    </script>

    
</body>
</html>
<?php
$conn=mysqli_connect('localhost','root','','iNotify') or die('Could not Connect My Sql:'.mysql_error());
mysqli_close($conn);}
?>