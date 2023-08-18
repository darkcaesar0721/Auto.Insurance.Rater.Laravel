<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Optimus\Optimus;

class Hasher
{
    public static function encode(...$args) {
        return app(Optimus::class)->encode(...$args);
    }

    public static function decode($enc) {
        return app(Optimus::class)->decode($enc) ? app(Optimus::class)->decode($enc) : null;
    }
}
