<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use App\MoodleUser;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class ScheduleWeekFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'type_week',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Неделя';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('type_week', $this->request->get('type_week'));;
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('type_week')
                    ->options([
                        'red'   => 'Красная',
                        'green' => 'Зеленая',
                    ])
                    ->title('Неделя')
                    ->empty()
                    ->value($this->request->get('type_week')),
        ];
    }
}
