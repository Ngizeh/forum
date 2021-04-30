<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    public array $invalidKeywords = [
        'Google Customer Support'
    ];


    /**
     * @param $body
     * @throws Exception
     */
   public  function detect($body)
   {
       foreach ($this->invalidKeywords as $keyword){
           if(stripos($body, $keyword) !== false){
               throw new Exception('Spam detected');
           }
       }
   }
}
