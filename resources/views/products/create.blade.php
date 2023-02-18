@extends('layouts.master')

@section('content')
    <form action="{{ (isset($product)) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        <div class="row justify-content-md-center">
            @csrf
            @if(isset($product))
                @method('PATCH')
            @endif
            <div class="col-8 p-4" style="border: 1px solid gray">
                <h3>{{ (isset($product)) ? "Update ": "Add " }} Product</h3>
                <div class="row">
                    <div class="col">
                        <label for="">Name</label>
                        <input type="text" name="title" class="form-control" placeholder="Product Name"
                            aria-label="First name" value="{{ $product->title ?? request()->old('title') }}">
                    </div>
                    <div class="col">
                        <label for="">SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="SKU" aria-label="Last name"
                            value="{{ $product->sku ?? request()->old('sku') }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <label for="">Short Description</label>
                        <input type="text" class="form-control" name="short_description" placeholder="Short Description"
                            value="{{ $product->short_description ?? request()->old('short_description') }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <div class="row">
                            <div class="col-12">
                                <label for="">Quantity</label>
                                <input type="number" name="quantity" id="" placeholder="Quantity"
                                    value="{{ $product->quantity ?? request()->old('quantity') }}" class="form-control">
                            </div>
                            <div class="col-12 mt-4">
                                <label for="">Price</label>
                                <input type="number" name="price" id="" placeholder="Price"
                                    value="{{ $product->price ?? request()->old('price') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <input type="file" name="image" class="d-none" id="product-image--chooser">
                        <div class="image--wrapper text-center p-1"
                            style="border: 1px solid gray; background: rgb(233, 233, 233); height:150px; width:250px">
                            @if (isset($product) && $product->image)
                            <img src="{{ asset("storage/images/{$product->image}") }}" class="image--preview" width="100%"
                            height="100%" alt="">
                            @else
                                <img src="/images/placeholder-image.png" class="image--preview" width="100%"
                                    height="100%" alt="">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <label for="">Description</label>
                        <textarea name="description" id="" cols="30" rows="5" class="form-control">
                            {{ $product->description ?? request()->old('description') }}
                        </textarea>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-success">{{ (isset($product))? "Update" : "Add" }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.image--wrapper', function() {
                $('#product-image--chooser').trigger('click');
            })

            $('#product-image--chooser').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('.image--preview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
