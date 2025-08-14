<?php

namespace Modules\OrderManagement\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\OrderManagement\Emails\OrderCreatedMail;
use Modules\OrderManagement\Models\Order;

class OrderCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Order $order,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->order->customer->email)
                ->send(new OrderCreatedMail($this->order));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
