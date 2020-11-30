<?php
require_once './JWT.php';
use Firebase\JWT\JWT;

$secret = 'eG?c7dQr"54L//;T';

$dsn = "mysql:host=localhost;dbname=JWTServer";
$username = "root";
$password = "root";

// Create connection
try {
  $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

/**
 * Find a user from a JWT key
 * @return user returns user if found, null if not
 */
function getUserFromJwt($jwtKey) {
  global $db;

  $query = "SELECT * FROM users WHERE key = ?";

  $statement = $db->prepare($query);
  $statement->execute([$jwtKey]);
  $result = $statement->fetch();

  return $statement->rowCount() == 1 ? $result : null;
}

/**
 * Register a user, generate a JWT API key
 * @return string JWT API key
 */
function registerUser() {
  global $db;
  global $secret;

  $jwt = JWT::encode(array(), $secret);

  $query = "INSERT INTO users (jwt_api_key) VALUES (?)";

  $statement = $db->prepare($query);
  $statement->execute([$jwt]);

  return $jwt;
}

/**
 * Get all cars
 * 
 * @return array All cars found 
 */
function getAllCars($jwtKey) {
  global $db;

  // Checking Key validity
  $user = getUserFromJwt($jwtKey);
  if (is_null($user)) {
    return json_encode([
      'error' => 'Invalid API Key'
    ]);
  }

  $query = "SELECT * FROM cars";

  $statement = $db->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();

  return json_encode($result);
}

/**
 * Get all cars from a specific constructor
 * 
 * @param constructor Constructor name
 * @return array All cars found 
 */
function getConstructorCars($jwtKey, $constructor) {
  global $db;

  // Checking Key validity
  $user = getUserFromJwt($jwtKey);
  if (is_null($user)) {
    return json_encode([
      'error' => 'Invalid API Key'
    ]);
  }

  $query = "SELECT * FROM cars WHERE constructor = :constructor";

  $statement = $db->prepare($query);
  $statement->bindValue(':constructor', $constructor);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();

  return json_encode($result);
}

/**
 * Get all cars from a specific constructor with a specific engine
 * 
 * @param constructor Constructor name
 * @param enging Engine name
 * @return array All cars found from this constructor and this engine
 */
function getCarsEngineConstructor($jwtKey, $constructor, $engine) {
  global $db;

  // Checking Key validity
  $user = getUserFromJwt($jwtKey);
  if (is_null($user)) {
    return json_encode([
      'error' => 'Invalid API Key'
    ]);
  }

  $query = "SELECT * FROM cars WHERE constructor = :constructor AND engine = :engine";

  $statement = $db->prepare($query);
  $statement->bindValue(':constructor', $constructor);
  $statement->bindValue(':engine', $engine);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();

  return json_encode($result);
}

ini_set("soap.wsdl_cache_enabled", 0);
$server = new SoapServer("cars.wsdl");
$server->addFunction("registerUser");
$server->addFunction("getAllCars");
$server->addFunction("getConstructorCars");
$server->addFunction("getCarsEngineConstructor");
$server->handle();
?>