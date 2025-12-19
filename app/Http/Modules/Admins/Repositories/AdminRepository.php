<?php

namespace App\Http\Modules\Admins\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Admins\Models\Admin;

class AdminRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Admin $model
     */
    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

}
