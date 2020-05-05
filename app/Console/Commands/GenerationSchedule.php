<?php

namespace App\Console\Commands;

use App\SchedulePage;
use App\User;
use Illuminate\Console\Command;

class GenerationSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generation:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация расписания запуска команд посещения страниц Moodle';

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
        /*
            1 пара - 08:00@09:20
            2 пара - 09:35@10:55
            3 пара - 11:10@12:30
            4 пара - 12:45@14:05
            5 пара - 14:20@15:40
            6 пара - 15:55@17:15
            7 пара - 17:30@18:50
            8 пара - 19:05@20:25
        */
        
        $schedule = [
            'red' => [
                    '1' => [
                                '08:00@09:20' => "/course/view.php?id=417", // 1 пара. Стандартизація  та  сертифікація програмного забезпечення
                                '09:35@10:55' => "/course/view.php?id=420", // 2 пара. Створення та адміністрування дистанційних освітних ресурсів
                                
                            ],
                    '2' => [
                                '08:00@09:20' => "/course/view.php?id=355", // 1 пара. Управління програмними проектами
                                '09:35@10:55' => "/course/view.php?id=419", // 2 пара. Розробка мобільних додатків
                            ],
                    '4' => [
                                '12:45@14:05' => "/course/view.php?id=417", // 4 пара. Стандартизація  та  сертифікація програмного забезпечення
                                '14:20@15:40' => "/course/view.php?id=350", // 5 пара. Технології інформаційного менеджменту
                            ],
                    '5' => [
                                '08:00@09:20' => "/course/view.php?id=328", // 1 пара. Проектування програмного забезпечення
                                '09:35@10:55' => "/course/view.php?id=420", // 2 пара. Створення та адміністрування дистанційних освітних ресурсів
                                '11:10@12:30' => "/course/view.php?id=355", // 3 пара. Управління освітніми проектами
                            ],
            ],
            'green' => [
                    '1' => [
                                '08:00@09:20' => "/course/view.php?id=417", // 1 пара. Стандартизація  та  сертифікація програмного забезпечення
                                '09:35@10:55' => "/course/view.php?id=355", // 2 пара. Управління освітніми проектами
                                '11:10@12:30' => "/course/view.php?id=419", // 3 пара. Розробка мобільних додатків
                            ],
                    '2' => [
                                '08:00@09:20' => "/course/view.php?id=417", // 1 пара. Стандартизація  та  сертифікація програмного забезпечення
                                '09:35@10:55' => "/course/view.php?id=350", // 2 пара. Технології інформаційного менеджменту
                            ],
                    '4' => [
                                '08:00@09:20' => "/course/view.php?id=350", // 1 пара. Технології інформаційного менеджменту
                                '09:35@10:55' => "/course/view.php?id=420", // 2 пара. Створення та адміністрування дистанційних освітних ресурсів
                            ],
                    '5' => [
                                '09:35@10:55' => "/course/view.php?id=355", // 2 пара. Управління освітніми проектами
                                '11:10@12:30' => "/course/view.php?id=328", // 3 пара. Проектування програмного забезпечення
                            ],
            ],
        ];

        $scheduleList = [];
        $scheduleList[] = [
            'user_id' => 1,
            'list' => $schedule
        ];


        if (count($scheduleList) > 0) {
            $list = [];
            foreach ($scheduleList as $item) {
                foreach ($item['list'][$this->getCodeWeek()][date("N", time())] as $times => $link) {
                    $time = explode('@', $times);
                    $time_from = strtotime(date('d-m-Y ', time()).$time[0]);
                    $time_to = strtotime(date('d-m-Y ', time()).$time[1]);

                    $i = $time_from;

                    $list[] = [
                        'type' => 'auth',
                        'user_id' => $item['user_id'],
                        'time_start' => $i,
                        'link' => "/login/index.php"
                    ];

                    do { 
                        $i = $i + rand(65, 200);
                        
                        $list[] = [
                            'type' => 'page',
                            'user_id' => $item['user_id'],
                            'time_start' => $i,
                            'link' => $link
                        ];

                    } while ($i <= $time_to);
                }
            }

            $countLine = count($list);

            foreach ($list as $key => $data) {
                $bar = $this->output->createProgressBar($countLine);

                $this->save($data);
            }
            $bar->advance();
        }

        $bar->finish();
    }

    /**
     * Определить какая неделя четная а какая нечетная
     * зелена  - четная
     * красная - нечетная
     *
     * @return void
     */
    protected function getCodeWeek()
    {
        return date("W", time())%2==0 ? 'green' : 'red';
    }

    protected function save($data)
    {
        $fields = new SchedulePage();
        $fields->fill($data);
        $fields->save();

        $id = $fields->id;
    }
}
