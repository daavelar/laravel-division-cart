<?php namespace Daavelar\Cart\Session;


class CartItem
{

    public $identifier;
    public $title;
    public $price;
    public $options;

    public function __construct(array $item)
    {
        $this->identifier = $item['identifier'];
        $this->id = $item['content']['id'];
        $this->title = $item['content']['title'];
        $this->price = $item['content']['price'];
        $this->total = $item['content']['price']*$item['content']['quantity'];
        $this->quantity = $item['content']['quantity'];
        $this->options = $item['content']['options'];
    }

}