<?php
require_once '../model/database.php';
require_once '../model/users.php';

session_start();

switch ($_GET['action']) {
  case 'login':
    // If logged in display home
    if (isset($_SESSION['user'])) {
      header("Location: index.php");
      break;
    }

    if (isset($_POST['login']) && isset($_POST['password'])) {
      // Check if credentials correspond
      $validLogin = checkLogin($_POST['login'], $_POST['password']);

      // Successfully logged in
      if ($validLogin != null) {
        $_SESSION['user'] = $validLogin;
        require_once '../view/home.php';
        break;
      }
      
      $_SESSION['error'] = 'Invalid credentials';
    }

    require_once '../view/login.php';
    break;
  
  case 'register':
    // If logged in display home
    if (isset($_SESSION['user'])) {
      header("Location: index.php");
      break;
    }

    if (isset($_POST['login']) && isset($_POST['password'])) {
      // Check if register worked
      try {
        $registered = register($_POST['login'], $_POST['password']);
      } catch (Exception $err) {
        $_SESSION['error'] = 'This login is already used';
      }

      // Successfully registered
      if ($registered != null)  {
        $_SESSION['user'] = $registered;
        require_once '../view/home.php';
        break;
      }
      
      // Other errors
      $_SESSION['error'] = 'An error occurred, try again';
    }

    require_once '../view/register.php';
    break;

  case 'disconnect':
    session_destroy();
    require_once '../view/login.php';
    break;
    
  default:
    require_once '../view/home.php';
}