<?php include "../view/partials/header.php" ?>

<h3>Login to Webservice</h3>

<?php if (isset($_SESSION['error'])) { ?>
  <div class="alert alert-danger"><?php echo $_SESSION['error'] ?></div>
<?php
  }
  unset($_SESSION['error']);
?>

<form action="index.php?action=login" method="POST">
  <div class="form-group">
    <label for="login">Login:</label>
    <input class="form-control" type="text" id="login" name="login" placeholder="Login" required />
  </div>
  
  <div class="form-group">
    <label for="password">Password:</label>
    <input class="form-control" type="password" id="password" name="password" placeholder="Password" required />
  </div>

  <div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">Login</button>
    <a href="?action=register">You don't have an account ? Register here</a>
  </div>
</form>

<?php include '../view/partials/footer.php' ?>