<?php

namespace App\Http\Controllers\Apps;

use App\Http\Contracts\Apps\UoMRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\UoM\StoreRequest;
use App\Http\Requests\Apps\UoM\UpdateRequest;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UoMController extends Controller
{
    protected $UoMRepo;

    /**
     * Create a new controller instance.
     *
     * @param UoMRepositoryInterface $UoMRepository
     * @return void
     */
    public function __construct(UoMRepositoryInterface $UoMRepository)
    {
        $this->UoMRepo = $UoMRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.content.uom.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.content.uom.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $uom = $this->UoMRepo->find($id);
        return view('pages.content.uom.edit', compact('uom'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $uom = $this->UoMRepo->find($id);
        return view('pages.content.uom.show', compact('uom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $this->UoMRepo->store($data);

            return response()->json([
                'message' => 'Unit of Measurement created successfully.',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unit of Measurement creation failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $this->UoMRepo->update($id, $data);
            return response()->json([
                'message' => 'Unit of Measurement updated successfully.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unit of Measurement update failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };
    }

    /**
     * Get datatable data.
     */
    public function datatable(Request $request)
    {
        return $this->UoMRepo->datatable($request);
    }

    /**
     * Select2 data for UoM.
     */
    public function select(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $uoms = $this->UoMRepo->getLimitedWithSearch($search);

            $results = collect($uoms)->map(function (UnitOfMeasurement $uom) {
                return [
                    'id' => $uom->id,
                    'text' => $uom->name . ' (' . $uom->abbreviation . ')',
                ];
            });

            return response()->json([
                'results' => $results
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
