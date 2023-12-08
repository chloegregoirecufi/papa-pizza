<?php

namespace App\Model;

use Core\Model\Model;

class Pizza_ingredient extends Model
{
    public int $pizza_id;
    public int $ingredient_id;
    public int $unit_id;
    public int $quantity;

    public Pizza $pizza;
    public Ingredient $ingredient;
    public Unit $unit;
}