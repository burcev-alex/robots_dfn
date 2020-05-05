<?php

namespace App\Console\Commands;

use App\SchedulePage;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VisitingPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visiting:page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Посещение страниц';

    protected $rows;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $items = SchedulePage::all()->toArray();

        /* Если пришел тот самый день и то самое время пары - инициализируем функции */
        foreach ($items as $iteration => $data) {
            if (IntVal($data['time_start']) > time()) {
                continue;
            }

            $postFields = array(
                "username"=>"burzev",
                "password"=>"burzev_G5",
                "anchor"=>""
            );

            if ($data['type'] == 'auth') {
                $res = $this->auth($data['user_id'], $postFields);
                if (substr_count($res, "Личный кабинет") > 0) {
                    Log::channel('crontab')->info('AUTH SUCCESS = '.date("d.m.Y H:i:s", $data['time_start']));
                } else {
                    Log::channel('crontab')->info('AUTH ERROR = '.date("d.m.Y H:i:s", $data['time_start']));
                }
            } else {
                $url = "/course/view.php?id=523";
                $res = $this->request($data['link'], "/cookie/".$data['user_id'].".txt");

                if (substr_count($res, "Личный кабинет") > 0) {
                    Log::channel('crontab')->info('PAGE SUCCESS = '.date("d.m.Y H:i:s", $data['time_start']));
                } else {
                    $res = $this->auth($data['user_id'], $postFields);
                    Log::channel('crontab')->info('PAGE ERROR = '.date("d.m.Y H:i:s", $data['time_start']));
                }
            }

            SchedulePage::destroy($data['id']);

            sleep(5);
        }
    }

    private function auth($userId, $post = [])
    {
        return $this->request("/login/index.php", "/cookie/".$userId.".txt", $post);
    }

    private function request($url, $cookie, $post = [])
    {
        $_SERVER['DOCUMENT_ROOT'] = "D:/WORK/dfn-laravel.loc/public";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.dfn.mdpu.org.ua".$url); // отправляем на
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].$cookie); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].$cookie);
        if (count($post) > 0) {
            curl_setopt($ch, CURLOPT_POST, true); // использовать данные в post
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
