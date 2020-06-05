<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\HistoryListLayout;
use App\Orchid\Screens\LoggingFiltersLayout;
use App\SchedulePage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class HistoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'History';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Задачи текущего дня';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'history' => SchedulePage::with('user')
                ->filters()
                ->filtersApplySelection(LoggingFiltersLayout::class)
                ->defaultSort('id', 'asc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            LoggingFiltersLayout::class,
            HistoryListLayout::class
        ];
    }
}