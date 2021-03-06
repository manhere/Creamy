<?php

session_start();
require_once("config.php");
require_once("user.php");
require_once("backend.php");

/**
 * Creamy administration backend.
 */

$user = new User();
$user->check_logout();
$user->check_login();

$backend = new Backend();

if ($user->logged_in()) {
  if (isset($_GET["refresh"])) {
    $backend->remove_contents_file();
  }
  if (isset($_GET["delete"])) {
    if (isset($_GET["file"])) {
      $root = $_SERVER["DOCUMENT_ROOT"];
      File::remove($root . "/" . $_GET["file"]);
    }
  }
  $backend->show_backend();
} else {
  $backend->show_login();
}

?>
