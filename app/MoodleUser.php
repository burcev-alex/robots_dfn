<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class MoodleUser extends Model
{
    use AsSource;

    /**
     * @var array
     */
    protected $fillable = [
        'login',
        'pass',
        'full_name'
    ];
}
