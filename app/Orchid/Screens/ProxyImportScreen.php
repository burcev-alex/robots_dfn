<?php
namespace App\Orchid\Screens;

use App\Proxy;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class ProxyImportScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Import proxy-server';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Загрузка из csv';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Proxy $item
     *
     * @return array
     */
    public function query(Proxy $item): array
    {
        $this->exists = $item->exists;

        return [
            'item' => $item
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Загрузить')
                ->icon('icon-pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists)
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('csv_file')->type('file')
            ])
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->store('/proxy');
            $file_url = $path;
        }
        else{
            $file_url = "";
        }
        
        $items = $this->get_data($file_url);
        foreach($items as $data){
            if(strlen($data[0]) == 0) continue;

            if(trim($data[2]) == "SOCKS4"){
                $typeProxy = "socks4";
            }
            else if($data[2] == "SOCKS5"){
                $typeProxy = "socks5";
            }
            else if($data[2] == "SOCKS4A"){
                $typeProxy = "socks4a";
            }
            else{
                $typeProxy = 'http';
            }
            
            $fields = new Proxy();
            $fields->fill([
                'ip_port' => $data[0].":".$data[1],
                'type' => $typeProxy
            ]);
            $fields->save();
        }

        Alert::info('Ваши данные успешно загружены | Count: '.count($items));

        return redirect()->route('platform.proxy.list');
    }

    protected function get_data($file_url) {

        $file = Storage::get($file_url);

        $arr = explode("\r\n", $file);
        $all_data = [];

        foreach($arr as $str){
            $item = explode(";", $str);

            $all_data[] = $item;
        }

        return $all_data;
    }
}