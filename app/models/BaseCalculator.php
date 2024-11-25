<?php

namespace App\Models;

use \Database;

class BaseCalculator {
    use \Database;
    
    protected $cache = [];

    public function __construct() {
        // No need to initialize db since it's handled by the trait
    }

    protected function generateCacheKey($params) {
        return md5(json_encode($params));
    }
}