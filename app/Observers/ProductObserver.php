<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductPriceLog;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    public function updating() {
        $fileName = null;
        if (request()->hasFile('image')) {
            $image      = request()->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs("public/images", $image, $fileName);
            // $data['image'] = $fileName;
        }
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        if ($product->isDirty('price')) {
            ProductPriceLog::create([
                'user_id' =>1,
                'product_id' => $product->id, 
                'price_before' => $product->getOriginal('price'),
                'current_price' => $product->price
            ]);
        }

        ProductLog::create([
            'user_id' => 1,
            'product_id' => $product->id
        ]);
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
