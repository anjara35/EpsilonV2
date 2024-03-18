<?php
include('header.php');
include('env.php');

function getIdUser() {
    require('env.php');
    global $mail;
    $select = $db->query('SELECT id FROM user WHERE mail="' . $mail . '"');
    $result = $select->fetch();
    $counttable = count((is_countable($result) ? $result : []));
    if ($counttable != 0) {
      return $result['id'];
    } else {
      return 'erreur req';
    }
  }

$isConnected = (isset($_COOKIE['mail']) || isset($_SESSION['mail'])) ? true : false;

if($isConnected == TRUE){

    $idUser = getIdUser();
    $nameOfDirForWork = $_GET['course'] . ' ' . $_GET['challenge'];
    $target_dir = $idUser . '/' . $nameOfDirForWork;

    $fileList = scandir($target_dir);

    echo '<h2>Liste des fichiers téléchargés :</h2>';
    echo '<ul>';
    foreach ($fileList as $file) {
        if ($file != '.' && $file != '..') {
            echo '<li><a href="' . $target_dir . '/' . $file . '">' . $file . '</a></li>';
        }
    }
    echo '</ul>';



}else{
    header("Location:index.php");
}










?>