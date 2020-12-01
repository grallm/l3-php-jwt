<?php
require_once '../view/partials/navbar.php';

// Display selects for service's functions with 1/2 parameters
if ($whichPage == 'constructorCars' || $whichPage == 'engineConstructorCars') {
  $constructors = ["All", "Citroen", "Porsche", "Peugeot"];
  $engines = ["All", "BLUEHDI", "V8", "Electrical", "VMax V14"];
?>
<form action="index.php?action=<?php echo $whichPage ?>" method="POST" >
  <label for="constructor">Constructors:</label>
  <select class="custom-select" id="constructor" name="constructor">
    <?php
    foreach ($constructors as $constructor) {
      // Selected if constructor and default = all
      $selected = isset($_POST['constructor']) && $_POST['constructor'] == $constructor
        ? 'selected'
        : ($constructor == 'All' ? 'selected' : '');
      echo '<option value="' . $constructor . '" ' . $selected . '>' . $constructor . '</option>';
    }
    ?>
  </select>
  
  <?php if ($whichPage == 'engineConstructorCars') { ?>
    <label for="engine">Engines:</label>
    <select class="custom-select" id="engine" name="engine">
      <?php
      foreach ($engines as $engine) {
        // Selected if constructor and default = all
        $selected = isset($_POST['engine']) && $_POST['engine'] == $engine
          ? 'selected'
          : ($engine == 'All' ? 'selected' : '');
        echo '<option value="' . $engine . '" ' . $selected . '>' . $engine . '</option>';
      }
      ?>
    </select>
  <?php } ?>

  <input class="mt-3 btn btn-primary" type="submit" value="Search" />
</form>
<?php } ?>

<div class="row mt-3">
  <?php
  $cars = is_null($cars) ? array() : $cars;

  if (is_null($cars) || sizeof($cars) == 0) {
    echo '<h5>No cars to display</h5>';
  } else if (isset($cars->error)) {
    echo '<h5 class="text-danger">' . $cars->error->message . '</h5>';
  } else {
    $displayList = '';
    
    foreach ($cars as $car) {
      $displayList = $displayList . '
        <div class="col-sm mb-3 d-flex justify-content-center">
          <div class="card h-100" style="width: 18rem;">
            <img src="' . $car->image . '" class="card-img-top" alt="' . $car->name . '">
            <div class="card-body">
              <h5 class="card-title">' . $car->name . '</h5>
              <div class="card-text">
                <ul>
                  <li>Constructor: ' . $car->constructor . '</li>
                  <li>Engine: ' . $car->engine . '</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      ';
    }
    echo $displayList;
  }
  ?>
</div>

<?php require_once '../view/partials/footer.php' ?>