<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function createProduct()
    {
        return view('seller.add-product');
    }
    // private function saveProduct($request, $id = null)
    // {
    //     try {
    //         // dd($request->all(), $id);
    //         return DB::transaction(function () use ($request, $id) {
    //             $product = Product::updateOrCreate(
    //                 ['id' => $id],
    //                 [
    //                     'user_id'   => Auth::id(),
    //                     'name'        => $request->product_name,
    //                     'description' => $request->product_description,
    //                 ]
    //             );

    //             if ($id) {
    //                 $product->brands()->delete();
    //             }

    //             foreach ($request->brands as $index => $brandData) {
    //                 $imagePath = null;
    //                 if (isset($request->file('brands')[$index]['image'])) {
    //                     $imagePath = $request->file('brands')[$index]['image']->store('brands', 'public');
    //                 }

    //                 $product->brands()->create([
    //                     'brand_name' => $brandData['name'],
    //                     'detail'     => $brandData['detail'],
    //                     'price'      => $brandData['price'],
    //                     'image'      => $imagePath,
    //                 ]);
    //             }
    //             return $product->load('brands');
    //         });
    //     } catch (\Throwable $th) {
    //         dd($th);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_name' => 'required|string',
    //         'brands'       => 'required|array',
    //         'brands.*.name'  => 'required',
    //         'brands.*.price' => 'required|numeric',
    //     ]);

    //     try {
    //         $product = $this->saveProduct($request);

    //         if ($request->expectsJson()) {
    //             return response()->json(['status' => true, 'data' => $product], 201);
    //         }
    //         return redirect()->back()->with('success', 'Product added successfully!');
    //     } catch (\Exception $e) {
    //         dd($e);
    //         return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
    //     }
    // }

    private function saveProduct($request, $id = null)
    {
        // create or find product
        $product = $id
            ? Product::findOrFail($id)
            : new Product();

        $product->user_id = Auth::id();
        $product->name = $request->product_name;
        $product->description = $request->product_description ?? null;
        $product->save();

        // delete old brands on update
        if ($id) {
            $product->brands()->delete();
        }

        // create brands
        foreach ($request->brands as $index => $brand) {

            $imagePath = null;

            if (isset($request->file('brands')[$index]['image'])) {
                $imagePath = $request->file('brands')[$index]['image']->store('brands', 'public');
            }

            $product->brands()->create([
                'brand_name' => $brand['name'],
                'detail'     => $brand['detail'] ?? null,
                'price'      => $brand['price'],
                'image'      => $imagePath
            ]);
        }

        return $product->load('brands');
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'brands'       => 'required|array|min:1',
            'brands.*.name'  => 'required|string',
            'brands.*.price' => 'required|numeric|min:0',
        ]);

        try {

            $product = $this->saveProduct($request);

            // If AJAX request → return JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Product created successfully',
                    'data' => $product
                ], 201);
            }

            // If normal HTTP request → redirect
            return redirect()
                ->route('products.index')
                ->with('success', 'Product created successfully');
        } catch (\Throwable $e) {

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Something went wrong')
                ->withInput();
        }
    }




    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Product::with('brands');

            if ($user->role !== 'admin') {
                $query->where('user_id', $user->id);
            }

            $products = $query->latest()->paginate(5);

            if ($request->expectsJson()) {
                return response()->json(['status' => true, 'data' => $products]);
            }
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => true,
                    'message' => 'Product list retrieved successfully',
                    'data' => $products
                ], 200);
            }
            return view('seller.product-list', compact('products'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function downloadPDF($id)
    {
        $product = Product::with('brands')->findOrFail($id);

        if (Auth::user()->role !== 'admin' && $product->user_id !== Auth::id()) {
            abort(403);
        }

        $totalPrice = $product->brands->sum('price');

        $pdf = Pdf::loadView('seller.product-pdf', compact('product', 'totalPrice'));

        return $pdf->download("product-{$product->id}.pdf");
    }
    public function destroy(Request $request, $id)
    {
        try {
            // dd(Auth::user());
            // dd($id);
            $product = Product::findOrFail($id);

            if (Auth::user()->role !== 'admin' && $product->user_id !== Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. You cannot delete this product.'
                ], 403); // 403 Forbidden
            }
            $product->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Product deleted successfully'
                ], 200); // 200 OK
            }
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => true,
                    'message' => 'Product deleted successfully',
                    'deleted_id' => $id
                ], 200);
            }
            return redirect()->back()->with('success', 'Product deleted successfully.');
        } catch (ModelNotFoundException $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ], 404); // 404 Not Found
        }
    }
}
