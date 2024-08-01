
    <?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'comp'; // استبدل هذا باسم قاعدة بياناتك

$conn = new mysqli($servername, $username, $password, $dbname);

// تحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من وجود القيم قبل استخدامها
    $full_name = isset($_POST['full_name']) ? $conn->real_escape_string($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $massage = isset($_POST['massage']) ? $conn->real_escape_string($_POST['massage']) : '';

    // تحقق من أن الحقول ليست فارغة
    if (!empty($full_name) && !empty($email) && !empty($massage)) {
        $sql = "INSERT INTO com (full_name, email, massage) VALUES ('$full_name', '$email', '$massage')";

        if ($conn->query($sql) === TRUE) {
            echo "تم إرسال الرسالة بنجاح";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "يرجى تعبئة جميع الحقول.";
    }
}

$conn->close();
?>

