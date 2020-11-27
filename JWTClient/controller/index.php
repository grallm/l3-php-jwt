<?php
if (isset($_GET['login']) && isset($_GET['password'])) {
  echo checkLogin($_GET['login'], $_GET['password']);
}