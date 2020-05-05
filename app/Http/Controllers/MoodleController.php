<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \GuzzleHttp\Cookie\CookieJar;

class MoodleController extends Controller
{
    protected $userId;
    function index(){

        $this->userId = 2;

        if (!file_exists($_SERVER['DOCUMENT_ROOT']."/cookie/".$this->userId.".txt")) {
            
            $postFields = array(
                "username"=>"burzev",
                "password"=>"burzev_G5",
                "anchor"=>""
            );
            $res = $this->auth($postFields);
            dd($res);
        }
        else{
            $url = "/course/view.php?id=523";
            $res = $this->request($url, "/cookie/".$this->userId.".txt");
            dd($res);
        }

        $resource = [
            '12'
        ];
        return view('moodle', ['resource' => $resource]);
    }

    private function auth($post = [])
    {
        return $this->request("/login/index.php", "/cookie/".$this->userId.".txt", $post);
    }

    private function request($url, $cookie, $post = [])
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "http://www.dfn.mdpu.org.ua".$url ); // отправляем на 
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
