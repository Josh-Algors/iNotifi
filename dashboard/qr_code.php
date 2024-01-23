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

if (isset($_GET['img']) && !empty($_GET['img'])) {

  $qrCodePath = "../output_image_" . $_GET['img'] . ".png";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Display</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <img src=<?php echo $qrCodePath; ?> alt='QR Code'>
</body>
</html>
<?php
      }
      else{
        echo "No image found!!";
        exit;
      }
?>