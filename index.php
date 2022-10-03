<?php

declare(strict_types=1);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once 'app/Lia.php';

$dlia = new Lia();

if (!empty($_POST)) {
  //$res = $dlia->Dec($_POST['res']);
 
}


include 'dia.phtml';
