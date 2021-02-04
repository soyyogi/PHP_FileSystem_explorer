<?php
session_start();

if($_POST['type']) {
    $type = $_POST['type'];
    switch ($type) {
        case 'dir':
            mkdir($_SESSION['currentPath'] . '/' . $_POST['title']);
            header('Location: http://localhost/PHP_FileSystem_explorer');
            break;
        case 'txt':
            
            break;
        case 'docx':

            break;
        default:
            echo 'unsupported type';
    }
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
