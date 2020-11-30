<?php
/**
 * Check if user logins are correct
 * @return user user if found, NULL if not
 */
function checkLogin(string $login, string $password)
{
    global $db;

    $query = "SELECT * FROM users WHERE login = :login AND password = :password";

    $statement = $db->prepare($query);
    $statement->execute([
      ':login' => $login,
      ':password' => $password
    ]);
    $result = $statement->fetch();

    return $statement->rowCount() == 1 ? $result : null;
}

/**
 * Register a user to the DB and ask for an API Key to server
 * @throws Exception if login already used
 * @return user user if inserted, NULL if not
 */
function register(string $login, string $password, string $apiKey)
{
    global $db;

    $query = "INSERT INTO users (jwt_api_key, login, password) VALUES (?, ?, ?)";

    $statement = $db->prepare($query);

    try {
      $statement->execute([
        $apiKey,
        $login,
        $password
      ]);
    } catch (Exception $err) {
      // Login already used
      if ($statement->errorInfo()[1] == 1062) {
        throw new Exception();
      }

      // Other exception
      return null;
    }

    return array(
      'id' => $db->lastInsertId(),
      'login' => $login,
      'password' => $password,
      'jwt_api_key' => NULL
    );
}
