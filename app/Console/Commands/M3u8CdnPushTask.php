<?php

namespace App\Console\Commands;

use App\Models\CmfVideoModel;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class M3u8CdnPushTask extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'M3u8CdnPushTask:handle {--offset=} {--limit=} {--debug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $offset = $this->option('offset');
        $limit = $this->option('limit');
        $debug = $this->option('debug');

        $urls = '';
        $paths = CmfVideoModel::select(['href'])->orderBy('id', 'DESC')->offset($offset)->take($limit)->pluck('href');
        foreach ($paths as $path) {
            if (empty($path)) continue;
            $witchServer = substr($path, 0, 1);
            $serverUrl = config("params.m3u8.server.{$witchServer}");
            $videoArr = explode('/', $path);
            unset($videoArr[count($videoArr) - 1]);
            $basePath = implode('/', $videoArr);

            $m3u8Path = str_replace('.m3u8', '', "/data/video/m3u8.finish.{$path}");
            $videoArr = explode('/', $m3u8Path);
            unset($videoArr[count($videoArr) - 1]);
            $dir = implode('/', $videoArr);

            if (!is_dir($dir)) continue;

            $dirFiles = scandir($dir);
            $k1 = array_search('.', $dirFiles);
            $k2 = array_search('..', $dirFiles);
            if ($k1 !== false) unset($dirFiles[$k1]);
            if ($k2 !== false) unset($dirFiles[$k2]);
            foreach ($dirFiles as $dirFile) {
                $urls .= "{$serverUrl}/{$basePath}/{$dirFile}\r\n";
            }
        }
        if (!empty($urls) && Storage::disk('local')->put('m3u8_cdn_urls.txt', $urls)) {
            exit("文件已保存至：storage/app/m3u8_cdn_urls.txt\r\n");
        }
        echo "地址为空，请检查！\r\n";
    }


    public function async()
    {
        $client = new Client();
        $requests = function ($total) {
            $uri = 'http://127.0.0.1:8126/guzzle-server/perf';
            for ($i = 0; $i < $total; $i++) {
                yield new Request('GET', $uri, ['timeout' => '60',]);
            }
        };

        $pool = new Pool($client, $requests(100), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);

        $promise = $pool->promise();

        $promise->wait();
    }
}
