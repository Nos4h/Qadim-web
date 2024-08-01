
<?php
$servername = "localhost"; // Change as required
$username = "root"; // Change as required
$password = ""; // Change as required
$dbname = "jobapplications"; // Change as required

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $file = $_FILES['resume'];

    // Check if the uploaded file is a PDF
    if ($file['type'] !== 'application/pdf') {
        echo "Only PDF files are allowed.";
        exit;
    }

    // Read the content of the uploaded file
    $fileContent = file_get_contents($file['tmp_name']);
    $fileContent = $conn->real_escape_string($fileContent);

    // Insert the data into the database
    $sql = "INSERT INTO applications (full_name, email, resume) VALUES ('$full_name', '$email', '$fileContent')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>