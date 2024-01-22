<?php
session_start();  
require '../vendor/autoload.php';
use Twilio\Rest\Client;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use Cloudinary\Uploader;
// use Cloudinary\Api;
use chillerlan\QRCode\{QRCode, QROptions};
$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

// Replace these with your Cloudinary credentials
// $cloudName = $_SERVER['CLOUDINARY_NAME'];
// $apiKey = $_SERVER['CLOUDINARY_API_KEY'];
// $apiSecret = $_SERVER['CLOUDINARY_SECRET_KEY'];

// // Replace these values with your Cloudinary credentials
// \Cloudinary::config(array(
//   'cloud_name' => $cloudName,
//   'api_key' => $apiKey,
//   'api_secret' => $apiSecret,
//   'url' => ['secure' => true]
// ));

// $cloudinary = new Cloudinary([
//     'cloud' => [
//         'cloud_name' => $cloudName,
//         'api_key' => $apiKey,
//         'api_secret' => $apiSecret,
//         'url' => ['secure' => true]
//     ]
// ]);

// function generateQRCode($data)
// {
//     $qrcode = new QRCode();

//     // Generate QR code from data
//     $image = $qrcode->render($data);

//     return $image;
// }

// //switch envs
// $base_url = ($_SERVER['APP_ENV'] == "local") ? $_SERVER['LOCAL_URL'] : $_SERVER['LIVE_URL'];
// $data = $base_url . "ok";
// $qrCodeImage = generateQRCode($data);
// echo $qrCodeImage;
// exit;

// $svgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrCodeImage));

// // Upload the SVG data to Cloudinary
// $uploadResult = Uploader::upload($svgData, array(
//     "resource_type" => "image",
//     "format" => "svg", // Specify the format to make sure it's treated as an SVG
//     "public_id" => "desired_public_id", // Optionally set a public ID for the file
// ));

// // Output the Cloudinary URL of the uploaded image
// echo $uploadResult;
// exit;

if($_SERVER['APP_ENV'] == 'local')
{
  $conn=mysqli_connect('localhost','root','','iNotifi') or die('Could not Connect My Sql:'.mysql_error());
}
else
{
  $conn=mysqli_connect('localhost','root',$_SERVER['DB_LIVE_PASSWORD'],'iNotifi') or die('Could not Connect My Sql:'.mysql_error());
}
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

