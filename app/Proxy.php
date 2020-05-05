<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Proxy extends Model
{
    use AsSource;

    /**
     * @var array
     */
    protected $fillable = [
        'ip_port'
    ];
}
