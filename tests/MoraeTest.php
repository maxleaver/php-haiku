<?php

use PhpHaiku\Morae;

class MoraeTest extends \PHPUnit_Framework_TestCase
{
    /**
        @test
        @expectedException Exception
    */
    public function it_cannot_have_words_that_span_the_maximum_syllables()
    {
        $words = array('a', 'word', 'array');
        new Morae(3, $words);

        $this->expectException(Exception::class);
    }

    /** @test */
    public function it_has_an_array_of_leftover_words()
    {
        $words = array('an', 'array', 'of', 'words');
        $morae = new Morae(3, $words);

        $this->assertEquals($morae->getRemaining(), ['of', 'words']);
    }

    /** @test */
    public function it_has_an_empty_array_of_leftover_words_if_the_string_is_the_exact_syllables()
    {
        $words = array('an', 'array', 'of', 'words');
        $morae = new Morae(5, $words);

        $this->assertEmpty($morae->getRemaining());
    }

    /** @test */
    public function it_has_a_string_representation_of_the_line()
    {
        $words = array('an', 'array', 'of', 'words');

        $morae = new Morae(3, $words);
        $this->assertEquals($morae->getText(), 'an array');
    }
}
