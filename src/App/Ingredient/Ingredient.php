<?php

namespace App\Ingredient;

use Symfony\Component\Config\Definition\Exception\Exception;

class Ingredient 
{
    private $title;
    private $best_before;
    private $use_by;

    public function __construct($ing) 
    {
        if(!isset($ing->title) || !isset($ing->{'best-before'}) || !isset($ing->{'use-by'})) {
            throw new Exception("Invalid JSON");
        }

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
