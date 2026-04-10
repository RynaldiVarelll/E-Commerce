<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type; // 'product' atau 'store'
    public $productId;
    public $newProductRating;
    public $newProductReviewCount;
    public $sellerId;
    public $newStoreRating;
    public $newStoreReviewCount;

    /**
     * Create a new event instance.
     */
    public function __construct($data)
    {
        $this->type = $data['type'] ?? 'product';
        $this->productId = $data['product_id'] ?? null;
        $this->newProductRating = $data['new_product_rating'] ?? 0;
        $this->newProductReviewCount = $data['new_product_review_count'] ?? 0;
        $this->sellerId = $data['seller_id'] ?? null;
        $this->newStoreRating = $data['new_store_rating'] ?? 0;
        $this->newStoreReviewCount = $data['new_store_review_count'] ?? 0;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('reviews'),
        ];
    }

    public function broadcastAs()
    {
        return 'review.added';
    }
}
