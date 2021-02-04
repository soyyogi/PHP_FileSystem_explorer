<?php
session_start();
$user = 'yogi';
$_SESSION['basePath'] = $user === 'yogi' ? 'http://localhost/PHP_FileSystem_explorer' : 'http://192.168.64.2/PHP_FileSystem_explorer';
// unset($_SESSION['currentPath']);
$root = 'root';
if (!(file_exists($root) && is_dir($root))) {
  mkdir($root);
}
if (!isset($_SESSION['currentPath'])) {
  $_SESSION['currentPath'] = $root;
}
$currentTree = array_slice(scandir($_SESSION['currentPath']), 2);
// print_r($currentTree);

function checktype($name)
{
  $path = $_SESSION['currentPath'] . '/' . $name;
  if (file_exists($path) && is_dir($path)) {
    return 'dir';
  }
  if (file_exists($path) && is_file($path)) {
    if (substr($name, -4) == '.txt') {
      return 'txt';
    } elseif (substr($name, -5) == '.docx') {
      return 'docx';
    } elseif (substr($name, -5) == '.jpeg' || substr($name, -4) == '.jpg' || substr($name, -4) == '.png') {
      return 'img';
    } elseif (substr($name, -4) == '.mp3') {
      return 'mp3';
    } elseif (substr($name, -4) == '.pdf') {
      return 'pdf';
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
  'pdf' => '<i class="far fa-file-pdf"></i>'
];


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js" integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>
  <script defer src="./script.js"></script>
  <script src="https://kit.fontawesome.com/1935655a46.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./index.css">
  <title>File Sistem</title>
</head>

<body>
  <header>
    <div class="header">
      <a href="/" class="logo">Logo</a>
      <input type="search" class="input-search" placeholder="search">
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
    <textarea name="body" id="body" cols="30" rows="10" hidden placeholder="some text here..."></textarea>
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

    <h3><a href="<?=$_SESSION['basePath'] . '/actions.php?action=previousDir'?>">&#8617;</a></h3>
    <ul>
      <?php
      foreach ($currentTree as $i => $name) {

        echo '<li class="currentTree-item"><a href="' .$_SESSION['basePath'] . '/actions.php?name=' . $name . '&action=open">' . $icons[checktype($name)] . ' ' . $name . '</a>
        <span class="show-actions">&#10247;
          <ul class="action-options hidden">
            <li class="action-option"><a href="' .$_SESSION['basePath'] . '/actions.php?name=' . $name . '&action=open">Open</a></li>
            <!-- <li class="action-option">Edit</li>
            <li class="action-option">Rename</li> -->
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
