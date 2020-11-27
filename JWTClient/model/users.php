<?php
/**
 * Check if user logins are correct
 * @return boolean true if correct pair login/password
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
    $results = $statement->fetch();
    $statement->closeCursor();

    return count($results) == 1;
}
