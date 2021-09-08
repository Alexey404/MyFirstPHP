<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form enctype="multipart/form-data" action="" method="POST">
        <input name="file" type="file" />
        <input type="submit" value="Upload" />
    </form>
    <form action="" method="POST" enctype="multipart/form-data">
        Name File: <input name="delete" type="text" />
        <input type="submit" value="Delete" />
    </form>
</body>

</html>

<?php
$login = "admin";
$password = "pass";

if (
    !isset($_SERVER['PHP_AUTH_USER']) ||
    !isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_PW'] !== $password ||
    strtolower($_SERVER['PHP_AUTH_USER']) !== $login
) {
    header('WWW-Authenticate: Basic realm="Backend"');
    header('HTTP/1.0 401 Unauthorized');
    die();
} else {


    if (isset($_FILES['file'])) {
        $dir = './assets/';
        $uploadFloder = 'assets/';
        $fileName = $_FILES['file']['name'];
        $fileTMP = $_FILES['file']['tmp_name'];

        move_uploaded_file($fileTMP, $uploadFloder . $fileName);

        if ($_FILES['file']['type'] == 'application/x-zip-compressed') {
            $zip = new ZipArchive;
            $res = $zip->open("./assets/" . $_FILES['file']['name']);
            if ($res === TRUE) {
                $zip->extractTo('./assets/');
                $zip->close();
                unlink("./assets/" . $_FILES['file']['name']);
                echo 'ok';
            } else {
                echo 'failed';
            }
        }
    }
    if (isset($_POST['delete'])) {
        if (unlink("./assets/" . $_POST['delete'])) {
            echo 'Removed';
        }
    }
}
$dir = opendir("./assets/");
$files = array();
while (($file = readdir($dir)) !== false) {
    if ($file != "." and $file != ".." and $file != "A.php") {
        array_push($files, $file);
    }
}
closedir($dir);
sort($files);
foreach ($files as $file)
    print "<div class='fileicon'>
            <p class='filetext'>$file</p>
            </div>";
