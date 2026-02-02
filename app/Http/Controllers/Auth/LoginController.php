<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\StoreRequest;
use App\Http\Contracts\Auth\LoginRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $loginRepository;

    /**
     * Create a new controller instance.
     *
     * @param LoginRepositoryInterface $loginRepository
     */
    public function __construct(LoginRepositoryInterface $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('auth.login.index');
    }

    /**
     * Handle the login request.
     *
     * @param \App\Http\Requests\Auth\Login\StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->loginRepository->login($credentials)) {
            return redirect()->route('app.dashboard.index');
        }

        return back()->withErrors([
            'login' => 'The provided credentials are incorrect.',
        ]);
    }

    public function logout()
    {
        if ($this->loginRepository->logout()) {
            return redirect()->route('login');
        }

        return redirect()->back()->withErrors(['logout' => 'Gagal logout. Silakan coba lagi.']);
    }

}
