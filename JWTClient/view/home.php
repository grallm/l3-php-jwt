<?php include "../view/partials/header.php" ?>

<h3>Home</h3>

<?php
  if (!isset($_SESSION['user'])) {
    header("Location: ?action=login");
    exit;
  }
?>


<?php include '../view/partials/footer.php' ?>