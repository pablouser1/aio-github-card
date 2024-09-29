<?php
namespace App\Models;

class CacheData {
  public bool $exists;
  public mixed $data;
  function __construct(bool $exists, mixed $data) {
    $this->exists = $exists;
    $this->data = $data;
  }
}
