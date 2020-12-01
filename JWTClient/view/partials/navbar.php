<?php
  if (!isset($_SESSION['user'])) {
    header("Location: ?action=login");
    exit;
  }

  global $soapClient;
  $premiumExpiry = $soapClient->getUserPremiumExpiry($_SESSION['user']['jwt_api_key']);
  $premiumExpiryTst = strtotime($premiumExpiry);
  // Premium if not null and expiry > now
  $isPremium = is_null($premiumExpiryTst) ? false : ($premiumExpiryTst > time());

  $whichPage = is_null($whichPage) ? 'home' : $whichPage;

  require_once "../view/partials/header.php";
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php echo $whichPage == 'home' ? 'active' : '' ?>">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item <?php echo $whichPage == 'allCars' ? 'active' : '' ?>">
        <a class="nav-link" href="?action=allCars">All cars</a>
      </li>
      <li class="nav-item <?php echo $whichPage == 'constructorCars' ? 'active' : '' ?>">
        <a class="nav-link" href="?action=constructorCars">Constructors</a>
      </li>
      <li class="nav-item <?php echo $whichPage == 'engineConstructorCars' ? 'active' : '' ?>">
        <a class="nav-link" href="?action=engineConstructorCars">Constructors and Engines</a>
      </li>
    </ul>

    <div>
      <?php echo $isPremium
        ? ('
          <span class="text-primary">
          Premium
          </span>(until ' . date('d/m/y', $premiumExpiryTst) . ')
        ') : '
          <span class="text-success">
          Free
          </span>
        ' ?>
      <a class="btn btn-outline-primary btn-sm ml-lg-2" href="?action=premium">Subscribe 1 month</a>
      <a class="btn btn-outline-danger btn-sm ml-lg-2" href="?action=disconnect">Log out</a>
    </div>
  </div>
</nav>