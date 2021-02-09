<?php
session_start();

$base = $_SESSION['basePath'];
$_SESSION['rename'] = false;

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
                $_SESSION['size'] = array_merge($_SESSION['size'], [$_POST['title']=>$fileSize]);
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

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['message'] .=  " Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $createdAt = date_create("now")->format("d-m-y H:i:s");
                    $fileSize = $_FILES["fileToUpload"]["size"];
                    $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'], [($_FILES["fileToUpload"]["name"])=>$createdAt]);
                    $_SESSION['size'] = array_merge($_SESSION['size'], [($_FILES["fileToUpload"]["name"])=>$fileSize]);
                    $_SESSION['message'] =  "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                } else {
                    $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                }
            }
            break;

        case "rename":
            $newPath = $_SESSION['currentPath'] . '/' . $_POST['newtitle'];
            copy($path, $newPath);

            // Adding new file time stamp and size
            $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'], [$_POST['newtitle'] => $_SESSION["createdAt"][$_POST['title']]]);
            $_SESSION['size'] = array_merge($_SESSION['size'], [$_POST['newtitle'] => $_SESSION["size"][$_POST['title']]]);

            $_SESSION['rename'] = true;
            $_SESSION['renameRedirect'] = $base . '/actions.php?action=delete&name=' . $_POST['title'];
            break;

        case "move":
            if(!empty($_POST['chooseFolder'])) {
                $createdAt = date_create("now")->format("d-m-y H:i:s");
                $fileSize = filesize($path);
                if (($_POST['chooseFolder'] != ".." && $_SESSION['currentPath'] == 'root' ) || ($_POST['chooseFolder'] == ".." && $_SESSION['currentPath'] != 'root')) {
                    $destination = $_SESSION['currentPath'] . "/" . $_POST['chooseFolder'] . "/" . $_POST['title'];
                    $targetElement = $_SESSION['currentPath'] . "/" . $_POST['title'];
                    copy($targetElement, $destination);
                    // Adding new file time stamp and size
                    $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'],[$_POST['title']=>$createdAt]);
                    $_SESSION['size'] = array_merge($_SESSION['size'],[$_POST['title']=>$fileSize]);
                }
            }
            $_SESSION['rename'] = true;
            $_SESSION['renameRedirect'] = $base . '/actions.php?action=delete&name=' . $_POST['title'];

            break;

        case "copy":
            if(!empty($_POST['chooseFolder'])) {
                $createdAt = date_create("now")->format("d-m-y H:i:s");
                $fileSize = filesize($path);
                $destination = $_SESSION['currentPath'] ."/" . $_POST['chooseFolder'] . "/" . $_POST['title'];
                if ($_POST['chooseFolder'] == "..") {
                    $destination = $_SESSION['currentPath'] . "/" . $_POST['title'];
                }
                $targetElement = $_SESSION['currentPath'] . "/" . $_POST['title'];
                if ($targetElement == $destination) {
                    $destination = $_SESSION['currentPath'] . "/copy " . $_POST['title'];
                    if (($pos = strpos($destination, "/")) !== FALSE) {
                        $copiedElement = substr($destination, $pos+1);
                         // Adding new file time stamp and size
                        $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'],[$copiedElement=>$createdAt]);
                        $_SESSION['size'] = array_merge($_SESSION['size'],[$copiedElement=>$fileSize]);
                    }
                }
                copy($targetElement, $destination);
                // Adding new file time stamp and size
                $_SESSION['createdAt'] = array_merge($_SESSION['createdAt'],[$_POST['title']=>$createdAt]);
                $_SESSION['size'] = array_merge($_SESSION['size'],[$_POST['title']=>$fileSize]);

                $_SESSION['message'] = 'Successufully copied!';
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

        default:
            $_SESSION['message'] = 'Unsupported action!';
    }
}

if($_SESSION['rename'] === true) {
    $url = $_SESSION['renameRedirect'];
    header("Location: $url");
} else {
    header("Location: $base");
}

$_SESSION['rename'] = false;