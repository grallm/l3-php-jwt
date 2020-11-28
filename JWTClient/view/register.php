<?php include "../view/partials/header.php" ?>

<h3>Register to Webservice</h3>

<?php if (isset($_SESSION['error'])) { ?>
  <div class="alert alert-danger"><?php echo $_SESSION['error'] ?></div>
<?php
  }
  unset($_SESSION['error']);
?>

<form action="index.php?action=register" method="POST">
  <div class="form-group">
    <label for="login">Login:</label>
    <input class="form-control" type="text" id="login" name="login" placeholder="Login" required />
  </div>
  
  <div class="form-group">
    <label for="password">Password:</label>
    <input class="form-control" type="password" id="password" name="password" placeholder="Password" required />
  </div>

  <div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">Register</button>
    <a href="?action=login">Already have an account ? Login here</a>
  </div>
</form>

<?php include '../view/partials/footer.php' ?>