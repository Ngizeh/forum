<?php


namespace App\Inspections;


class Spam
{
    public array $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    /**
     * @param $body
     * @return bool
     */
    public function detect($body): bool
    {
        foreach ($this->inspections as $instance){
            app($instance)->detect($body);
        }
        return false;
    }

}
