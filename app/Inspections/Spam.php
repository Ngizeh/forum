<?php


namespace App\Inspections;


class Spam
{
    public $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body)
    {
        foreach ($this->inspections as $instance){
            app($instance)->detect($body);
        }
        return false;
    }

}
