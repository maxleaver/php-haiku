<?php

class HaikuGeneratorTest extends PHPUnit_Framework_TestCase
{
	protected $haiku = null;

	public function setUp()
	{
		$this->haiku = new PhpHaiku\Haiku();
	}

	/** @test */
    public function a_string_with_less_than_seventeen_syllables_cannot_be_haiku()
    {
        $this->assertFalse($this->haiku->generate('this string has too few syllables'));
    }

    /** @test */
    public function a_string_with_greater_than_seventeen_syllables_cannot_be_haiku()
    {
        $this->assertFalse($this->haiku->generate('this string has too many syllables to be converted into a haiku'));
    }

    /** @test */
    public function a_seventeen_syllable_string_with_syllable_breaks_inside_words_cannot_be_haiku()
    {
        $this->assertFalse($this->haiku->generate('this string has words spanning a line break but has seventeen syllables'));
    }

    /** @test */
    public function a_seventeen_syllable_string_with_clean_word_breaks_between_lines_can_be_haiku()
    {
        $expected = array(
        	'first' => 'this text string has just',
        	'second' => 'enough syllables to be',
        	'third' => 'made into haiku'
        );
        $result = $this->haiku->generate('this text string has just enough syllables to be made into haiku');

        $this->assertEquals($expected, $result);
    }
}