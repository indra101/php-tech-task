<?php

namespace App\Ingredient;

use App\Ingredient\Ingredient;

class IngredientList
{
    private $list = array();

    public function create($ingList) {
        $instance = new self();
        
        foreach($ingList as $i) {
            $obj = new Ingredient($i);
            $instance->list[] = $obj;
        }

        return $instance;
    }

    public static function from_titles($titles, IngredientList $ingList) {
        $instance = new self();

        $list = $ingList->list;
        foreach($titles as $t) {
            foreach($list as $ing) {
                if($ing->get_title() == $t) {
                    $instance->list[] = $ing;
                }
            }
        }
        return $instance;
    }

    public function get_list() {
        return $this->list;
    }

    public function get_ingredient_by_title($title) {
        foreach($this->$list as $ingredient) {
            if($ingredient->title == $title) {
                return $ingredient;
            }
        }
    }

    public function get_last_best_before() {
        $last = null;
        foreach($this->list as $ing) {
            $bb = date('Y-m-d',strtotime($ing->get_best_before()));
            if(empty($last) || $bb < $last) {
                $last = $bb;
            }
        }

        return $last;
    }

}