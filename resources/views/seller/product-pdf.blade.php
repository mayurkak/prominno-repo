<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #333;
        }
        .header { text-align: center; margin-bottom: 30px; }
        .brand-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .brand-table th { background-color: #f2f2f2; border: 1px solid #ccc; padding: 10px; text-align: left; }
        .brand-table td { border: 1px solid #ccc; padding: 10px; }
        .total-box {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .brand-img { width: 60px; height: 60px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->description }}</p>
    </div>

    <h4>Associated Brands</h4>
    <table class="brand-table">
        <thead>
            <tr>
                <th>Brand Image</th>
                <th>Brand Name</th>
                <th>Detail</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->brands as $brand)
            <tr>
                <td>
                    @if($brand->image)
                        <img src="{{ public_path('storage/' . $brand->image) }}" class="brand-img">
                    @else
                        No Image
                    @endif
                </td>
                <td>{{ $brand->brand_name }}</td>
                <td>{{ $brand->detail }}</td>
                <td>₹{{ number_format($brand->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Product Price: ₹{{ number_format($totalPrice, 2) }}
    </div>
</body>
</html>
