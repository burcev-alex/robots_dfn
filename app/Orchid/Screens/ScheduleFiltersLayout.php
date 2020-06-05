<?php

namespace App\Orchid\Screens;

use App\Orchid\Filters\MoodleUserFilter;
use App\Orchid\Filters\ScheduleWeekFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ScheduleFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            MoodleUserFilter::class,
            ScheduleWeekFilter::class,
        ];
    }
}
