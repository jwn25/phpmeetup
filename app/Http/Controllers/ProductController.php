<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Jobs\ProductUpdateJob;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductPriceLog;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\ProductUpdateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'sku' => 'required',
            'short_description' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => "required"
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $fileName = null;
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs("public/images", $image, $fileName);
        }
        Product::create([
            'title' => $request->title, 
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $fileName
        ]);

        return redirect()->route('home')->with('success', "Product successfully added");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.create', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product, ProductUpdateRequest $request)
    {
        $product->update($request->all());
        dispatch(new ProductUpdateJob($product));
        return redirect()->route('home')->with('success', "Product successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //Example of early returns
    public function calculateVendorAnnualCharge($vendor_id) {
        $vendor = Vendor::find($vendor_id);

        if ($vendor) {
            if ($vendor->should_pay) {
                $vendor_orders = Order::where('vendor_id', $vendor_id)
                    ->whereBetween('order_date', [
                        now()->subYear()->startOfYear(),
                        now()->subYear()->endOfYear()
                    ]);
                
                $order_total = 0;
                foreach ($vendor_orders as $vendor_order) {
                    $order_total += $vendor_order->amount;
                }

                if ($order_total > 100000) {
                    $vendor_charge = $order_total * 0.15; //Should Pay 15% of total
                } else {
                    $vendor_charge = $order_total * 0.10; //Should pay 10% of total
                }
            } else {
                $vendor_charge = 0;
            }
        } else {
            $vendor_charge = 0;
        }

        return $vendor_charge;
    }
}
