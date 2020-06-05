<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use App\MoodleUser;
use Orchid\Filters\Filterable;

class Schedule extends Model
{
    use AsSource, Filterable;

    protected $allowedFilters = [
        'user_id',
        'link',
        'title',
        'day',
        'type_week',
        'lesson_number'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'link',
        'title',
        'day',
        'type_week',
        'lesson_number'
    ];

    public function user()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'user_id');
    }
}
