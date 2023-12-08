<?php

namespace App\Model;

use Core\Model\Model;

class Order_row extends Model
{
    public int $quantity;
    public float $price;
    public int $order_id;
    public int $pizza_id;

    public Order $order;
    public Pizza $pizza;
}