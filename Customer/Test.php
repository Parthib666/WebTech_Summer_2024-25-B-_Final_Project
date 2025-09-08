<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Folder where images will be saved
    $targetDir = "../Images/";

    // Create folder if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir .$fileName; // timestamp to avoid duplicates

        // Allowed file extensions
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                echo "✅ File uploaded successfully: " . $targetFile;
            } else {
                echo "❌ Error uploading file.";
            }
        } else {
            echo "❌ Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
        }
    } else {
        echo "❌ No file selected or upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Upload</title>
</head>
<body>
    <h2>Upload an Image</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Select image:</label>
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
