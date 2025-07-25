<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessO implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $orderId)
    {
        // dd($orderId);
        $this->orderId = $orderId;
        // dd($this->orderId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Running job for user: ' . $this->orderId);
        $order = Order::find($this->orderId);
        \Log::info('Running job for user: ' . $order);
        if (!$order) {
            \Log::warning("Order not found: {$this->orderId}");
            return;
        }

        $order->status = 'processed';

        // Save changes
        $order->save();

        // Log success
        \Log::info("Order #{$this->orderId} processed successfully.");
    }
}
