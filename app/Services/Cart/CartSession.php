<?php

namespace App\Services\Cart;

use App\Services\Cart\Contract\CartInterface;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartSession implements CartInterface
{
    const MINIMUM_QUANTITY = 1;
    const DEFAULT_INSTANCE = 'cart';

    protected $session;

    public function __construct(
        SessionManager $sessionManager
    )
    {
        $this->session = $sessionManager;
    }

    public function addItem($product_id, $product_count)
    {
        # VALIDATE COUNT AND COLLECT DATA
        $cartItem = $this->createCartItem($product_id, $product_count);

        # CHECK CART EXISTS OR NOT IF NOT EXISTS CREATE NEW ONE
        $content = $this->getContent();

        # SAVE DATA INTO OUR SESSION OR COLLECTION
        $content->put($product_id, $cartItem);

        # SAVE INTO SESSION
        $this->session->put(self::DEFAULT_INSTANCE, $content);

        # RETURN SESSION DATA FOR RESPONSE
        return ($this->session->get(self::DEFAULT_INSTANCE, $product_id)->get($product_id));
    }


    public function updateCount($product_id, $product_count)
    {
        # CHECK CART EXISTS OR NOT IF NOT EXISTS CREATE NEW ONE
        $content = $this->getContent();

        # CHECK WE HAVE THIS PRODUCT IN CART OR NOT
        if ($content->has($product_id)) {
            # GET PRODUCT FROM CART
            $cartItem = $content->get($product_id);

            # CHECK COUNT NOT BE 0
            if ($product_count < self::MINIMUM_QUANTITY) {
                $product_count = self::MINIMUM_QUANTITY;
            }

            # UPDATE ITEM COUNT
            $cartItem->put('product_count', $product_count);

            # UPDATE ITEM
            $content->put($product_id, $cartItem);

            # UPDATE CART
            $this->session->put(self::DEFAULT_INSTANCE, $content);

            # RETURN SESSION DATA FOR RESPONSE
            return $this->session->get(self::DEFAULT_INSTANCE, $product_id);
        }
    }

    public function deleteItem($product_id)
    {
        $content = $this->getContent();

        if ($content->has($product_id)) {
            $this->session->put(self::DEFAULT_INSTANCE, $content->except($product_id));
        }
    }

    public function clear()
    {
        $this->session->forget(self::DEFAULT_INSTANCE);
    }

    public function all()
    {
        return $this->session->get(self::DEFAULT_INSTANCE);
    }

    public function get($product_id)
    {
        return $this->session->get(self::DEFAULT_INSTANCE,$product_id)->get($product_id);
    }

    public function userId()
    {
        return auth('sanctum')->user()->id;
    }

    protected function createCartItem($product_id, $product_count): Collection
    {
        if ($product_count < self::MINIMUM_QUANTITY) {
            $product_count = self::MINIMUM_QUANTITY;
        }

        return collect([
            'product_id' => $product_id,
            'user_id' => $this->userId(),
            'product_count' => $product_count
        ]);
    }

    protected function getContent(): Collection
    {
        return $this->session->has(self::DEFAULT_INSTANCE) ? $this->session->get(self::DEFAULT_INSTANCE) : collect([]);
    }

}
