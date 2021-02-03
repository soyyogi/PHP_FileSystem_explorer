<?php
session_start();
$action = $_GET['action'];
if (isset($action)) {
    switch ($action) {
        case 'open':
            $name = $_GET['name'];
            if(isset($name)) {
                $path = $_SESSION['currentPath'] . '/' . $name;
                if (file_exists($path) && is_dir($path)) {
                    $_SESSION['currentPath'] = $path;
                    echo $_SESSION['currentPath'];
                }
            }
            header('Location: http://localhost/PHP_FileSystem_explorer');
            break;
        default:
            echo 'unsupported action';
    }
}
