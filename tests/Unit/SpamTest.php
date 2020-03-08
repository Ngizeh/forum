<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test * */
    public function it_validates_spam()
    {

        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

    }

    /** @test * */
    public function it_detects_key_held_down()
    {

        $spam = new Spam();

        $this->expectException(\Exception::class);

        $this->assertFalse($spam->detect('Hello world aaaaaaaaaaaaaaaaaaaa'));

    }
}