if (isset($_POST['sendnote'])) {
    function generateQRCode($data)
    {
        $qrcode = new QRCode();

        // Generate QR code from data
        $image = $qrcode->render($data);
        return $image;
    }

    function generateRandomString($length = 10) {
      $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      $randomString = '';
  
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
  
      return $randomString;
  }
    // echo $qrCodeImage;
    // exit;
    
    $dept = $_POST['dept'];
    $lev = $_POST['lev'];
    
    // Fetch students from the specified department and level
    $studentsQuery = mysqli_query($conn, "SELECT * FROM student WHERE `department`='$dept' AND `level`='$lev'");
    
    // Get the total number of seats
    $seatno = $_POST['seat'];
    $rowCount = mysqli_num_rows($studentsQuery);

    if($seatno < $rowCount)
    {
      echo '<script>alert("More seats needed!")</script>';
      exit;
    }

    // Random number for seat allocation
    $nums = range(1, $seatno);
    shuffle($nums);

    // Initialize arrays to store seat values and surnames
    $seatvals = [];
    $surnames = [];

    while ($row = mysqli_fetch_array($studentsQuery)) {
      $seatvals[] = array_pop($nums);
      $surnames[] = $row['id'];
    }

    // // Sort arrays
    // sort($seatvals);
    // sort($surnames);

    $date = date("Y-M-d, h:i:sa");
    $examdate = $_POST['examtime'];
    $reference = generateRandomString();
    $course_name = $_POST['course'];
    $exam_date = $_POST['examtime'];
    $exam_duration = $_POST['duration'];
    $exam_venue = $_POST['venue'];
    $hall_size= $seatno;


    //switch envs
    $base_url = ($_SERVER['APP_ENV'] == "local") ? $_SERVER['LOCAL_URL'] : $_SERVER['LIVE_URL'];
    $data = $base_url . '/dashboard/allocate.php?reference=' . $reference;
    $qrCodeImage = generateQRCode($data);
    // echo $qrCodeImage;
    // // exit;

    // Extract the MIME type and base64-encoded data
    list($mime, $data) = explode(';', $qrCodeImage);
    list(, $data)      = explode(',', $data);

    // Decode the base64-encoded data
    $decodedData = base64_decode($data);

    // // Create a temporary file for the SVG data
    // $tempFile = tempnam(sys_get_temp_dir(), 'cloudinary_svg');
    // file_put_contents($tempFile, $decodedData);

    // // Upload the SVG file to Cloudinary
    // $uploadResult = \Cloudinary\Uploader::upload($tempFile, array(
    //     "resource_type" => "image",
    //     "format" => "svg",
    //     "folder" => "uploads" // Optional: specify a folder in Cloudinary
    // ));

    // echo 'Uploaded SVG URL: ' . $uploadResult['secure_url'];
    // exit;
    // Specify the full path to the root folder and generate a unique filename with the correct extension
    $rootFolder = "../"; // Assuming this script is in the root folder
    $uniqueFileName = generateRandomString();
    $svgFilename = $rootFolder . 'input_image_' . $uniqueFileName. '.svg';
    $pngFilename = $rootFolder . 'output_image_' . $uniqueFileName . '.png';

    // Save the decoded SVG data to a file
    file_put_contents($svgFilename, $decodedData);

    // Use Inkscape to convert SVG to PNG
    if($_SERVER['APP_ENV'] == 'local')
    {
      $svgPath = "/opt/homebrew/bin/inkscape";
      $command = $svgPath . ' --export-type=png --export-filename=' . $pngFilename . ' --export-dpi=600 ' . $svgFilename ;
      exec($command, $output, $returnCode);
    }
    else
    {
      $svgPath = "/usr/bin/inkscape";
      $command = $svgPath . ' --export-png=' . $pngFilename . ' --export-dpi=600 ' . $svgFilename;
      exec($command, $output, $returnCode);
    }
    



    // Insert allocation details into the exam table
    for ($y = 0; $y < count($surnames); $y++) {
        $curseat = $seatvals[$y];
        $student_id = $surnames[$y];

        // echo $student_id . ", " . $reference . ", " . $course_name . ", " . $exam_date . ", " . $curseat . ", " . $exam_duration . ", " . $exam_venue . ", " . $hall_size . ", " . $date;
        // echo gettype($student_id) . ", " . gettype($reference) . ", " . gettype($course_name) . ", " . gettype($exam_date) . ", " . gettype($curseat) . ", " . gettype($exam_duration) . ", " . gettype($exam_venue) . ", " . gettype($hall_size) . ", " . gettype($date);
        // exit;
        // echo $student_id;
        // Insert into exam table
        $que = mysqli_query($conn, "INSERT INTO `exam`(`student_id`, `reference`, `course_name`, `exam_date`, `seat`, `exam_duration`, `exam_venue`, `hall_size`, `date`) 
        VALUES ('$student_id','$reference','$course_name', '$exam_date', '$curseat', '$exam_duration', '$exam_venue', '$hall_size', '$date')");

        $examdetails = "EXAMINATION DETAILS\nCourse Code/Title: " . $_POST['course'] . "\nExamination Venue: " . $_POST['venue'] .
            "\nExamination Date and Time: " . $_POST['examtime'] . "\nDuration: " . $_POST['duration'] .
            " minutes\nDepartment: " . $_POST['dept'] . "\nLevel: " . $_POST['lev'] . "\nExamination Seat: " . $curseat;

        // Fetch phone number from the student table
        $phoneQuery = mysqli_query($conn, "SELECT `phone` FROM `student` WHERE `id` = '$student_id'");
        $phoneRow = mysqli_fetch_array($phoneQuery);
        $phoneNumber = "+" . $phoneRow['phone'];

        // // Send SMS using Twilio
        // $account_sid = 'ACc8dc4617e2231412a7e4eaeb355608aa';
        // $auth_token = 'a2a95f8be993c1e527e8ed4f1ffb1374';
        // $twilio_number = "+2349053219099";

        // $client = new Client($account_sid, $auth_token);

        // $validation_request = $client->validationRequests
        //                      ->create("+2349053219099", // phoneNumber
        //                               ["friendlyName" => "My Home Phone Number"]
        //                      );
        // exit;

        // $client->messages->create(
        //     $phoneNumber,
        //     array(
        //         'from' => $twilio_number,
        //         'body' => $examdetails
        //     )
        // );

        // Fetch email from the student table
        $emailQuery = mysqli_query($conn, "SELECT `email` FROM `student` WHERE `id` = '$student_id'");
        $emailRow = mysqli_fetch_array($emailQuery);
        $to = $emailRow['email'];
        $subject = "Examination Info for " . $_POST['course'];
        $message = $examdetails;
        $from = "From: olukoyajoshua72@gmail.com";

        $firstnameQuery = mysqli_query($conn, "SELECT `firstname` FROM `student` WHERE `id` = '$student_id'");
        $firstnameRow = mysqli_fetch_array($firstnameQuery);
        $firstname = $firstnameRow['firstname'];

        $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername('technitedevs@gmail.com');
        $transport->setPassword('eydrnlsiubowrsdh');

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Test Email'))
            ->setFrom(['technitedevs@gmail.com' => 'Exam Admin'])
            ->setTo([$to => $firstname])
            ->setBody(
              '<html>
                  <body>
                      <p>Hello ' . $firstname. ', kindly find the detailed info for your exam.</p>
                      <p>Course Name: ' . $course_name . '</p>
                      <p>Exam Date: ' . $exam_date . '</p>
                      <p>Allocated Seat: ' . $curseat . '</p>
                      <p>Exam Duration: ' . $exam_duration . '</p>
                      <p>Exam Venue: ' . $exam_venue . '</p>
                      <p>Hall Size: ' . $hall_size . '</p>
                      <p>Date: ' . $date . '</p>
                      <p>Department: ' . $_POST['dept'] . '</p>
                      <p>Level: ' . $_POST['lev'] . '</p>
                      <img src="../output_image_' . $uniqueFileName . ' alt="QR Code">
                      <p>You can scan the above QR Code to see full details.</p>
                  </body>
                </html>
                ', 'text/html'
            );

        $imagePath = $pngFilename;
        $imageData = file_get_contents($imagePath);
        $image = new Swift_Image($imageData, 'image.png', 'image/png');
        $message->attach($image);

        $mailer->send($message);
      
    }

    echo '<script>alert("Info Sent!")</script>';
}


// if(isset($_POST['sendnote'])){

//   function generateQRCode($data)
//   {
//       $qrcode = new QRCode();
  
//       // Generate QR code from data
//       $image = $qrcode->render($data);
  
//       return $image;
//   }
  
//   //switch envs
//   $base_url = ($_SERVER['APP_ENV'] == "local") ? $_SERVER['LOCAL_URL'] : $_SERVER['LIVE_URL'];
//   $data = $base_url . $query;
//   $qrCodeImage = generateQRCode($data);
//   echo $qrCodeImage;
//   exit;
//   // echo '<img src="'.$qrCodeImage.'" alt="QR Code" />';
//   // exit;

//   $random_number = rand(100000, 999999);
//   $dept = $_POST['dept'];
//   $lev = $_POST['lev'];
//   $va = mysqli_query($conn,"select * from student WHERE `department`='$dept' AND `level`='$lev'");
//   $seatno = $_POST['seat'];
  
  
//   while($row = mysqli_fetch_array($va)){
                
//     $seatvals[] = rand(1,$seatno);
//     $surnames[] = $row['matric no'];
//     }
//     sort($seatvals);
//     sort($surnames);
//     $curdate = date("Y-M-d, h:i:sa");
//     for ($y = 0; $y < mysqli_num_rows($va); $y++) {
//       $curseat = $seatvals[$y];
//       $curname = $surnames[$y];
//       $que = mysqli_query($conn,"INSERT INTO `exam`(`name`, `seat`, `date`) VALUES ('$curname','$curseat','$curdate')");
//       $examdetails = "EXAMINATION DETAILS\nCourse Code/Title: " . $_POST['course'] . "\nExamination Venue: " .$_POST['venue'].
//       "\nExamination Date and Time: " .$_POST['examtime']. "\nDuration: " .$_POST['duration']. 
//       "minutes\nDepartment: " .$_POST['dept']."\nLevel: " .$_POST['lev']. "\nExamination Seat: " .$curseat;
     
//       $ques = mysqli_query($conn,"SELECT  `phone` FROM `student` WHERE `matric no` = '$curname'");
//       $rows = mysqli_fetch_array($ques);
//       $rowss = "+" . $rows['phone'];

//       //config for mails 
//       $quest = mysqli_query($conn,"SELECT  `email` FROM `student` WHERE `matric no` = '$curname'");
//       $rowmail = mysqli_fetch_array($quest);
//       $to = $rowmail['email'];
//       $sub = "Examination Info for " .$_POST['course'];
//       $message = $examdetails;
//       $from = "From: iNotifi6@gmail.com";
//       mail($to,$sub,$message,$from);
          
//       // Your Account SID and Auth Token from twilio.com/console
//       $account_sid = 'AC039121e48fa58c46c42f3f97ea5bb80c';
//       $auth_token = 'ee0460591e3ed7d65746dcd9badcc0e3';
//       // In production, these should be environment variables. E.g.:
//       // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
      
//       // A Twilio number you own with SMS capabilities
//       $twilio_number = "+12065043061";
      
//       $client = new Client($account_sid, $auth_token);
//       $client->messages->create(
//           // Where to send a text message (your cell phone?)
//           $rowss,
//           array(
//               'from' => $twilio_number,
//               'body' => $examdetails
//           )
//       );


//             }
//             echo '<script>alert("Info Sent!")</script>';

// }
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
      $from = "From: iNotifi6@gmail.com";
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


    <title> iNotifi </title>

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
                        <span>STUDENT EXAM VENUE ALLOCATION SYSTEM</span>

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
              <select name="venue" class="form-control" id="venue" required>
              <option value="">--Choose Your Department--</option>
              <option value="Multipurpose Hall">Multipurpose Hall</option>
              <option value="New Building Room 3">New Building Room 3</option>
              <option value="New Building Room 4">New Building Room 4</option>
              <option value="Food Technology Building Room 2A">Food Technology Building Room 2A</option>
              <option value="Food Technology Building Room 2B">Food Technology Building Room 2B</option>
              <option value="Microbiology Room 1">Microbiology Room 1</option>
              <option value="Microbiology Room 2">Microbiology Room 2</option>
              <option value="Polymer & Textile Room 201">Polymer & Textile Room 201</option>
              <option value="Polymer & Textile Room 204">Polymer & Textile Room 204</option>
              <option value="NUSB 015">NUSB 015</option>
              <option value="NASB 015">NASB 015</option>
              <option value="NASB 109">NASB 109</option>
              <option value="NASB 115">NASB 115</option>
              <option value="NASB 209">NASB 209</option>
          </select>
          </div>
              <div class="form-group required">
                    
              <input class="form-control" type="text" name="seat" id="seat" readonly>
                  </div>
                 
                      
	            <div class="form-group captcha-box">
	                                                <!--<div class="g-recaptcha" data-sitekey="6Lfwu8sUAAAAAGi3hFs-D8F8o2ZLI1mzBA2fIRiS" data-callback="enableBtn"></div>--> 
                                                    <div class="submit">
		            		<input class="btn btn-primary" style="color:#ffffff; text-align:center;" name="sendnote" id="signInButton1" type="submit" value="Allocate!" disabled="">
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

      $(document).ready(function () {
        // Attach an event listener to the venue select dropdown
        $("#venue").change(function () {
            var selectedVenue = $(this).val();

            // Fetch the corresponding hall size
            var hallSizes = {
                'Multipurpose Hall': 100,
                'New Building Room 3': 128,
                'New Building Room 4': 120,
                'Food Technology Building Room 2A': 170,
                'Food Technology Building Room 2B': 93,
                'Microbiology Room 1': 104,
                'Microbiology Room 2': 80,
                'Polymer & Textile Room 201': 24,
                'Polymer & Textile Room 204': 100,
                'NUSB 015': 58,
                'NASB 015': 80,
                'NASB 109': 54,
                'NASB 115': 50,
                'NASB 209': 62
                // Add other venue options here
            };
            
            var selectedHallSize = hallSizes[selectedVenue];

            // Update the hall size input field
            $("#seat").val(selectedHallSize);
        });
    });


    </script>

    
</body>
</html>
<?php
$conn=mysqli_connect('localhost','root','','iNotifi') or die('Could not Connect My Sql:'.mysql_error());
mysqli_close($conn);}
?>