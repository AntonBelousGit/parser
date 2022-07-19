<?php

namespace App\Jobs;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParseToFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ConnectToParseServiceContract $parseServiceContract)
    {
        $config = config('parsers');
        foreach ($config as $key => $parser) {
            try {
                $method = $parser['method'];
                $html = $parseServiceContract->$method($parser['url']);
                $file = '/file/' . $key . '.txt';
                if (File::exists(public_path($file))) {
                    $tempFile = '/file/temp/' . $key . '.txt';
                    File::put(public_path() . $tempFile, $html);
                    if (filesize(public_path() . $tempFile) !== filesize(public_path() . $file)) {
                        File::move(public_path($tempFile), public_path($file));
                        $config[$key]['status'] = false;
                        Log::info($key . ' change structure!!!');
                    } else {
                        File::delete(public_path($tempFile));
                    }
                } else {
                    File::put(public_path() . '/file/' . $key . '.txt', $html);
                }
            } catch (Throwable $exception) {
                Log::info('parseToFile - problem ' . $exception);
            }
        }
        $config = var_export($config, true);
        File::put(config_path('/parsers.php'), "<?php\n declare(strict_types=1);\n return $config ;");
    }
}
