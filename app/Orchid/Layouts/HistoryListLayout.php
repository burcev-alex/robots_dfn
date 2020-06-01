<?php

namespace App\Orchid\Layouts;

use App\SchedulePage;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class HistoryListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'history';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('user_id', 'ФИО')
                ->render(function (SchedulePage $item) {
                    return $item->user['full_name'];
                }),
            
            TD::set('type', 'Тип'),
            TD::set('link', 'Ссылка'),
            TD::set('time_start', 'Время запуска')->render(function (SchedulePage $item) {
                return date("d.m.Y H:i:s", $item->time_start);
            }),
        ];
    }
}