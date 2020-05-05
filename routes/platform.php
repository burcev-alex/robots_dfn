<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\LogScreen;
use App\Orchid\Screens\ScheduleEditScreen;
use App\Orchid\Screens\ScheduleListScreen;
use App\Orchid\Screens\MoodleUserEditScreen;
use App\Orchid\Screens\MoodleUserListScreen;
use App\Orchid\Screens\ProxyEditScreen;
use App\Orchid\Screens\ProxyListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)->name('platform.main');

// Users...
Route::screen('users/{users}/edit', UserEditScreen::class)->name('platform.systems.users.edit');
Route::screen('users', UserListScreen::class)->name('platform.systems.users');

// Roles...
Route::screen('roles/{roles}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
Route::screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
Route::screen('roles', RoleListScreen::class)->name('platform.systems.roles');

$this->router->screen('schedule/{post?}', ScheduleEditScreen::class)
    ->name('platform.schedule.edit');

$this->router->screen('schedules', ScheduleListScreen::class)
    ->name('platform.schedule.list');

$this->router->screen('moodleuser/{post?}', MoodleUserEditScreen::class)
    ->name('platform.moodleuser.edit');

$this->router->screen('moodleusers', MoodleUserListScreen::class)
    ->name('platform.moodleuser.list');

$this->router->screen('proxy/{post?}', ProxyEditScreen::class)
    ->name('platform.proxy.edit');

$this->router->screen('proxys', ProxyListScreen::class)
    ->name('platform.proxy.list');

Route::screen('log', LogScreen::class)->name('platform.log');
//Route::screen('/dashboard/screen/idea', 'Idea::class','platform.screens.idea');
