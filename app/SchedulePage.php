<?php

namespace App;

use App\MoodleUser;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;

class SchedulePage extends Model
{
    use AsSource, Filterable;

    protected $allowedFilters = [
        'user_id', 'type', 'link', 'time_start'
    ];

    protected $fillable = ['user_id', 'type', 'link', 'time_start'];

    public function user()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'user_id');
    }
}
