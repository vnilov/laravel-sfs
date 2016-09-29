<?php

namespace SimpleFileStorage\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $fillable = ['name', 'picture'];
}