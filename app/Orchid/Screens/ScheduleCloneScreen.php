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
use Orchid\Screen\TD;
use Illuminate\Support\Facades\Storage;

class ScheduleCloneScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Клонирование расписания';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Дублирование расписания от одного пользователя к другому';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Schedule $item
     *
     * @return array
     */
    public function query(Schedule $item): array
    {
        $this->exists = $item->exists;

        return [
            'item' => $item
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
            Button::make('Копировать')
                ->icon('icon-pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists)
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
                Relation::make('user_from')
                    ->title('From')
                    ->fromModel(MoodleUser::class, 'full_name'),
                Relation::make('user_to')
                    ->title('To')
                    ->fromModel(MoodleUser::class, 'full_name'),
            ])
        ];
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        if ($request->has('user_from')) {
            $userFrom = $request->get('user_from');
            $userTo = $request->get('user_to');
            
            $status = true;
        }
        else{
            $status = false;
        }

        if(IntVal($userFrom) !== IntVal($userTo)){

            $items = Schedule::where('user_id', $userFrom)->paginate(500);
            foreach($items as $key=>$item){
                $data = $item->toArray();
                $data['user_id'] = $userTo;
                
                $post = new Schedule;

                $post->user_id = $userTo;
                $post->link = $data['link'];
                $post->title = $data['title'];
                $post->day = $data['day'];
                $post->type_week = $data['type_week'];
                $post->lesson_number = $data['lesson_number'];

                $post->save();
            }
        }
        else{
            $status = false;
        }

        if($status){
            Alert::info('Ваши данные успешно загружены');
        }
        else{
            Alert::error('Ваши данные успешно загружены');
        }

        return redirect()->route('platform.schedule.list');
    }
}