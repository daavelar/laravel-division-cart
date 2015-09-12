<?php namespace NovoTempo\Cart\Session;

use Session;

class CartDivision
{
    private $division;

    public function __construct($division)
    {
        $this->division = $division;
    }

    public function add($new_item)
    {
        $identifier = uniqid();

        if(Session::has("cart.{$this->division}")) {
            foreach(Session::get("cart.{$this->division}") as $key => $item_in_cart) {
                if($new_item['id'] == $item_in_cart['content']['id']) {
                    Session::put("cart.{$this->division}.$key.content.quantity", $item_in_cart['content']['quantity'] + 1);

                    return $item_in_cart['identifier'];
                }
            }
        }

        if(!array_key_exists('quantity', $new_item)) {
            $new_item['quantity'] = 1;
        }

        Session::push("cart.{$this->division}", [
            'identifier' => $identifier,
            'content'    => $new_item
        ]);

        return $identifier;
    }

    public function count()
    {
        return count(Session::get("cart.{$this->division}"));
    }

    public function total()
    {
        $total = 0;

        if(!Session::has("cart.{$this->division}")) {
            return 0;
        }

        foreach($this->content() as $item) {
            $total += $item->total;
        }

        return $total;
    }

    public function content()
    {
        $content = [];

        if(Session::has("cart.{$this->division}")) {
            $content = $this->makeCartItems($content);
        }

        return $content;
    }

    public function isEmpty()
    {
        return !$this->count();
    }


    public function remove($identifier)
    {
        foreach(Session::get("cart.{$this->division}") as $key => $item) {
            if($item['identifier'] == $identifier) {
                Session::forget("cart.{$this->division}.$key");
            }
        }
    }

    public function removeUnit($identifier)
    {
        foreach(Session::get("cart.{$this->division}") as $key => $item) {
            if($item['identifier'] == $identifier) {
                $item_quantity = Session::get("cart.{$this->division}.$key.content.quantity");

                if($item_quantity == 1) {
                    $this->remove($identifier);
                }
                else {
                    Session::put("cart.{$this->division}.$key.content.quantity", $item_quantity-1);
                }

            }
        }
    }

    public function destroy()
    {
        Session::forget("cart.{$this->division}");
    }

    /**
     * @param $content
     * @return array
     */
    private function makeCartItems($content)
    {
        foreach(Session::get("cart.{$this->division}") as $cart_item) {
            $content[] = new CartItem($cart_item);
        }

        return $content;
    }

}
