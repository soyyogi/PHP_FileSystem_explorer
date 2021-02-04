<?php
session_start();
if($_POST['type']) {
    print_r($_POST);
}
$action = $_GET['action'];
if (isset($action)) {
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
