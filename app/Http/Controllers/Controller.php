<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $time;

    public function __construct()
    {
        $this->time = date('Y-m');
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests , ApiResponder;
}
