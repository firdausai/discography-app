<?php

namespace App\Http\Controllers;

use App\Modules\Admin\AdminService;
use Illuminate\Http\Request;
use App\Http\Requests\login;
use App\Http\Requests\register;

class AuthController extends Controller
{
    /**
     * @var $albumService
     */
    protected $adminService;

    /**
     * AlbumRepository constructor
     * 
     * @param AlbumService $albumService
     */
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request)
    {
        if ($request->is('login')) {
            return view('pages.login');
        }

        return view('pages.register');
    }

    public function login(login $request)
    {
        // $payload = $request->validate();
        $payload = $request->all();

        try {
            $response = $this->adminService->login($payload);
        } catch (Throwable $e) {
            abort(404);
        }
        
        if ($response === true) {
            return redirect('/dashboard');
        } else {
            return back();
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

    public function createUser(login $request)
    {
        // $payload = $request->validate();
        $payload = $request->all();

        try {
            $response = $this->adminService->createUser($payload);
        } catch (Throwable $e) {
            abort(404);
        }

        return redirect('/dashboard');
    }
}
