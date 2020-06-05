<?php

namespace App\Orchid\Layouts;

use App\Logging;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class LoggingListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'logging';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('created_at', 'Время запуска')->render(function (Logging $item) {
                return date("Y.m.d H:i:s", strtotime($item->created_at));
            })->sort(),
            TD::set('user_id', 'ФИО')
                ->render(function (Logging $user) {
                    return $user->user['full_name'];
                })->sort(),
            
            TD::set('ip_port', 'Proxy'),
            TD::set('link', 'Ссылка'),
            TD::set('status', 'Статус')->sort(),
            TD::set('description', 'Результат'),
        ];
    }
}