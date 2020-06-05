<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Logging extends Model
{
    use AsSource, Filterable;

    protected $allowedFilters = [
        'ip_port',
        'description',
        'user_id',
        'status',
        'link'
    ];

    protected $allowedSorts = [
        'created_at',
        'ip_port',
        'description',
        'user_id',
        'status',
        'link'
    ];

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
