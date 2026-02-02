<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Contracts\Auth\RegisterRepositoryInterface;
use App\Http\Requests\Auth\Register\StoreRequest;
use App\Http\Requests\Auth\Register\UpdateRequest;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    protected $registerRepository;

    /**
     * Create a new controller instance.
     *
     * @param RegisterRepositoryInterface $registerRepository
     * @return void
     */
    public function __construct(RegisterRepositoryInterface $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }


    /**
     * Show the user list.
     */
    public function index()
    {
        return view('pages.content.register.index');
    }

    /**
     * Handle user registration request.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = $this->registerRepository->register($validated);

            return response()->json([
                'message' => 'User successfully registered!',
                'user' => $user,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User registration failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle user table.
     */
    public function create() {
        $roles = Role::all();
        return view('pages.content.register.create',
            ['roles' => $roles]
        );
    }

    /**
     * Handle user table.
     */
    public function datatable(Request $request) {
        return $this->registerRepository->datatable($request);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = $this->registerRepository->find($id);
        $roles = Role::all();

        return view('pages.content.register.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $user = $this->registerRepository->update($id, $validated);

            return response()->json([
                'message' => 'User successfully updated!',
                'user' => $user,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User update failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            $this->registerRepository->delete($id);

            return response()->json([
                'message' => 'User successfully deleted!',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User deletion failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
