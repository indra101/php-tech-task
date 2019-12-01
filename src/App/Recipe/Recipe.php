<?php

namespace App\Recipe;

use App\Ingredient\Ingredient;
use App\Ingredient\IngredientList;

class Recipe 
{
    private $title;
    private $ingredients;

    public function __construct($rec, $ingLIst) 
    {
        $this->title = $rec->title;
        $ingTitles = $rec->ingredients;
        $this->ingredients = IngredientList::from_titles($ingTitles, $ingLIst);
    }

    public function get_title() {
        return $this->title;
    }

    public function get_ingredients() {
        return $this->ingredients;
    }

    public function is_expired($date) {
        $res = false;
        $ingredients = $this->ingredients->get_list();
        
        foreach($ingredients as $ing) {
            if($ing->is_expired($date)) {
                $res = true;
            }

            $name = $ing->get_title();
            $use_by = $ing->get_use_by();

            $dt = date('Y-m-d',$date);
        }

        return $res;
    }

    public function getArray() {
        return array(   'title' => $this->title,
                        // 'ingredients' => $this->best_before,
                        // 'use_by' => $this->use_by,
                    );
    }

    public function get_last_best_before() {
        return $this->ingredients->get_last_best_before();
    }

}