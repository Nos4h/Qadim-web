<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
    
    <?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'comp';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch all records from the com table
    $sql = "SELECT full_name, email, massage FROM com";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Start the table and add headers
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>Name</th><th>Email</th><th>Massage</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['massage']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            // No data found
            echo "No data found in the table.";
        }
    } else {
        // Query execution error
        echo "Error executing the query: " . $conn->error;
    }

    $conn->close();
    ?>
</body>
<footer>
    <p>&copy; 2024   جميع الحقوق محفوظة. تصميم بواسطة عائشة الشنبري و روان جميل و اريام السعد و شهد الزهراني  </p>
</footer>
</html>
