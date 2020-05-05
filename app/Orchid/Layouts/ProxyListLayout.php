<?php

namespace App\Orchid\Layouts;

use App\Proxy;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ProxyListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'proxy';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('id', 'ID')
                ->render(function (Proxy $item) {
                    return Link::make($item->id)
                        ->route('platform.proxy.edit', $item);
                }),
            
            TD::set('ip_port', 'IP:PORT'),
        ];
    }
}