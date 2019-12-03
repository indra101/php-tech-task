<?php

use PHPUnit\Framework\TestCase;
use App\Recipe\Recipe;
use App\Ingredient\IngredientList;

final class RecipeTest extends TestCase
{
    public function testCanBeCreatedFromValidJson(): void
    {
        $arr = array("title" => "Ham and Cheese Toastie",
                                        "ingredients" => array( "Ham",
                                                                "Cheese",
                                                                "Bread",
                                                                "Butter")
               
                    );

        $json = json_encode($arr);

        $input = json_decode($json);

        $ing = array("ingredients" => array(array("title" => "Ham",
                                            "best-before" => "2019-03-25",
                                            "use-by" => "2019-03-27")
                                            )
                    );
        $jsonIng = json_encode($ing);

        echo ($jsonIng);

        $inputIng = IngredientList::create(json_decode($jsonIng)->ingredients);

        $this->assertInstanceOf(
            Recipe::class,
            new Recipe($input, $inputIng)
        );
    }

    public function testCannotBeCreatedFromInvalidJson(): void
    {
        $this->expectException(Exception::class);
        $this->expectException(Error::class);

        new Recipe('invalid', 'invalid');
    }

}