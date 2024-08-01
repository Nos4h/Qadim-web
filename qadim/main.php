<!DOCTYPE html>
<html lang="en">
<head>
    <link href="xpdfReader">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'jobapplications';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    ?>

    <nav class="navbar">
        <a href="about.html" class="logo">
            <img src="image1.png" alt="Logo" class="logo-image" width="20%">
        </a>
        <ul class="nav-links"> 
        <li><a href="MainPage.html">الرئيسية</a></li>
            <li><a href="about.html">حول المنصة</a></li>
            <li class="ctn"><a href="ContactUs.html#contact-form">تواصل معنا</a></li>
        </ul>
    </nav>
    <section id="contact-form">
    <div class="search-container">
        <h2>بحث</h2>
        <form method="post">
            <input type="text" name="search_key" placeholder="Enter keyword" required>
            <button type="submit">بحث</button>
           
        </form>
    </div>
    <div class="complaints-button-container">
            <a href="contactAdmin.php"><button>شكاوي</button></a>
        </div>
</section>




    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search_key = $conn->real_escape_string($_POST['search_key']);

        $sql = "SELECT full_name, email, resume FROM applications";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
           
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>Name</th><th>Email</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $resumeContent = $row['resume'];
                $resumeText = pdfToText($resumeContent);

                if (stripos($resumeText, $search_key) !== false) {
                    echo "<tr>";
                    echo "<td>" . $row['full_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
        } else {
            echo "No results found.";
        }
    }

    $conn->close();

    function pdfToText($pdfContent) {
        // Save the PDF content to a temporary file
        $tempPdfFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempPdfFile, $pdfContent);

        // Use a library like `pdftotext` to extract text from the PDF
        $outputTextFile = $tempPdfFile . '.txt';
        $command = "pdftotext $tempPdfFile $outputTextFile";
        shell_exec($command);

        // Read the text content
        $textContent = file_get_contents($outputTextFile);

        // Clean up temporary files
        unlink($tempPdfFile);
        unlink($outputTextFile);

        return $textContent;
    }
    ?>
   
</body>
<footer>
    <p>&copy; 2024   جميع الحقوق محفوظة. تصميم بواسطة عائشة الشنبري و روان جميل و اريام السعد و شهد الزهراني  </p>
</footer>
</html>
