<?php   
session_start();
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
if(!isset($_SESSION["registered"])){  
    header("location:../login/index.htm");  
} else {  

$query = "SELECT * FROM student"; //You don't need a ; like you do in SQL
$result = mysqli_query($conn,$query);

$email = "EMAIL";
$fname = "FIRST NAME";
$lname = "LAST NAME";
$matnum = "MATRIC NUMBER";
$dept = "DEPARTMENT";
$level = "LEVEL";
$phone = "PHONE NUMBER";


echo "<table>"; // start a table tag in the HTML
echo "<tr><th>".$email. "&#9;</th><th>".$fname. "&#9;</th><th>".$lname. "&#9;</th><th>".$matnum. "&#9;</th><th>".$dept. "&#9;</th><th>".$level. "&#9;</th><th>".$phone. "&#9;</th>";
while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results
echo "<tr><td>" . $row['email'] . "&#9;</td><td>".$row['firstname'] . "&#9;</td><td>" . $row['lastname'] ."&#9;</td><td>" . $row['matric_no'] . "&#9;</td><td>" . $row['department'] . "&#9;</td><td>" . $row['level'] . "&#9;</td><td>" . $row['phone'] . "&#9;</td></tr>";  //$row['index'] the index here is a field name
}

echo "</table>"; 
echo"<a href='../dashboard/admin.php'>Back to your profile.</a>";

}
mysqli_close($conn);
?>
	
    	