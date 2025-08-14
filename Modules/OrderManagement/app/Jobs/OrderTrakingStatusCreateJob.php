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
use Modules\OrderManagement\Emails\OrderTackingCreatedMail;
use Modules\OrderManagement\Models\OrderTracking;

class OrderTrakingStatusCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public OrderTracking  $orderTracking
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->orderTracking->order->customer->email)
                ->send(new OrderTackingCreatedMail($this->orderTracking));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
