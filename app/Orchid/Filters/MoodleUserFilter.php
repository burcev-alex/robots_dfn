<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use App\MoodleUser;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;

class MoodleUserFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'user_id',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Пользователь';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('user', function (Builder $query) {
            $query->where('user_id', $this->request->get('user_id'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Relation::make('user_id')
                    ->title('Пользователь')
                    ->fromModel(MoodleUser::class, 'full_name')
                    ->empty()
                    ->value($this->request->get('user_id')),
        ];
    }
}
