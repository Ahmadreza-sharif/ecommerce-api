<?php

namespace App\Services\Cart\Contract;

interface CartInterface
{
    public function addItem($product_id,$product_count,$product_price);

    public function updateCount($product_id,$product_count);

    public function deleteItem($product_id);

    public function clear();

    public function all();

    public function get($product_id);

    public function userId();
}
