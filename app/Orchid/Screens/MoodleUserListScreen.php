<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\MoodleUserListLayout;
use App\MoodleUser;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class MoodleUserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Пользователи Moodle';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все пользователи';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'users' => MoodleUser::paginate()
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
                ->route('platform.moodleuser.edit')
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
            MoodleUserListLayout::class
        ];
    }
}