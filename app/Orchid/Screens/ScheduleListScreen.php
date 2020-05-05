<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\ScheduleListLayout;
use App\Schedule;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ScheduleListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Расписание занятий';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все пары расписанные по-недельно';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'schedules' => Schedule::paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить')
                ->icon('icon-pencil')
                ->route('platform.schedule.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ScheduleListLayout::class
        ];
    }
}