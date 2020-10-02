<?php

namespace App\Modules\Admin;

use App\Modules\Admin\AdminRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;

class AdminService
{
    /**
     * @var $albumRepository
     */
    protected $adminRepository;

    /**
     * AlbumRepository constructor
     * 
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login($payload)
    {
        $params = [
            'username'  => $payload['username'],
        ];

        $user = $this->adminRepository->getUser($params);

        if (Hash::check($payload['password'].$user['salt'], $user['password'])) {
            session(['authorize' => true]);
            return true;
        }

        return false;    
    }

    public function createUser($payload)
    {
        $salt = $random = Str::random(32);

        $params = [
            'username'  => $payload['username'],
            'password'  => Hash::make($payload['password'].$salt),
            'salt'      => $salt
        ];

        $this->adminRepository->storeUser($params);
    }
}