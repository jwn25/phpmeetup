<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductPriceLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

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

        $is_price_changed = $product->price !== $request->price;
        $orgiginal_price = $product->price;
        $product->update($request->all());

        if ($is_price_changed) {
            ProductPriceLog::create([
                'user_id' =>1,
                'product_id' => $product->id, 
                'price_before' => $orgiginal_price,
                'current_price' => $request->price
            ]);
        }

        ProductLog::create([
            'user_id' =>1,
            'product_id' => $product->id
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Mail::send('emails.product_updated', ['product' => $product], function($mail) use ($admin) {
                $mail->from(
                    config('mail.from.address'),
                    config('mail.from.name')
                );

                $mail->to($admin->email, $admin->name)->subject('Product Update Notification');
            });
        }

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
}
