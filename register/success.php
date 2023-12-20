<?php
session_start();

if(!isset($_SESSION['login'])){
    header('Location: ../login/index.htm');
}

if(isset($_SESSION['login'])){



?>
<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="description" content="iNotify.">
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
                        <h3>Registration Successful!.</h3>
                        <br/>
                        <span>Your Info is received. We will get back to you soon!</span> 
                          <a href="../login/index.htm" class="btn" title="Back to Profile">Back to login page</a>
                      </div>

                      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 ">
                          
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
?>