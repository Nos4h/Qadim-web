<?php
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

function initializeGoogleDriveClient() {
    $client = new Client();
    $client->setAuthConfig('credentials.json'); // ملف الـ JSON الذي قمت بتحميله
    $client->addScope(Drive::DRIVE_FILE);
    $client->setRedirectUri('http://localhost/your-redirect-uri'); // استبدلها بعنوان إعادة التوجيه الخاص بك
    $client->setAccessType('offline');

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        file_put_contents('token.json', json_encode($token));
        header('Location: ' . filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL));
        exit;
    } elseif (file_exists('token.json')) {
        $accessToken = json_decode(file_get_contents('token.json'), true);
        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken();
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            file_put_contents('token.json', json_encode($client->getAccessToken()));
        }
    } else {
        $authUrl = $client->createAuthUrl();
        echo "<a href='$authUrl'>Connect to Google Drive</a>";
        exit;
    }

    return $client;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = initializeGoogleDriveClient();
    $driveService = new Drive($client);

    $fileMetadata = new Drive\DriveFile(array(
        'name' => basename($_FILES['resume']['name'])
    ));
    $content = file_get_contents($_FILES['resume']['tmp_name']);
    $file = $driveService->files->create($fileMetadata, array(
        'data' => $content,
        'mimeType' => 'application/pdf',
        'uploadType' => 'multipart',
        'fields' => 'id'
    ));
    $fileId = $file->id;
    $fileUrl = "https://drive.google.com/file/d/$fileId/view";

    echo "File uploaded successfully. <a href='$fileUrl'>View File</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload to Google Drive</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="resume" accept=".pdf" required>
        <input type="submit" value="Upload to Google Drive">
    </form>
</body>
</html>
