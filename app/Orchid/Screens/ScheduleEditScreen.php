<?php
namespace App\Orchid\Screens;

use App\Schedule;
use App\MoodleUser;
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

class ScheduleEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создание записи в расписании';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Расписание';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Schedule $post
     *
     * @return array
     */
    public function query(Schedule $post): array
    {
        $this->exists = $post->exists;

        if($this->exists){
            $this->name = 'Редактировать';
        }

        return [
            'post' => $post
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
                Input::make('post.title')
                    ->title('Дисциплина (название)'),

                Select::make('post.lesson_number')
                    ->options([
                        '1' => '1я пара',
                        '2' => '2я пара',
                        '3' => '3я пара',
                        '4' => '4я пара',
                        '5' => '5я пара',
                        '6' => '6я пара',
                        '7' => '7я пара',
                        '8' => '8я пара'
                    ])
                    ->title('Занятие (номер)'),

                TextArea::make('post.link')
                    ->title('Ссылки которые нужно посетить')
                    ->rows(5)
                    ->placeholder('Минимум одна ссылка, если больше тогда каждая в новой строке'),

                Relation::make('post.user_id')
                    ->title('Пользователь')
                    ->fromModel(MoodleUser::class, 'full_name'),

                Select::make('post.type_week')
                    ->options([
                        'red'   => 'Красная',
                        'green' => 'Зеленая',
                    ])
                    ->title('Неделя')
                    ->help('Выберите тип недели'),

                Select::make('post.day')
                    ->options([
                        '1' => 'Пн',
                        '2' => 'Вт',
                        '3' => 'Ср',
                        '4' => 'Чт',
                        '5' => 'Пт'
                    ])
                    ->title('День недели'),

            ])
        ];
    }

    /**
     * @param Schedule    $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Schedule $post, Request $request)
    {
        $post->fill($request->get('post'))->save();

        Alert::info('Ваши данные успешно сохранены');

        return redirect()->route('platform.schedule.list');
    }

    /**
     * @param Schedule $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Schedule $post)
    {
        $post->delete()
            ? Alert::info('Вы успешно удалили запись.')
            : Alert::warning('Произошла ошибка')
        ;

        return redirect()->route('platform.schedule.list');
    }
}