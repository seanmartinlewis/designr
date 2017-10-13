<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [ 'title', 'email', 'city', 'state', 'address', 'has_paid', 'picture_path', 'shirt_color', 'shipped_on'];
}
