<?php

namespace SimpleFileStorage\Eloquent;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'size', 'type'];
}