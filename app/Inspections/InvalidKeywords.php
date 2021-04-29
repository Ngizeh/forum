<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    /**
     * Invalid key words for a spam
     * @var string[]
     */
    public  $invalidKeywords = [
        'Google Customer Support'
    ];

    /**
     * Find is the body is spam
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
