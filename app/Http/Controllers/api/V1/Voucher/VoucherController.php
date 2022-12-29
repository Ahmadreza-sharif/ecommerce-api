<?php

namespace App\Http\Controllers\api\v1\Voucher;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Voucher\StoreVoucherRequest;
use App\Http\Requests\Api\V1\Voucher\syncVoucherProductRequest;
use App\Http\Requests\Api\V1\Voucher\UpdateVoucherRequest;
use App\Http\Requests\Api\V1\Voucher\ValidateVoucherRequest;
use App\Http\Requests\Api\V1\Voucher\VoucherRequest;
use App\Http\Resources\Voucher\VoucherCollection;
use App\Http\Resources\Voucher\VoucherResource;
use App\Models\brand;
use App\Models\category;
use App\Models\product;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public $user;

    public function __construct()
    {
        $this->user = auth('sanctum')->user();
    }

    public function store(StoreVoucherRequest $request)
    {
        $voucher = Voucher::create([
            "code" => $request->code,
            "start_at" => $request->start_at,
            "expire_at" => $request->expire_at,
            "uses" => $request->uses,
            "voucher_amount" => $request->voucher_amount,
            "voucher_type" => $request->voucher_type,
            "type" => $request->type,
        ]);
        $voucherResource = new VoucherResource($voucher);
        return $this->sendSuccess($voucherResource, __('general.voucher.add'));
    }

    public function update(UpdateVoucherRequest $request)
    {
        $voucher = Voucher::where('id', $request->input('id'))->first();

        $voucher->update([
            "code" => $request->code,
            "start_at" => $request->start_at,
            "expire_at" => $request->expire_at,
            "uses" => $request->uses,
            "max_uses" => $request->max_uses,
            "voucher_amount" => $request->voucher_amount,
            "voucher_type" => $request->voucher_type,
            "type" => $request->type,
        ]);

        $voucher->save();

        $VoucherResource = new VoucherResource($voucher);

        return $this->sendSuccess($VoucherResource, __('general.voucher.update'));
    }

    public function show(Request $request)
    {
        if (isset($request->id)) {

            $request->validate([
                'id' => 'required|integer|exists:vouchers,id'
            ]);

            $voucher = Voucher::where('id', $request->input('id'))->first();

            $ResourceVoucher = new VoucherResource($voucher);

            return $this->sendSuccess($ResourceVoucher, __("general.voucher.select"));

        } else {
            $voucher = Voucher::all();

            $CollectionVoucher = new VoucherCollection($voucher);

            return $this->sendSuccess($CollectionVoucher, __("general.voucher.select"));
        }

    }

    public function destroy(VoucherRequest $request)
    {
        $voucherId = $request->input('id');
        Voucher::where('id', $voucherId)->delete();
        return $this->sendSuccess('', __("general.voucher.delete"));
    }

    public function addVoucherTo(syncVoucherProductRequest $request)
    {
        $voucher = Voucher::find($request->voucher_id);

        if ($request->category_id) {
            $item = category::find($request->category_id);
        } elseif ($request->product_id) {
            $item = product::find($request->product_id);
        } elseif ($request->brand_id) {
            $item = brand::find($request->brand_id);
        } else {
            return $this->sendError('', __("general.voucher.assign-failed"));
        }

        $item->vouchers()->attach([$item->id]);
        return $this->sendSuccess($voucher->categories, __("general.voucher.assign"));
    }

}
