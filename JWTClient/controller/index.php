<?php
require_once '../env.php';
require_once '../model/database.php';
require_once '../model/users.php';

session_start();

$soapClient = new SoapClient("../../JWTServer/cars.wsdl", array('cache_wsdl' => WSDL_CACHE_NONE));

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
  case 'login':
    /* LOGIN */
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
    /*  REGISTER */
    // If logged in display home
    if (isset($_SESSION['user'])) {
      header("Location: index.php");
      break;
    }

    if (isset($_POST['login']) && isset($_POST['password'])) {
      // Try to register on Server and get JWT API key
      $apiKey = $soapClient->registerUser();
      if (!empty($apiKey)) {
        // Check if register worked
        try {
          $registered = register($_POST['login'], $_POST['password'], $apiKey);
        } catch (Exception $err) {
          $_SESSION['error'] = 'This login is already used';
        }
  
        // Successfully registered
        if (isset($registered) && !is_null($registered)) {
          $_SESSION['user'] = $registered;
          require_once '../view/home.php';
          break;
        }
      } else {
        $response = json_decode($apiKey);
        $_SESSION['error'] = isset($response['error']) ? $response['error']['message'] : 'Registration failed on server';
      }
      
      // Other errors
      $_SESSION['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : 'An error occurred, try again later';
    }

    require_once '../view/register.php';
    break;

  case 'disconnect':
    /* DISCONNECT */
    session_destroy();
    require_once '../view/login.php';
    break;
    
  default:
    require_once '../view/home.php';
}