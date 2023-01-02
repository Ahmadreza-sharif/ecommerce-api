<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\api\traits\StorageMethod;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests , ApiResponder , StorageMethod;
}
