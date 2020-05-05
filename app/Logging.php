<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Logging extends Model
{
    use AsSource;

    protected $fillable = [
        'ip_port',
        'description',
        'user_id',
        'status',
        'link'
    ];

    public function user()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'user_id');
    }
}
