<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;





class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $orderId;

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
    // Retrieve the order from the database
    $order = Order::find($this->orderId);

    \Log::info("Order #{$order} .");
    // dd($order);

    // If not found, log and stop
    if (!$order) {
        \Log::warning("Order not found: {$this->orderId}");
        return;
    }

    // Simulate processing time (e.g., payment, notification)
    // sleep(2); // Placeholder for real logic

    // Update order status
    $order->status = 'processed';

    // Save changes
    $order->save();

    // Log success
    \Log::info("Order #{$this->orderId} processed successfully.");
}
}
