<?php

namespace App\Orchid\Screens;

use App\Orchid\Filters\MoodleUserFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class LoggingFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            MoodleUserFilter::class,
        ];
    }
}
