<?php
namespace App\Orchid\Screens;

use App\Proxy;
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

class ProxyEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создание proxy';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Создание proxy для робота';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Proxy $item
     *
     * @return array
     */
    public function query(Proxy $item): array
    {
        $this->exists = $item->exists;

        if($this->exists){
            $this->name = 'Редактировать';
        }

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
                Input::make('item.ip_port')
                    ->title('IP:PORT'),
                
                Select::make('item.type')
                    ->options([
                        'http' => 'HTTP',
                        'https' => 'HTTPS',
                        'socks5' => 'SOCKS5',
                        'socks4' => 'SOCKS4'
                ])
            ])
        ];
    }

    /**
     * @param Proxy    $item
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Proxy $item, Request $request)
    {
        $item->fill($request->get('item'))->save();

        Alert::info('Ваши данные успешно сохранены');

        return redirect()->route('platform.proxy.list');
    }

    /**
     * @param Proxy $item
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Proxy $item)
    {
        $item->delete()
            ? Alert::info('Вы успешно удалили запись.')
            : Alert::warning('Произошла ошибка')
        ;

        return redirect()->route('platform.proxy.list');
    }
}