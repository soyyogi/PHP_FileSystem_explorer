<?php
session_start();

if($_POST['type']) {
    $type = $_POST['type'];
    $path = $_SESSION['currentPath'] . '/' . $_POST['title'];
    switch ($type) {
        case 'dir':
            if(!(file_exists($path) && is_dir($path))) {
                mkdir($path);
            }
            break;
        case 'txt':
            if(!(file_exists($path . '.txt') && is_file($path . '.txt'))) {
                $file = fopen($path . '.txt', 'a+');
                fwrite($file, $_POST['body']);
                fclose($file);
            }
            break;
        case 'docx':
            if(!(file_exists($path . '.docx') && is_file($path . '.docx'))) {
                $file = fopen($path . '.docx', 'a+');
                fwrite($file, $_POST['body']);
                fclose($file);
            }
            break;
        default:
            echo 'unsupported type';
    }
    header('Location: http://localhost/PHP_FileSystem_explorer');
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
    switch ($action) {
        case 'open':
            $name = $_GET['name'];
            if(isset($name)) {
                $path = $_SESSION['currentPath'] . '/' . $name;
                if (file_exists($path) && is_dir($path)) {
                    $_SESSION['currentPath'] = $path;
                }
            }
            header('Location: http://localhost/PHP_FileSystem_explorer');
            break;
        case 'previousDir':
            $endPos = strrpos($_SESSION['currentPath'], '/');
            $path = substr($_SESSION['currentPath'], 0, $endPos);
            if (file_exists($path) && is_dir($path)) {
                $_SESSION['currentPath'] = $path;
            }
            header('Location: http://localhost/PHP_FileSystem_explorer');
            break;
        default:
            echo 'unsupported action';
    }
}
