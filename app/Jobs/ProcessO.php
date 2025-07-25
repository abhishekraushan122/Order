<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

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
        // Find the order by ID
        $order = Order::find($this->orderId);

        if (!$order) {
            \Log::warning("Order not found for ID: {$this->orderId}");
            return;
        }

        // Update the order status
        $order->status = 'processed';
        $order->save();

        \Log::info("Order {$this->orderId} processed successfully.");
    }
}
