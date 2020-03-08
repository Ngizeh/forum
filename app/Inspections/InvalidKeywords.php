<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    public  $invalidKeywords = [
        'Google Customer Support'
    ];


   public  function detect($body)
   {
       foreach ($this->invalidKeywords as $keyword){
           if(stripos($body, $keyword) !== false){
               throw new Exception('Spam detected');
           }
       }
   }
}
