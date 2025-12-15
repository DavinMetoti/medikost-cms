<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Contracts\Apps\LocationRepositoryInteface;
use App\Http\Requests\Apps\Location\StoreRequest;
use App\Http\Requests\Apps\Location\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LocationController extends Controller
{
    protected LocationRepositoryInteface $locationRepository;

    public function __construct(LocationRepositoryInteface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Display locations page
     */
    public function index()
    {
        return view('pages.content.locations.index');
    }

    /**
     * Show create page
     */
    public function create()
    {
        return view('pages.content.locations.create');
    }

    /**
     * Store location (JSON)
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $location = $this->locationRepository->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Location berhasil dibuat',
                'data'    => $location,
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Location',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display location detail (PAGE)
     */
    public function show(string $id)
    {
        try {
            $location = $this->locationRepository->findById((int) $id);

            if (! $location) {
                abort(404, 'Location tidak ditemukan');
            }

            return view('pages.content.locations.show', compact('location'));

        } catch (Throwable $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Show edit page
     */
    public function edit(string $id)
    {
        try {
            $location = $this->locationRepository->findById((int) $id);

            if (! $location) {
                abort(404, 'Location tidak ditemukan');
            }

            return view('pages.content.locations.edit', compact('location'));

        } catch (Throwable $e) {
            abort(500, 'Gagal mengambil data Location');
        }
    }

    /**
     * Update location (JSON)
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $location = $this->locationRepository->update(
                (int) $id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Location berhasil diperbarui',
                'data'    => $location,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Location',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove location (JSON)
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->locationRepository->delete((int) $id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Location berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Location',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Datatable
     */
    public function datatable()
    {
        try {
            return $this->locationRepository->datatable();
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Location',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Select locations for dropdown
     */
    public function select(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $warehouseId = $request->get('warehouse_id');
            $id = $request->get('id');

            if ($id) {
                // Fetch single location by ID
                $location = $this->locationRepository->findById($id);
                if ($location) {
                    $results = collect([
                        [
                            'id' => $location->id,
                            'text' => $location->code . ' - ' . $location->name,
                            'location_display' => $location->code . ' - ' . $location->name,
                        ]
                    ]);
                } else {
                    $results = collect();
                }
            } else {
                $locations = $this->locationRepository->allActive();

                if ($warehouseId) {
                    $locations = $locations->where('warehouse_id', $warehouseId);
                }

                if ($search) {
                    $locations = $locations->filter(function ($location) use ($search) {
                        return stripos($location->name, $search) !== false ||
                               stripos($location->code, $search) !== false;
                    });
                }

                $results = $locations->map(function ($location) {
                    return [
                        'id' => $location->id,
                        'text' => $location->code . ' - ' . $location->name,
                        'location_display' => $location->code . ' - ' . $location->name,
                    ];
                });
            }

            return response()->json([
                'results' => $results
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Location',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
