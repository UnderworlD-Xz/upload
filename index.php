<php? 
$message = "";

if (isset($_POST['submit'])) {
    $target_file = basename($_FILES["fileToUpload"]["name"]); // Menyimpan file di direktori yang sama
    
    if (file_exists($target_file)) {
        $message = "File already exists.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $message = "File uploaded successfully.";
        } else {
            $message = "Error uploading file.";
        }
    }
}
if (isset($_GET['delete'])) {
    $file_to_delete = basename($_GET['delete']); 
    
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete); 
        $message = "File has been deleted.";
    } else {
        $message = "File not found.";
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Upload File</h2>

    <form class="upload-form" action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>


    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, 'Error') !== false ? 'error' : '' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>No</th>
            <th>File Name</th>
            <th>Actions</th>
        </tr>
        <?php
       $files = array_diff(scandir(__DIR__), array('.', '..', 'index.php'));
        $i = 1;
        foreach ($files as $file):
            if (!is_dir($file)): 
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($file); ?></td>
            <td class="actions">
                <a href="<?php echo urlencode($file); ?>" class="download-btn" download>Download</a>
                <a href="?delete=<?php echo urlencode($file); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
            </td>
        </tr>
        <?php
            endif;
        endforeach;
        ?>
    </table>

</body>
</html>
