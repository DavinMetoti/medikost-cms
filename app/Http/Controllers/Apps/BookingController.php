<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.content.bookings.index');
    }

    /**
     * Datatable for bookings.
     */
    public function datatable(Request $request)
    {
        $query = Booking::with(['user', 'productDetail.product']);

        // Handle search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQ) use ($search) {
                    $userQ->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('productDetail', function ($pdQ) use ($search) {
                    $pdQ->where('room_name', 'like', "%{$search}%");
                })
                ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Handle ordering
        if ($request->has('order')) {
            $orderColumn = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $columns = ['id', 'user.name', 'productDetail.product.name', 'productDetail.room_name', 'check_in_date', 'check_out_date', 'total_price', 'status'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $totalRecords = $query->count();
        $bookings = $query->skip($request->start)->take($request->length)->get();

        $data = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'user_name' => $booking->user->name ?? 'N/A',
                'kost_name' => $booking->productDetail->product->name ?? 'N/A',
                'room_name' => $booking->productDetail->room_name ?? 'N/A',
                'check_in_date' => $booking->check_in_date->format('d M Y'),
                'check_out_date' => $booking->check_out_date->format('d M Y'),
                'total_price' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                'status' => $booking->status,
                'created_at' => $booking->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        DB::transaction(function () use ($request) {
            $productDetail = ProductDetail::findOrFail($request->product_detail_id);

            // Check if room is available
            if ($productDetail->available_rooms <= 0 || $productDetail->status !== 'kosong') {
                throw new \Exception('Room is not available.');
            }

            // Total price is just the room price (monthly)
            $totalPrice = $productDetail->price;

            // Create booking
            Booking::create([
                'user_id' => auth()->id(),
                'product_detail_id' => $request->product_detail_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
            ]);

            // Decrease available rooms
            $productDetail->decrement('available_rooms');

            // If available rooms reach 0, change status to 'isi'
            if ($productDetail->available_rooms == 0) {
                $productDetail->update(['status' => 'isi']);
            }
        });

        return response()->json(['message' => 'Booking created successfully.']);
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
