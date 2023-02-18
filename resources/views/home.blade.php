@extends('layouts.master')

@section('content')
    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">Add Product</a>

    <div class="row mt-3">
        @foreach ($products as $product)
            <div class="col-md-3">

                <div class="card">
                    <img src="{{ asset("storage/images/$product->image") }}" class="card-img-top p-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <span class="card-text">Rs {{ $product->price }}</span>
                        <span class="float-end"><a href="{{ route('products.edit', $product->id) }}"
                                class="btn btn-secondary btn-sm">Edit</a>
                                <a href="{{ route('home') }}" class="btn btn-danger btn-sm">Delete</a>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
