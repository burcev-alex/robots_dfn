<?php
namespace App\Orchid\Screens;

use App\MoodleUser;
use App\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class MoodleUserEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Пользователь Moodle';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Создание пользователя для обработки робота';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param MoodleUser $user
     *
     * @return array
     */
    public function query(MoodleUser $user): array
    {
        $this->exists = $user->exists;

        if($this->exists){
            $this->name = 'Редактировать';
        }

        return [
            'user' => $user
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
            Button::make('Создать')
                ->icon('icon-pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Редактировать')
                ->icon('icon-note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Удалить')
                ->icon('icon-trash')
                ->method('remove')
                ->canSee($this->exists),
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
            Layout::rows([
                Input::make('user.full_name')
                    ->title('ФИО'),

                Input::make('user.login')
                    ->title('Логин'),

                Input::make('user.pass')
                    ->title('Пароль'),

            ])
        ];
    }

    /**
     * @param MoodleUser    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(MoodleUser $user, Request $request)
    {
        $user->fill($request->get('user'))->save();

        Alert::info('Ваши данные успешно сохранены');

        return redirect()->route('platform.moodleuser.list');
    }

    /**
     * @param MoodleUser $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(MoodleUser $user)
    {
        $user->delete()
            ? Alert::info('Вы успешно удалили запись.')
            : Alert::warning('Произошла ошибка')
        ;

        return redirect()->route('platform.schedule.list');
    }
}