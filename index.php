<?php
  session_start();
  $root = 'root';
  if(!(file_exists($root) && is_dir($root))) {
    mkdir($root);
  }
  if (!isset($_SESSION['currentPath'])) {
    $_SESSION['currentPath'] = $root;
  }
  $currentTree = array_slice(scandir($_SESSION['currentPath']), 2);
  // print_r($currentTree);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js" integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./index.css">
  <title>File Sistem</title>
</head>
<body>
  <header>
    <div class="header">
      <a href="/" class="logo">Logo</a>
      <input type="search" class="input-search" placeholder="search">
      <ul class="header-list">
        <li><a href="#" class="header-link">Upload</a></li>
        <li><a href="#" class="header-link">New Item</a></li>
      </ul>
    </div>
  </header>
  <main>
    <h2>This will be the main body part</h2>
    <ul>
<?php
  foreach( $currentTree as $i => $name) {
    echo '<li><a href="http://localhost/PHP_FileSystem_explorer/actions.php?name='.$name.'&action=open">'. $name . '</a></li>';
  }

?>
    </ul>
  </main>
</body>
</html>