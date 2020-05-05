<?php

namespace App\Orchid\Screens\Examples;

use App\Orchid\Layouts\Examples\ChartBarExample;
use App\Orchid\Layouts\Examples\MetricsExample;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class LogScreen extends Screen
{
    /**
     * Fish text for the table.
     */
    public const TEXT_EXAMPLE = 'Lorem ipsum at sed ad fusce faucibus primis, potenti inceptos ad taciti nisi tristique
    urna etiam, primis ut lacus habitasse malesuada ut. Lectus aptent malesuada mattis ut etiam fusce nec sed viverra,
    semper mattis viverra malesuada quam metus vulputate torquent magna, lobortis nec nostra nibh sollicitudin
    erat in luctus.';

    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Лог';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Статистика переходов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'charts'  => [
                [
                    'name'   => 'Бурцев',
                    'values' => [25, 40, 30, 35, 8, 52, 17],
                ],
                [
                    'name'   => 'Купчак',
                    'values' => [25, 50, -10, 15, 18, 32, 27],
                ],
                [
                    'name'   => 'Зубыч',
                    'values' => [15, 20, -3, -15, 58, 12, -17],
                ],
                [
                    'name'   => 'Харитонова',
                    'values' => [10, 33, -8, -3, 70, 20, -34],
                ],
            ],
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            ChartBarExample::class
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showToast(Request $request)
    {
        Toast::warning($request->get('toast', 'Hello, world! This is a toast message.'));

        return back();
    }
}
