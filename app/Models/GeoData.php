<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeoData extends Model
{
    use HasFactory;

    protected $table = 'geo_data';

    protected $fillable = ['division', 'district', 'thana', 'union'];
}
