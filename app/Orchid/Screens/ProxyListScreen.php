<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\ProxyListLayout;
use App\Proxy;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ProxyListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Proxy List';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'proxy' => Proxy::paginate()
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
                ->route('platform.proxy.edit')
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
            ProxyListLayout::class
        ];
    }
}