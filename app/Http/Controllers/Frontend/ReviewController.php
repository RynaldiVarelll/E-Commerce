<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\ProductReview;
use App\Models\StoreReview;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Menyimpan atau memperbarui ulasan produk.
     */
    public function storeProductReview(Request $request, Transaction $transaction)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        // Pastikan transaksi milik user dan status selesai
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'completed') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        // Simpan atau update ulasan (Sinkronisasi)
        $review = ProductReview::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'transaction_id' => $transaction->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        $this->syncProductRating($request->product_id);

        return back()->with('success', 'Terima kasih atas ulasan produknya!');
    }

    /**
     * Menyimpan ulasan toko secara langsung.
     */
    public function storeStoreReview(Request $request, Transaction $transaction)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'completed') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $sellerId = $transaction->seller_id;
        
        if (!$sellerId) {
            $firstItem = $transaction->items()->first();
            $sellerId = $firstItem ? $firstItem->product->user_id : null;
        }

        if ($sellerId) {
            StoreReview::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'seller_id' => $sellerId,
                    'transaction_id' => $transaction->id,
                ],
                [
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]
            );

            $this->syncStoreRating($sellerId);
        }

        return back()->with('success', 'Ulasan toko Anda telah kami terima!');
    }

    /**
     * Re-calculate physical rating into products table for consistent performance.
     */
    private function syncProductRating($productId)
    {
        $product = \App\Models\Product::find($productId);
        if ($product) {
            $avg = \App\Models\ProductReview::where('product_id', $productId)->avg('rating');
            $count = \App\Models\ProductReview::where('product_id', $productId)->count();
            
            $product->rating = $count > 0 ? round($avg, 1) : 0;
            $product->review_count = $count;
            $product->save();

            // Serta sinkronisasi rating toko pemilik produk tersebut
            $user = $this->syncStoreRating($product->user_id);

            event(new \App\Events\ReviewAdded([
                'type' => 'product',
                'product_id' => $product->id,
                'new_product_rating' => $product->rating,
                'new_product_review_count' => $product->review_count,
                'seller_id' => $user ? $user->id : null,
                'new_store_rating' => $user ? $user->store_rating : 0,
                'new_store_review_count' => $user ? $user->store_review_count : 0,
            ]));
        }
    }

    /**
     * Re-calculate physical rating into users table for consistent performance.
     */
    private function syncStoreRating($sellerId)
    {
        $user = \App\Models\User::find($sellerId);
        if ($user) {
            // Rata-rata dari produk-produknya
            $productIds = \App\Models\Product::where('user_id', $sellerId)->pluck('id');
            $productReviewSum = \App\Models\ProductReview::whereIn('product_id', $productIds)->sum('rating');
            $productReviewCount = \App\Models\ProductReview::whereIn('product_id', $productIds)->count();

            // Ulasan langsung dari transaksi ke toko
            $storeReviewSum = \App\Models\StoreReview::where('seller_id', $sellerId)->sum('rating');
            $storeReviewCount = \App\Models\StoreReview::where('seller_id', $sellerId)->count();

            $totalSum = $productReviewSum + $storeReviewSum;
            $totalCount = $productReviewCount + $storeReviewCount;

            $user->store_rating = $totalCount > 0 ? round($totalSum / $totalCount, 1) : 0;
            $user->store_review_count = $totalCount;
            $user->save();

            // Cek apakah function ini dipanggil secara langsung (bukan via product), maka fire event 'store'
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            if (isset($backtrace[1]['function']) && $backtrace[1]['function'] !== 'syncProductRating') {
                event(new \App\Events\ReviewAdded([
                    'type' => 'store',
                    'seller_id' => $user->id,
                    'new_store_rating' => $user->store_rating,
                    'new_store_review_count' => $user->store_review_count,
                ]));
            }
        }
        return $user;
    }
}
