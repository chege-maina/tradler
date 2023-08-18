<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CustomerCsvProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $header;
    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $header, $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->header = $header;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(10);
        
        foreach ($this->data as $value) {
            $customer = array_combine($this->header, $value);
            $customer['user_id'] = $this->user->id;
            Customer::create($customer);
        }
    }



    public function failed(Throwable $e): void
    {
        // Send user notification of failure, etc...
    }
}
