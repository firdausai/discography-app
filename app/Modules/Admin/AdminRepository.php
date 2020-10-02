<?php

namespace App\Modules\Admin;

use App\User;

class AdminRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * SubscriptionRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser($params)
    {
        return $this->user
        ->where('name', $params['username'])
        ->first();
    }

    public function storeUser($params)
    {
        $user           = $this->user;
        $user->name     = $params['username'];
        $user->password = $params['password'];
        $user->salt     = $params['salt'];
        $user->save();

        return $user;
    }
    
}