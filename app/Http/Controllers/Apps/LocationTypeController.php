<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Contracts\Apps\LocationTypeRepositoryInteface;
use App\Http\Requests\Apps\LocationType\StoreRequest;
use App\Http\Requests\Apps\LocationType\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LocationTypeController extends Controller
{
    protected LocationTypeRepositoryInteface $locationTypeRepository;

    public function __construct(LocationTypeRepositoryInteface $locationTypeRepository)
    {
        $this->locationTypeRepository = $locationTypeRepository;
    }

    /**
     * Display Location Type page
     */
    public function index()
    {
        return view('pages.content.location-types.index');
    }

    /**
     * Show create page
     */
    public function create()
    {
        return view('pages.content.location-types.create');
    }

    /**
     * Store Location Type (JSON)
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $locationType = $this->locationTypeRepository->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Location Type berhasil dibuat',
                'data'    => $locationType,
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Location Type',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show Location Type detail (JSON)
     */
    public function show(string $id)
    {
        return view('pages.content.location-types.show', compact('id'));
    }

    /**
     * Show edit page
     */
    public function edit(string $id)
    {
        try {
            $locationType = $this->locationTypeRepository->findById($id)
                ?? abort(404, 'Location Type tidak ditemukan');
            return view('pages.content.location-types.edit', compact('locationType'));
        } catch (\Throwable $th) {
            abort(500, 'Gagal mengambil data Location Type');
        }
    }

    /**
     * Update Location Type (JSON)
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $locationType = $this->locationTypeRepository->update(
                (int) $id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Location Type berhasil diperbarui',
                'data'    => $locationType,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Location Type',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete Location Type (JSON)
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->locationTypeRepository->delete((int) $id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location Type tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Location Type berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Location Type',
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
            return $this->locationTypeRepository->datatable();
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Location Type',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Select location types for dropdown
     */
    public function select(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $id = $request->get('id');

            if ($id) {
                // Fetch single location type by ID
                $locationType = $this->locationTypeRepository->findById($id);
                if ($locationType) {
                    $results = collect([
                        [
                            'id' => $locationType->id,
                            'text' => $locationType->code . ' - ' . $locationType->name,
                        ]
                    ]);
                } else {
                    $results = collect();
                }
            } else {
                $locationTypes = $this->locationTypeRepository->allActive();

                if ($search) {
                    $locationTypes = $locationTypes->filter(function ($locationType) use ($search) {
                        return stripos($locationType->name, $search) !== false ||
                               stripos($locationType->code, $search) !== false;
                    });
                }

                $results = $locationTypes->map(function ($locationType) {
                    return [
                        'id' => $locationType->id,
                        'text' => $locationType->code . ' - ' . $locationType->name,
                    ];
                });
            }

            return response()->json([
                'results' => $results
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Location Type',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
