<?php

declare(strict_types=1);

namespace App\Orchid\Composers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

class MainMenuComposer
{
    /**
     * @var Dashboard
     */
    private $dashboard;

    /**
     * MenuComposer constructor.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Registering the main menu items.
     */
    public function compose()
    {
        // Main
        $this->dashboard->menu
            ->add(Menu::MAIN,
                ItemMenu::label('Расписание занятий')
                    ->icon('icon-list')
                    ->route('platform.schedule.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Пользователи moodle')
                    ->icon('icon-list')
                    ->route('platform.moodleuser.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Proxy')
                    ->icon('icon-list')
                    ->route('platform.proxy.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Лог')
                    ->icon('icon-list')
                    ->route('platform.logging.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('История на текущий день')
                    ->icon('icon-list')
                    ->route('platform.history.list')
            );
    }
}
