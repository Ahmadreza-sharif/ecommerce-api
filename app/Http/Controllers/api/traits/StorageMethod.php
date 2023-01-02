<?php

namespace App\Http\Controllers\api\traits;

use Illuminate\Support\Facades\Storage;

trait StorageMethod
{
    public function date()
    {
        return  date('Y-m');
    }
    public function putStorage($url,$file)
    {
        return Storage::put($this->date().$url,$file);
    }

    public function deleteStorage($url)
    {
        Storage::delete($url);
    }
}
