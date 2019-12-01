<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Ingredient\Ingredient;
use App\Ingredient\IngredientList;
use App\Recipe\Recipe;

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