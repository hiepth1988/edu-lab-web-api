<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpCountryRange extends Model
{
    public $timestamps = false;

    protected $fillable = ['ip_version', 'range_start', 'range_end', 'country'];
}
