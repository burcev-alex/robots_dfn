<?php

namespace App;

use App\MoodleUser;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class SchedulePage extends Model
{
    use AsSource;
    protected $fillable = ['user_id', 'type', 'link', 'time_start'];

    public function user()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'user_id');
    }
}
