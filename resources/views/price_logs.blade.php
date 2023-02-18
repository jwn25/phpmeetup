@extends('layouts.master')

@section('content')
    {{-- <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">Add Product</a> --}}

    {{-- <div class="row mt-3">
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
    </div> --}}

    <h3>Price Change Log</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Previous Price</th>
                <th>Chaged To</th>
                <th>Changed By</th>
                <th>Changed At</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($price_logs as $price_log)
                <tr>
                    <td>
                        <img src="{{ asset("storage/images/{$price_log->product->image}") }}" width="40px" alt="">
                        {{ $price_log->product->title }}
                    </td>
                    <td>Rs. {{ $price_log->price_before }}</td>
                    <td>Rs. {{ $price_log->current_price }}</td>
                    <td>{{ $price_log->user->name }}</td>
                    <td>{{ $price_log->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
