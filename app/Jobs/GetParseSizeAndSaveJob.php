<?php

namespace App\Jobs;

use App\Models\Size;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetParseSizeAndSaveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected array $data;

    public function __construct(array $data)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new Size())->create($this->data);
    }
}
