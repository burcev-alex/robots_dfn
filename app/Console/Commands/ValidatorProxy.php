<?php

namespace App\Console\Commands;

use App\Proxy;
use Illuminate\Console\Command;

class ValidatorProxy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validator:proxy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Валидация списока прокси-серверов';

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
        

        $items = Proxy::all()->toArray();
            
        $countLine = count($items);

        foreach ($items as $key => $data) {

            $url = 'http://myip.ru/';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_PROXY, $data['ip_port']);

            if($data['type'] == 'socks5'){
                $proxtType = CURLPROXY_SOCKS5;
            }
            else if($data['type'] == 'socks4'){
                $proxtType = CURLPROXY_SOCKS4;
            }
            else if($data['type'] == 'socks4a'){
                $proxtType = CURLPROXY_SOCKS4A;
            }
            else{
                $proxtType = CURLPROXY_HTTP;
            }
            curl_setopt($ch, CURLOPT_PROXYTYPE, $proxtType);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $curl_scraped_page = curl_exec($ch);

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            
            if(IntVal($http_code) >= 200 && IntVal($http_code) <= 399){
                // все ок, оставляем прокси-сервер
            }
            else{
                Proxy::destroy($data['id']);
            }

            $bar = $this->output->createProgressBar($countLine);
            
        }

        if (count($items) > 0) {
            $bar->advance();

            $bar->finish();
        }
    }
}
