<?php
require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

if($_SERVER['APP_ENV'] == 'local')
{
  $conn=mysqli_connect('localhost','root','','iNotifi') or die('Could not Connect My Sql:'.mysql_error());
}
else
{
  $conn=mysqli_connect('localhost','root',$_SERVER['DB_LIVE_PASSWORD'],'iNotifi') or die('Could not Connect My Sql:'.mysql_error());
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
  // Sanitize the id value
  $id = $_GET['id'];

  // Prepare SQL statement
  $sql = "SELECT e.id, s.firstname, s.lastname, s.matric_no, s.department, s.level, e.course_name, e.exam_date, e.seat, e.exam_duration, e.exam_venue, e.hall_size
          FROM exam e
          JOIN student s ON e.student_id = s.id
          WHERE e.id = ?";
      
  $stmt = mysqli_stmt_init($conn);

  if ($stmt->prepare($sql)) {

  $stmt->bind_param("s", $id);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();


?>
<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="description" content="iNotifi.">
    <meta name="keywords" content="">


    <meta name="google-site-verification" content="">
    <meta name="naver-site-verification" content="">


    <title> iNotifi </title>

    <style>
        dt, dd {
            display: inline; /* Set display property to inline */
            margin-bottom: 10px; /* Add some margin for spacing */
        }
        dt {
          text-align: left;
        }
    </style>

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
        <meta property="og:image" content="img/yaba.png">

<link href="css/widget.css" rel="stylesheet">
</head>

<body id="gm-home">


<div class="gm-feature-box" id="gm-home-3" style="margin-bottom: -40px;">
              <div class="container">
                  <div class="row">
                      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 ">
                          
                           </div>
                      
                      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 ">
                      <img src="../success.gif" height="250" width="400" style='align-item:center'; >
                      <br/>
                        <h3>Examination Info!.</h3>
                        <?php
                        if ($result->num_rows > 0) {
                          // Output data of each row
                          while ($row = $result->fetch_assoc()) {
                              echo '<dl>';
                              echo '<dt> Name: ' . $row["firstname"] . " " . $row["lastname"] . '</dt><br/>';
                              echo '<dt> Matric No: ' . $row["matric_no"] . '</dt><br/>';
                              echo '<dt> Department: ' . $row["department"] . '</dt><br/>';
                              echo '<dt> Level: ' . $row["level"] . '</dt><br/>';
                              echo '<dt> Course Name: ' . $row["course_name"] . '</dt><br/>';
                              echo '<dt> Exam Date: ' . $row["exam_date"] . '</dt><br/>';
                              echo '<dt> Seat No: ' . $row["seat"] . '</dt><br/>';
                              echo '<dt> Exam Duration: ' . $row["exam_duration"] . '</dt><br/>';
                              echo '<dt> Exam Venue: ' . $row["exam_venue"] . '</dt><br/>';
                              echo '<dt> Hall Size: ' . $row["hall_size"] . '</dt><br/>';
                              echo '</dl>';
                          }
                        ?>
                      </div>
                    
                      </div>
                  </div>
              </div>
          </div>






<link type="text/css" rel="stylesheet" href="css/slick.min.css">
<script type="text/javascript" src="js/slick.min.js" async=""></script>
<script type="text/javascript">
  $(document).ready(function () {

      /* Logo Slider */
    $('.aso-slide').slick({
      infinite: true,
      speed: 750,
      autoplay: true,
      autoplaySpeed: 4000,
      slidesToShow: 6,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      responsive: [
        {
          breakpoint: 1680,
          settings: {
            slidesToShow: 5
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 4
          }
        },
        {
          breakpoint: 850,
          settings: {
            slidesToShow: 3
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2
          }
        },
        {
          breakpoint: 440,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });


  });
</script>

</body>
</html>
<?php
}
else {
      echo 'No records found for the given id.';
  }

  // Close the statement
  $stmt->close();
} else {
  echo 'Error preparing statement: ' . $conn->error;
}
$conn->close();
} else {
  echo 'Error processing this request!!!.';
}
?>