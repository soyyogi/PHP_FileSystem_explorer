<?php
session_start();
if (!isset($_SESSION['createdAt'])) {
  $_SESSION['createdAt'] = [];
}
if (!isset($_SESSION['size'])) {
  $_SESSION['size'] = [];
}
// unset($_SESSION['createdAt']);
// unset($_SESSION['size']);

$user = 'faber';
$_SESSION['basePath'] = $user === 'yogi' ? 'http://localhost/PHP_FileSystem_explorer' : 'http://192.168.64.2/PHP_FileSystem_explorer';

$root = 'root';
if (!(file_exists($root) && is_dir($root))) {
  mkdir($root);
}
if (!isset($_SESSION['currentPath'])) {
  $_SESSION['currentPath'] = $root;
}
$currentTree = array_slice(scandir($_SESSION['currentPath']), 2);

function checktype($name) {
  $path = $_SESSION['currentPath'] . '/' . $name;
  if (file_exists($path) && is_dir($path)) {
    return 'dir';
  } elseif (file_exists($path) && is_file($path)) {
      if (substr($name, -4) == '.txt') {
        return 'txt';
      } elseif (substr($name, -5) == '.docx') {
        return 'docx';
      } elseif (substr($name, -5) == '.jpeg' || substr($name, -4) == '.jpg' || substr($name, -4) == '.png' || substr($name, -4) == '.svg') {
        return 'img';
      } elseif (substr($name, -4) == '.mp3') {
        return 'mp3';
      } elseif (substr($name, -4) == '.pdf') {
        return 'pdf';
      } elseif (substr($name, -4) == '.mp4') {
        return 'mp4';
      } elseif (substr($name, -4) == '.ppt') {
        return 'ppt';
      } elseif (substr($name, -4) == '.csv') {
        return 'csv';
      } elseif (substr($name, -4) == '.zip' || substr($name, -4) == '.rar' || substr($name, -4) == '.exe') {
        return 'zip';
      }
    }
  return 'unknown';
}

$icons = [
  'dir' => '<i class="far fa-folder"></i>',
  'txt' => '<i class="far fa-file-alt"></i>',
  'docx' => '<i class="far fa-file-word"></i>',
  'img' => '<i class="far fa-image"></i>',
  'mp3' => '<i class="far fa-file-audio"></i>',
  'unknown' => '<i class="far fa-file"></i>',
  'pdf' => '<i class="far fa-file-pdf"></i>',
  'csv' => '<i class="far fa-file-excel"></i>',
  'ppt' => '<i class="far fa-file-powerpoint"></i>',
  'zip' => '<i class="far fa-file-archive"></i>',
  'mp4' => '<i class="far fa-file-video"></i>'
];

function convertSize($bytes) {
  if ($bytes >= 1000000) {
    return $bytes = number_format($bytes / 1000000, 2) . ' MB';
  } elseif ($bytes >= 1000) {
    return $bytes = number_format($bytes / 1000, 2) . ' KB';
  } else {
    return $bytes . ' Bytes';
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    const currentTree = <?php echo json_encode($currentTree);?>;
    const basePath = <?php echo json_encode($_SESSION['basePath']);?>;
    const icons = <?php echo json_encode($icons);?>;
  </script>
  <script defer src="./script.js"></script>
  <script src="https://kit.fontawesome.com/1935655a46.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./index.css">
  <title>File Sistem</title>
</head>

<body>
  <header>
    <div class="header">
      <a href="<?=$_SESSION['basePath']?>" class="logo">
        <i class="fab fa-google-drive"></i>
        Docs
      </a>
      <div class="search-form">
        <i class="fas fa-search"></i>
        <input type="search" class="input-search" placeholder="search">
      </div>
      <ul class="header-list">
        <li class="upload-file header-link">Upload</li>
        <li class="create-new-item header-link">
          New Item
          <ul class="item-options hidden">
            <li class="item-option txt">Text File</li>
            <li class="item-option docx">MS Word File</li>
            <li class="item-option dir">Folder</li>
          </ul>
        </li>
      </ul>
    </div>
  </header>

  <form class="create-item-form hidden" action="<?=$_SESSION['basePath'] . '/actions.php'?>" method="post">
    <input type="text" name="type" id="type" hidden>
    <input type="file" name="fileToUpload" id="fileToUpload" hidden>
    <input type="text" name="title" id="title" placeholder="title" maxlength="20">
    <input type="text" name="newtitle" id="newtitle" placeholder="new title" maxlength="20" hidden>
    <textarea name="body" id="body" cols="30" rows="10" hidden placeholder="some text here..."></textarea>
    <div class="move-doc hidden">
      <label for="chooseFolder" id="chooseFolder-label">Choose a folder</label>
      <select name="chooseFolder" id="chooseFolder">
        <?php
          $showDocs = array_slice(scandir($_SESSION['currentPath']), 1);
          foreach ($showDocs as $i => $name) {
            $path = $_SESSION['currentPath'] . '/' . $name;
            if (is_dir($path)) {
              echo '<option value="'.$name.'">'.$name.'</option>';
            }
          }
        ?>
      </select>
    </div>
    <button type="submit">Submit</button>
  </form>

  <main>
    <?php
    if (isset($_SESSION['message'])) {
      echo '<p class="message">' . nl2br($_SESSION['message']) . '</p>';
    }
    if (isset($_SESSION['fileContent'])) {
      echo '<section class="file-content"><p>' . $_SESSION['fileContent'] . '</p></section>';
    }

    ?>

    <h3>
      <a href="<?=$_SESSION['basePath'] . '/actions.php?action=previousDir'?>" class="back-arrow">&#8617;</a>
    </h3>
    <ul id="displayList">
      <?php
      foreach ($currentTree as $i => $name) {

        echo '<li class="currentTree-item">
          <span>'. $icons[checktype($name)] . '</span>
          <a href="' .$_SESSION['basePath'] . '/actions.php?name=' . $name . '&action=open">' . ' ' . $name . '</a>
          <span class="info">' . $_SESSION['createdAt'][$name] . '</span>
          <span class="info">' . convertSize($_SESSION['size'][$name]) . '</span>
          <span class="info">' . (isset($_SESSION['lastEdit']) ? $_SESSION['lastEdit'] : "") . '</span>
          <span class="show-actions">&#10247;
            <ul class="action-options hidden">
              <li class="action-option"><a href="' .$_SESSION['basePath'] . '/actions.php?name=' . $name . '&action=open">Open</a></li>
              <li class="action-option" data-action-rename data-name="'.$name.'">Rename</li>
              <li class="action-option" data-action-copy data-name="' .$name.'" >Copy</li>
              <li class="action-option" data-action-move data-name="' .$name.'">Move</li>
              <li class="action-option"><a href="' .$_SESSION['basePath'] . '/actions.php?name=' . $name . '&action=delete">Delete</a></li>
            </ul>
          </span>
        </li>';
      }
      ?>
    </ul>
  </main>
</body>

</html>

<?php

unset($_SESSION['message']);
unset($_SESSION['fileContent']);