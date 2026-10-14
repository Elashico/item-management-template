<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $fillable = ['box_id', 'name'];
    protected $touches = ['box'];
    public function box() { return $this->belongsTo(Box::class); }
}
