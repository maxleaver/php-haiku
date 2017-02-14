<?php

use PhpHaiku\Morae;

class MoraeTest extends PHPUnit_Framework_TestCase
{
	protected $morae;

	public function setUp()
	{
		$this->morae = new Morae(5);
	}

    /** @test */
    public function builds_correctly_if_syllables_are_exact_amount()
    {
        $words = array('an', 'array', 'of', 'words');
        $result = $this->morae->build($words);

        $this->assertTrue($result);
        $this->assertEquals($this->morae->text, 'an array of words');
        $this->assertEquals($this->morae->remaining, array());
    }

    /** @test */
    public function returns_remaining_words_if_successful_but_has_leftover_words()
    {
        $words = array('an', 'array', 'of', 'words', 'plus', 'some', 'more');
        $result = $this->morae->build($words);

        $this->assertTrue($result);
        $this->assertEquals($this->morae->text, 'an array of words');
        $this->assertEquals($this->morae->remaining, array('plus', 'some', 'more'));
    }

    /** @test */
    public function fails_if_words_exceed_maximum_syllable_count()
    {
        $words = array('this', 'array', 'is', 'excessively', 'long');
        $result = $this->morae->build($words);

        $this->assertFalse($result);
    }
}
