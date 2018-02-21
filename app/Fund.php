<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = [ 'name', 'description', 'user_id', 'risk_id' ];

    public function risk() {
        return $this->hasOne('App\Risk', 'id', 'risk_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
