<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Statistik Kost
        $totalProducts = Product::count();
        $publishedProducts = Product::where('is_published', true)->count();
        $draftProducts = Product::where('is_published', false)->count();

        // Distribusi Category
        $categoryStats = Product::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Statistik Kamar
        $totalRooms = ProductDetail::count();
        $availableRooms = ProductDetail::where('status', 'kosong')->count();
        $occupiedRooms = ProductDetail::where('status', 'terisi')->count();

        // Recent Products
        $recentProducts = Product::latest()->take(5)->get();

        // Recent Rooms
        $recentRooms = ProductDetail::with('product')->latest()->take(5)->get();

        return view('pages.content.dashboard.index', compact(
            'totalProducts',
            'publishedProducts',
            'draftProducts',
            'categoryStats',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'recentProducts',
            'recentRooms'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
