<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    public function risk() {
        return $this->hasOne('App\Risk');
    }
}
