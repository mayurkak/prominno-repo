<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSellerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller
{
    public function createSeller(CreateSellerRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'mobile_no' => $request->mobile_no,
                'country'   => $request->country,
                'state'     => $request->state,
                'skills'    => $request->skills,
                'password'  => Hash::make($request->password),
                'role'      => 'seller',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Seller created successfully!',
                'data'    => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to create seller.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function index(Request $request)
    {
        $perPage = min($request->query('per_page', 5), 50);
        $sellers = User::where('role', 'seller')
            ->latest()
            ->paginate($perPage);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Sellers list retrieved',
                'data' => $sellers
            ], 200);
        }
        return view('admin.sellers-list', compact('sellers'));
    }
    public function create()
    {
        return view('admin.create-seller');
    }

    // public function store(CreateSellerRequest $request)
    // {
    //     dd($request->all());
    //     $data = $request->validated();
    //     $data['password'] = Hash::make($data['password']);
    //     $data['role'] = 'seller';

    //     User::create($data);

    //     return redirect()->route('admin.sellers.index')->with('success', 'Seller created successfully!');
    // }
    public function store(CreateSellerRequest $request)
    {
        try {
            // dd($request->all());
            $data = $request->validated();

            $data['role'] = 'seller';

            // Hash password
            $data['password'] = Hash::make($data['password']);

            $seller = User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Seller created successfully',
                'data' => $seller
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
