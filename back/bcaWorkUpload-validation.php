<?php

include('header.php');

$isConnected = (isset($_COOKIE['mail']) || isset($_SESSION['mail'])) ? true : false;
if (!$isConnected) {
  echo 'Vous n\'êtes pas connecté, veuillez vous inscrire ou vous connecter sur la page d\'accueil<br><a href="index.php">Retour</a>';
  exit();
}

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

$idUser = getIdUser();

if (!is_dir($idUser)) {
  mkdir($idUser, 0777);
}

$nameOfDirForWork = $_GET['course'] . ' ' . $_GET['challenge'];
$target_dir = $idUser . '/' . $nameOfDirForWork;

if (!is_dir($target_dir)) {
  mkdir($target_dir, 0777);
}

if (isset($_POST["submit"])) {
  $totalFiles = count($_FILES['filesToUpload']['name']);
  for ($i = 0; $i < $totalFiles; $i++) {
    $target_file = $target_dir . '/' . basename($_FILES["filesToUpload"]["name"][$i]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
      echo "Désolé, le fichier " . htmlspecialchars(basename($_FILES["filesToUpload"]["name"][$i])) . " existe déjà.";
      $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["filesToUpload"]["size"][$i] > 500000) {
      echo "Désolé, votre fichier " . htmlspecialchars(basename($_FILES["filesToUpload"]["name"][$i])) . " est trop gros";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, array("jpg", "png", "jpeg", "gif", "pdf", "ppt", "pptx"))) {
      echo "Désolé, seul les JPG, JPEG, PNG, GIF, PDF, PPT & PPTX sont autorisés pour " . htmlspecialchars(basename($_FILES["filesToUpload"]["name"][$i])) . ".";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo " Votre fichier " . htmlspecialchars(basename($_FILES["filesToUpload"]["name"][$i])) . " n'a pas été uploadé.";
      // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["filesToUpload"]["tmp_name"][$i], $target_file)) {
        echo "Le fichier " . htmlspecialchars(basename($_FILES["filesToUpload"]["name"][$i])) . " a été uploadé.";
      } else {
        echo "Désolé, il y a eu une erreur durant l'upload de " . htmlspecialchars(basename($_FILES["filesToUpload"]["name"][$i])) . ".";
      }
    }
  }
}

?>

<br>
<a href="index.php">Retour</a>
