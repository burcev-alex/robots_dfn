<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use App\MoodleUser;

class Schedule extends Model
{
    use AsSource;

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
