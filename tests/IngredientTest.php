<?php

use PHPUnit\Framework\TestCase;
use App\Ingredient\Ingredient;

final class IngredientTest extends TestCase
{
    public function testCanBeCreatedFromValidJson(): void
    {
        $arr = array("ingredients" => array("title" => "Ham",
                                            "best-before" => "2019-03-25",
                                            "use-by" => "2019-03-27")
                    );
        $json = json_encode($arr);

        $input = json_decode($json)->ingredients;

        $this->assertInstanceOf(
            Ingredient::class,
            new Ingredient($input)
        );
    }

    public function testCannotBeCreatedFromInvalidJson(): void
    {
        $this->expectException(Exception::class);

        new Ingredient('invalid');
    }

}