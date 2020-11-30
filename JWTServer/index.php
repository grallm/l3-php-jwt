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
 * @return user|null returns user if found, null if not
 */
function getUserFromJwt($jwtKey) {
  global $db;

  $query = "SELECT * FROM users WHERE jwt_api_key = ?";

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
 * Subscribe user for Premium for 1 month more
 * @return datetime Premium expiry date 
 */
function subscribePremium($jwtKey) {
  global $db;

  // Checking Key validity
  $user = getUserFromJwt($jwtKey);
  if (is_null($user)) {
    return json_encode([
      'error' => [
        'message' => 'Invalid API Key',
        'code' => '0'
      ]
    ]);
  }

  // If null set date to now
  $expiryDate = new DateTime(is_null($user['premium_expires']) ? 'now' : $user['premium_expires']);

  // Set expiry date to next month
  $expiryDate->add(new DateInterval('P1M'));

  $query = "UPDATE users SET premium_expires = ? WHERE id = ?";

  $statement = $db->prepare($query);
  $statement->execute([
    $expiryDate->format('Y-m-d'),
    $user['id']
  ]);

  return $expiryDate->format('Y-m-d');
}

/**
 * Get user's Premium expiry date
 * @return datetime|null Premium expiry date
 */
function getUserPremiumExpiry($jwtKey) {
  // Checking Key validity
  $user = getUserFromJwt($jwtKey);
  if (is_null($user)) {
    return json_encode([
      'error' => [
        'message' => 'Invalid API Key',
        'code' => '0'
      ]
    ]);
  }

  return $user['premium_expires'];
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
      'error' => [
        'message' => 'Invalid API Key',
        'code' => '0'
      ]
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
      'error' => [
        'message' => 'Invalid API Key',
        'code' => '0'
      ]
    ]);
  }

  $query = "SELECT * FROM cars WHERE constructor LIKE :constructor";

  $statement = $db->prepare($query);
  $statement->bindValue(':constructor', is_null($constructor) || empty($constructor) || $constructor == 'All' ? '%' : $constructor);
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
      'error' => [
        'message' => 'Invalid API Key',
        'code' => '0'
      ]
    ]);
  }

  $query = "SELECT * FROM cars WHERE constructor LIKE :constructor AND engine LIKE :engine";

  $statement = $db->prepare($query);
  $statement->bindValue(':constructor', is_null($constructor) || empty($constructor) || $constructor == 'All' ? '%' : $constructor);
  $statement->bindValue(':engine', is_null($engine) || empty($engine) || $engine == 'All' ? '%' : $engine);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();

  return json_encode($result);
}

ini_set("soap.wsdl_cache_enabled", 0);
$server = new SoapServer("cars.wsdl");
$server->addFunction("registerUser");
$server->addFunction("subscribePremium");
$server->addFunction("getUserPremiumExpiry");
$server->addFunction("getAllCars");
$server->addFunction("getConstructorCars");
$server->addFunction("getCarsEngineConstructor");
$server->handle();
?>