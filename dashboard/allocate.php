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
// Function to sanitize and validate input
// function sanitizeInput($data) {
//     return htmlspecialchars(strip_tags($data));
// }

// Check if the reference value is present in the URL
if (isset($_GET['reference']) && !empty($_GET['reference'])) {
    // Sanitize the reference value
    $reference = $_GET['reference'];

    // Prepare SQL statement
    $sql = "SELECT e.id, s.firstname, s.lastname, s.matric_no, s.department, s.level, e.course_name, e.exam_date, e.seat, e.exam_duration, e.exam_venue, e.hall_size
            FROM exam e
            JOIN student s ON e.student_id = s.id
            WHERE e.reference = ?";
        
    $stmt = mysqli_stmt_init($conn);

    if ($stmt->prepare($sql)) {

    $stmt->bind_param("s", $reference);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if there are rows returned
    if ($result->num_rows > 0) {
        echo '<h2> ALLOCATION TABLE </h2>';
        echo '<table border="1">';
        echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Matric No</th><th>Department</th><th>Level</th><th>Course Name</th><th>Exam Date</th><th>Seat</th><th>Exam Duration</th><th>Exam Venue</th><th>Hall Size</th></tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["id"] . '</td>';
            echo '<td>' . $row["firstname"] . '</td>';
            echo '<td>' . $row["lastname"] . '</td>';
            echo '<td>' . $row["matric_no"] . '</td>';
            echo '<td>' . $row["department"] . '</td>';
            echo '<td>' . $row["level"] . '</td>';
            echo '<td>' . $row["course_name"] . '</td>';
            echo '<td>' . $row["exam_date"] . '</td>';
            echo '<td>' . $row["seat"] . '</td>';
            echo '<td>' . $row["exam_duration"] . '</td>';
            echo '<td>' . $row["exam_venue"] . '</td>';
            echo '<td>' . $row["hall_size"] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No records found for the given reference.';
    }

    // Close the statement
    $stmt->close();
} else {
    echo 'Error preparing statement: ' . $conn->error;
}
$conn->close();
} else {
    echo 'Reference value not provided in the URL.';
}
?>
