<?php
// include configurations
require 'config.php';

// include dependencies
require_once 'libraries/google-api-php-client-2.4.0/vendor/autoload.php';
require_once 'functions.php';

$pageVisitId = $_GET['pageVisitId'];
$timeInSeconds = time();
capture('Donation Attempts', ["=$timeInSeconds/86400 + DATE(1970,1,1)", $pageVisitId]);
?>