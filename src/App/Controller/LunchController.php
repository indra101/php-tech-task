<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LunchController extends AbstractController
{
    /**
     * @Route("/lunch", name="lunch")
     */
    public function index()
    {
        $ingJson = file_get_contents(__DIR__ . '/../Ingredient/data.json');
        $ingredients = IngredientList::create(json_decode($ingJson)->ingredients);
        
        $recJson = file_get_contents(__DIR__ . '/../Recipe/data.json');
        $recipes = json_decode($recJson)->recipes;

        $res = array();

        foreach($recipes as $rec) {

            $today = strtotime('2019-03-08');
            $recipe = new Recipe($rec, $ingredients);

            if(!$recipe->is_expired($today)) {
                $res[] = array( 'title' => $recipe->get_title(),
                                'best_before' => $recipe->get_ingredients()->get_last_best_before()
                                );
            }
        }

        usort($res, function($a, $b){
            if ($a['best_before'] == $b['best_before']) {
                return 0;
            }
            return ($a['best_before'] < $b['best_before']) ? 1 : -1;
        });

        return $this->json(array('data' => $res));
    }
}

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

class Ingredient 
{
    private $title;
    private $best_before;
    private $use_by;

    public function __construct($ing) 
    {
        $this->title = $ing->title;
        $this->best_before = $ing->{'best-before'};
        $this->use_by = $ing->{'use-by'};
    }

    public function set_title($title) {
        $this->title = $title;
    }

    public function get_title() {
        return $this->title;
    }

    public function set_best_before($best_before) {
        $this->best_before = $best_before;
    }

    public function get_best_before() {
        return $this->best_before;
    }

    public function set_use_by($use_by) {
        $this->use_by = $use_by;
    }

    public function get_use_by() {
        return $this->use_by;
    }

    public function is_expired($date) {
        $use_by = date('Y-m-d',strtotime($this->use_by));
        $dt = date('Y-m-d',$date);

        if($use_by <= $dt) {
            return true;
        } else {
            return false;
        }
    }

    public function getArray() {
        return array(   'title' => $this->title,
                        'best_before' => $this->best_before,
                        'use_by' => $this->use_by,
                    );
    }

}
