@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Product Listing ({{ Auth::user()->name }})</h3>
            <a href="{{ route('sellers-products-create') }}" class="btn btn-primary btn-sm">Add Product</a>
        </div>

        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Brands Count</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td><span class="badge bg-info text-dark">{{ $product->brands->count() }} Brands</span></td>
                        <td>₹{{ number_format($product->brands->sum('price'), 2) }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('products.pdf', $product->id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> View PDF
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
<style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
    }

    .btn-sm,
    .btn-group-sm>.btn {
        height: 29px;
    }
</style>
