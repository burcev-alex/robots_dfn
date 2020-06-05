<?php

namespace App\Console\Commands;

use App\SchedulePage;
use App\MoodleUser;
use App\Proxy;
use App\Logging;
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
        // текущие задачи
        $items = SchedulePage::all()->toArray();

        // список прокси
        $proxyList = Proxy::all()->toArray();

        $count = 0;

        /* Если пришел тот самый день и то самое время пары - инициализируем функции */
        foreach ($items as $iteration => $data) {
            
            if (IntVal($data['time_start']) > time()) {
                continue;
            }

            if($count > 10) break;

            $user = MoodleUser::where('id', $data['user_id'])->get()->toArray()[0];

            if (strlen($user['login']) == 0) {
                SchedulePage::destroy($data['id']);

                continue;
            }

            $postFields = array(
                "username" => $user['login'],
                "password" => $user['pass'],
                "anchor" => ""
            );

            // случайный прокси
            shuffle($proxyList);
            $randProxy = current($proxyList);

            #$randProxy["ip_port"] = "5.53.124.38:80";

            $loggingData = [
                'ip_port' => $randProxy["ip_port"],
                'user_id' => $data['user_id']
            ];

            if ($data['type'] == 'auth') {
                $resAuthHtml = $this->auth($data['user_id'], $randProxy, $postFields);
                $loggingData['link'] = "http://www.dfn.mdpu.org.ua/login/index.php";
                $loggingData['description'] = $this->parseTitle($resAuthHtml);
                if (substr_count($resAuthHtml, "Личный кабинет") > 0) {
                    $loggingData['status'] = 'success';
                } else {
                    $loggingData['status'] = 'error';
                }
            } else {
                $resPageHtml = $this->request($data['link'], "/cookie/".$data['user_id'].".txt", $randProxy);
                if (substr_count($this->parseTitle($resPageHtml), "____") == 0) {
                    $loggingData['link'] = $data['link'];
                    $loggingData['description'] = $this->parseTitle($resPageHtml);

                    if(substr_count($loggingData['description'], "Not Found") > 0 || substr_count($loggingData['description'], "error code") > 0){
                        Proxy::destroy($randProxy["id"]);
                        $loggingData['status'] = 'error';
                    }
                    else{
                        $loggingData['status'] = 'success';
                    }
                    
                } else {
                    $resAuthHtml = $this->auth($data['user_id'], $randProxy, $postFields);
                    $loggingData['link'] = "http://www.dfn.mdpu.org.ua/login/index.php";
                    $loggingData['description'] = $this->parseTitle($resAuthHtml);

                    if(substr_count($loggingData['description'], "Not Found") > 0 || substr_count($loggingData['description'], "error code") > 0){
                        Proxy::destroy($randProxy["id"]);
                        $loggingData['status'] = 'error';
                    }

                    if (substr_count($resAuthHtml, "Личный кабинет") > 0) {
                        $loggingData['status'] = 'success';
                    } else {
                        $loggingData['status'] = 'error';
                    }
                }
            }

            $this->log($loggingData);

            SchedulePage::destroy($data['id']);

            unset($loggingData);
            unset($randProxy);
            unset($user);
            $count++;

            sleep(rand(1,3));
        }
    }

    private function parseTitle($text = ""){
        if(strlen($text) == 0){
            return $text;
        }
        if(substr_count(strtolower($text), "error code") > 0){
            return $text;
        
        }
        $arr = explode("</title>", $text);
        if (strlen($arr[0]) > 0) {
            if(is_array($arr)){
                $res = explode("<title>", $arr[0]);

                if (strlen($res[1]) > 0) {
                    return $res[1];
                }
                else{
                    return "_______";
                }
            }
            else{
                return "_______";
            }
        }
        else{
            return '_______';
        }
    }

    private function auth($userId, $proxy = [], $post = [])
    {
        return $this->request("/login/index.php", "/cookie/".$userId.".txt", $proxy, $post);
    }

    private function request($url, $cookie, $proxy = [], $post = [])
    {
        $_SERVER['DOCUMENT_ROOT'] = "/var/www/html/public";

        if(substr_count($url, "dfn.mdpu.org.ua") > 0){
            $arLink = explode("org.ua/", $url);
            $url = $arLink[1];
        }

        if(IntVal(rand(1,5)) === 2){
            $proxy = [];
        }

        $domain = "http://www.dfn.mdpu.org.ua/";

        $finalUrl = $domain.$url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl); // отправляем на
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (count($proxy) > 0) {
            //Указываем к какому прокси подключаемся и передаем логин-пароль
            curl_setopt($ch, CURLOPT_PROXY, $proxy['ip_port']);
        
            //доступные значения для типа используемого прокси-сервера:  CURLPROXY_HTTP (по умолчанию), CURLPROXY_SOCKS4, CURLPROXY_SOCKS5, CURLPROXY_SOCKS4A или CURLPROXY_SOCKS5_HOSTNAME. 
            if($proxy['type'] == 'socks5'){
                $proxtType = CURLPROXY_SOCKS5;
            }
            else if($proxy['type'] == 'socks4'){
                $proxtType = CURLPROXY_SOCKS4;
            }
            else if($proxy['type'] == 'socks4a'){
                $proxtType = CURLPROXY_SOCKS4A;
            }
            else{
                $proxtType = CURLPROXY_HTTP;
            }
            curl_setopt($ch, CURLOPT_PROXYTYPE, $proxtType);
        }
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

    protected function log($data)
    {
        $fields = new Logging();
        $fields->fill($data);
        $fields->save();

        $id = $fields->id;

        return $id;
    }
}
