<?php

namespace App\Orchid\Layouts;

use App\Schedule;
use App\MoodleUser;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ScheduleListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'schedules';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('user_id', 'Пользователь')
                ->render(function (Schedule $post) {
                    return $post->user['full_name'];
                }),

            TD::set('title', 'Дисциплина')
                ->render(function (Schedule $post) {
                    return Link::make($post->title)
                        ->route('platform.schedule.edit', $post);
                }),

            TD::set('type_week', 'Неделя')->render(function (Schedule $post) {
                if($post->type_week == 'red'){
                    return 'Красная';
                }
                else{
                    return 'Зеленая';
                }
            }),
            TD::set('day', 'День недели')->render(function (Schedule $post) {
                switch ($post->day) {
                    case '1':
                        return 'Пн';
                        break;
                    case '2':
                        return 'Вт';
                        break;
                    case '3':
                        return 'Ср';
                        break;
                    case '4':
                        return 'Чт';
                        break;
                    case '5':
                        return 'Пт';
                        break;
                    default:
                        return '-';
                        break;
                }

            }),
            TD::set('lesson_number', 'Номер пары'),
        ];
    }
}