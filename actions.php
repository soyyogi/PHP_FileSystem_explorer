<?php
session_start();
$base = $_SESSION['basePath'];

if (isset($_POST['type'])) {
    $type = $_POST['type'];
    $path = $_SESSION['currentPath'] . '/' . $_POST['title'];
    switch ($type) {
        case 'dir':
            if (!(file_exists($path) && is_dir($path))) {
                mkdir($path);
                $createdAt = date_create("now")->format("d-m-y H:i:s");
                $fileSize = filesize($path);
                $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'], [$_POST['title']=>$createdAt]);
                $_SESSION['size'] = array_merge($_SESSION['size'], [$_POST['title']=>$filize]);
                $_SESSION['message'] = 'Successfully created new directory!';
            } else {
                $_SESSION['message'] = 'Directory already exists!';
            }
            break;
        case 'txt':
            if (!(file_exists($path . '.txt') && is_file($path . '.txt'))) {
                $file = fopen($path . '.txt', 'a+');
                fwrite($file, $_POST['body']);
                fclose($file);
                $createdAt = date_create("now")->format("d-m-y H:i:s");
                $fileSize = filesize($path . '.txt');
                $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'], [($_POST['title'].'.txt')=>$createdAt]);
                $_SESSION['size'] = array_merge($_SESSION['size'], [($_POST['title'].'.txt')=>$fileSize]);
                $_SESSION['message'] = 'Successfully created new text file!';
            } else {
                $_SESSION['message'] = 'File already exists!';
            }
            break;
        case 'docx':
            if (!(file_exists($path . '.docx') && is_file($path . '.docx'))) {
                $file = fopen($path . '.docx', 'a+');
                fwrite($file, $_POST['body']);
                fclose($file);
                $createdAt = date_create("now")->format("d-m-y H:i:s");
                $fileSize = filesize($path . '.docx');
                $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'], [($_POST['title'].'.docx')=>$createdAt]);
                $_SESSION['size'] = array_merge($_SESSION['size'], [($_POST['title'].'.docx')=>$fileSize]);
                $_SESSION['message'] = 'Successfully created new MS word file!';
            } else {
                $_SESSION['message'] = 'File already exists!';
            }
            break;
        case 'upload':
            $target_file = $_SESSION['currentPath'] . '/' . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // // Check if image file is a actual image or fake image
            // if (isset($_POST["submit"])) {
            //     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            //     if ($check !== false) {
            //         echo "File is an image - " . $check["mime"] . ".";
            //         $uploadOk = 1;
            //     } else {
            //         echo "File is not an image.";
            //         $uploadOk = 0;
            //     }
            // }

            // Check if file already exists
            if (file_exists($target_file)) {
                $_SESSION['message'] = "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                $_SESSION['message'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            // if (
            //     $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            //     && $imageFileType != "gif"
            // ) {
            //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            //     $uploadOk = 0;
            // }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['message'] .=  " Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $createdAt = date_create("now")->format("d-m-y H:i:s");
                    $fileSize = $_FILES["fileToUpload"]["size"];
                    // print_r($fileSize);
                    $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'], [($_FILES["fileToUpload"]["name"])=>$createdAt]);
                    $_SESSION['size'] = array_merge($_SESSION['size'], [($_FILES["fileToUpload"]["name"])=>$fileSize]);
                    $_SESSION['message'] =  "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                } else {
                    $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                }
            }
            break;
        default:
            $_SESSION['message'] = 'Unsupported file type!';
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'open':
            $name = $_GET['name'];
            if (isset($name)) {
                $path = $_SESSION['currentPath'] . '/' . $name;
                if (file_exists($path) && is_dir($path)) {
                    $_SESSION['currentPath'] = $path;
                }
                if (file_exists($path) && is_file($path)) {
                    if (substr($name, -4) == '.txt') {
                        // $path = $base . '/' . $path;
                        // header("Location: $path");
                        // die();
                        $file = fopen($path, 'r');
                        $size = filesize($path);
                        $content = fread($file, $size);
                        $_SESSION['fileContent'] = $content;
                        fclose($file);
                    } else {
                        $path = $base . '/' . $path;
                        header("Location: $path");
                        die();
                    }
                }
            }
            break;
        case 'previousDir':
            $endPos = strrpos($_SESSION['currentPath'], '/');
            $path = substr($_SESSION['currentPath'], 0, $endPos);
            if (file_exists($path) && is_dir($path)) {
                $_SESSION['currentPath'] = $path;
            }
            break;
        case 'delete':
            $name = $_GET['name'];
            if (isset($name)) {
                $path = $_SESSION['currentPath'] . '/' . $name;
                if (file_exists($path)) {
                    function delFile($item) {
                        $name = substr($item, strrpos($item, "/") +1);
                        $_SESSION["createdAt"] = array_diff_assoc($_SESSION["createdAt"], [$name => $_SESSION["createdAt"][$name]]);
                        $_SESSION["size"] = array_diff_assoc($_SESSION["size"], [$name => $_SESSION["size"][$name]]);
                        unlink($item);
                    }
                    function delDir($item) {
                        $name = substr($item, strrpos($item, "/") +1);
                        $_SESSION["createdAt"] = array_diff_assoc($_SESSION["createdAt"], [$name => $_SESSION["createdAt"][$name]]);
                        $_SESSION["size"] = array_diff_assoc($_SESSION["size"], [$name => $_SESSION["size"][$name]]);
                        rmdir($item);
                    }
                    function delRecurse($path)
                    {
                        if (is_dir($path)) {
                            $list = glob($path . '/*');
                            foreach ($list as $item) {
                                is_dir($item) ? delRecurse($item) : delFile($item);
                            }
                            delDir($path);
                        } elseif (is_file($path)) {
                            delFile($path);
                        }
                    }
                    delRecurse($path);
                    $_SESSION['message'] = 'Successfully deleted!';
                }
            }
            break;

        case "edit":
            // todo replace the date Session[modifiedAt][$name] = new date;
            break;

        case "remove":
            break;

        case "move":
            break;

        case "copy":
            break;

        default:
            $_SESSION['message'] = 'Unsupported action!';
    }
}

header("Location: $base");
