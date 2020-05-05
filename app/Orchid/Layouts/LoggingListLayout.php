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
            TD::set('user_id', 'ФИО')
                ->render(function (Logging $user) {
                    return $user->user['full_name'];
                }),
            
            TD::set('ip_port', 'Proxy'),
            TD::set('link', 'Ссылка'),
            TD::set('status', 'Статус'),
            TD::set('description', 'Результат'),
        ];
    }
}