<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ganttconfig extends Model
{
    protected $table = 'ganttconfig';
    public $incrementing = false;
    protected $keyType = 'string';
}
