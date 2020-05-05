<?php

namespace App;

use App\MoodleUser;
use Illuminate\Database\Eloquent\Model;

class SchedulePage extends Model
{
    protected $fillable = ['user_id', 'type', 'link', 'time_start'];

    public function user()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'user_id');
    }
}
