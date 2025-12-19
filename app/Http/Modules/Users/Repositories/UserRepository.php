<?php

namespace App\Http\Modules\Users\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Users\Models\User;

class UserRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

}
