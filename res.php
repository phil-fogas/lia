<?php

declare(strict_types=1);

require_once __DIR__ . '/app/Lia.php';

$dlia = new Lia();

if (!empty($_POST['res'])) {
  header('Content-type:application/json;charset=utf-8');
  echo ($dlia->jsDec($_POST['res']));
}
