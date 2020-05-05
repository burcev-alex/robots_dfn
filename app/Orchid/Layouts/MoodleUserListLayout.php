<?php

namespace App\Orchid\Layouts;

use App\MoodleUser;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class MoodleUserListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('full_name', 'ФИО')
                ->render(function (MoodleUser $user) {
                    return Link::make($user->full_name)
                        ->route('platform.moodleuser.edit', $user);
                }),
            
            TD::set('login', 'Логин'),
            TD::set('pass', 'Пароль'),
        ];
    }
}