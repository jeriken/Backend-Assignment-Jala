<?php

namespace App\Enums;

use ReflectionClass;

final class SalesEnum
{
  const PENDING = 'pending';
  const DONE = 'done';

  static function getConstants()
  {
    $oClass = new ReflectionClass(__CLASS__);
    return $oClass->getConstants();
  }
}