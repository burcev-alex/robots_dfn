<?php

namespace App\Console\Commands;

use Mail;
use App\SchedulePage;
use App\MoodleUser;
use App\Proxy;
use App\Logging;
use App\Mail\ReportMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VisitingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visiting:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отчет по посещению за предыдущий день';

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
        // пользователи
        $users = MoodleUser::all()->toArray();

        $report = [];

        /* Если пришел тот самый день и то самое время пары - инициализируем функции */
        foreach ($users as $iteration => $user) {
            
            // лог
            $loggingList = Logging::with('user')
            ->where('created_at', '>=', date('Y-m-d', (time()-(60*60*20))).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d').' 00:00:00')
            ->where('user_id', $user['id'])->paginate(10000);

            #var_dump($loggingList);
            $iteration = $loggingList->total();

            $report[$user['id']] = $iteration;
        }

        $this->info("\r\n");

        $data = [];
        $data['title'] = 'Отчет за период с '.date('Y-m-d', (time()-(60*60*20))).' 00:00:00'.' по '.date('Y-m-d').' 00:00:00';
        foreach($report as $userId=>$count){
            $data['user'.$userId] = 'Пользователь #'.$userId.': '.$count.' событий';
        }
        $toEmail = "Alexander.burzev@gmail.com";

        Mail::send('emails.report', $data, function ($message) use ($toEmail) {
            $message->to($toEmail)->subject('Отчет ежедневного посещения DFN!');
        });

        $this->info("\nDone!\r\n");
    }
}
