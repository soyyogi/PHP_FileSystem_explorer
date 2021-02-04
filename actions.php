<?php
session_start();

if ($_POST['type']) {
    $type = $_POST['type'];
    $path = $_SESSION['currentPath'] . '/' . $_POST['title'];
    switch ($type) {
        case 'dir':
            if (!(file_exists($path) && is_dir($path))) {
                mkdir($path);
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
                $_SESSION['message'] = 'Successfully created new MS word file!';
            } else {
                $_SESSION['message'] = 'File already exists!';
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
                    function delRecurse($path) {
                        if(is_dir($path)){
                            $list = glob($path . '/*');
                            foreach($list as $item) {
                                is_dir($item) ? delRecurse($item) : unlink($item);
                            }
                            rmdir($path);
                        } elseif(is_file($path)){
                            unlink($path);
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

header('Location: http://localhost/PHP_FileSystem_explorer');
