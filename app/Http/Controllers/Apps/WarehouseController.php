<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Contracts\Apps\WarehouseRepositoryInterface;
use App\Http\Requests\Apps\Warehouse\StoreRequest;
use App\Http\Requests\Apps\Warehouse\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WarehouseController extends Controller
{
    protected WarehouseRepositoryInterface $warehouseRepository;

    public function __construct(WarehouseRepositoryInterface $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * Display warehouse page
     */
    public function index()
    {
        return view('pages.content.warehouse.index');
    }

    /**
     * Show create page
     */
    public function create()
    {
        return view('pages.content.warehouse.create');
    }

    /**
     * Store warehouse (JSON)
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $warehouse = $this->warehouseRepository->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Warehouse berhasil dibuat',
                'data'    => $warehouse,
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat warehouse',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show warehouse detail
     */
    public function show(string $id)
    {
        try {
            $warehouse = $this->warehouseRepository->findById((int) $id);

            if (! $warehouse) {
                abort(404, 'Warehouse tidak ditemukan');
            }

            return view('pages.content.warehouse.show', compact('warehouse'));

        } catch (Throwable $e) {
            abort(500, 'Gagal mengambil data warehouse');
        }
    }

    /**
     * Show edit page
     */
    public function edit(string $id)
    {
        $warehouse = $this->warehouseRepository->findById((int) $id);
        return view('pages.content.warehouse.edit', compact('warehouse'));
    }

    /**
     * Update warehouse (JSON)
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $warehouse = $this->warehouseRepository->update(
                (int) $id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Warehouse berhasil diperbarui',
                'data'    => $warehouse,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui warehouse',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete warehouse (JSON)
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->warehouseRepository->delete((int) $id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Warehouse tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Warehouse berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus warehouse',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function datatable()
    {
        try {
            return $this->warehouseRepository->datatable();
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data warehouse',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Select2 data for warehouses.
     */
    public function select(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $warehouses = $this->warehouseRepository->allActive();

            if ($search) {
                $warehouses = $warehouses->filter(function ($warehouse) use ($search) {
                    return stripos($warehouse->name, $search) !== false ||
                           stripos($warehouse->code, $search) !== false;
                });
            }

            $results = $warehouses->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'text' => $warehouse->code . ' - ' . $warehouse->name,
                ];
            });

            return response()->json([
                'results' => $results
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data warehouse',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
