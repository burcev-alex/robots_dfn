<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\LoggingListLayout;
use App\Logging;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class LoggingListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Log';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'История запросов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'logging' => Logging::paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            LoggingListLayout::class
        ];
    }
}