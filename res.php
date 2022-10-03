<?php

declare(strict_types=1);

require_once 'app/Lia.php';

$dlia = new Lia();

if (!empty($_POST)) {
 // $res = json_decode($dlia->jsDec($_POST['res']));
  header('Content-type:application/json;charset=utf-8');
  echo $dlia->jsDec($_POST['res']);
}