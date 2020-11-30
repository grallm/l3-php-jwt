<div class="row">
  <?php
  $cars = is_null($cars) ? array() : $cars;

  if (is_null($cars) || sizeof($cars) == 0) {
    echo '<h5>No cars to display</h5>';
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