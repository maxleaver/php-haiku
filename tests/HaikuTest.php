<?php

use PhpHaiku\Haiku;

class HaikuTest extends PHPUnit_Framework_TestCase
{
	protected $haiku = null;

	public function setUp()
	{
		$this->haiku = new Haiku();
	}

	/** @test */
    public function a_string_with_less_than_seventeen_syllables_cannot_be_haiku()
    {
        $string = 'this string has too few syllables';
        $this->haiku->setText($string);

        $this->assertFalse($this->haiku->isHaiku);
    }

    /** @test */
    public function a_string_with_greater_than_seventeen_syllables_cannot_be_haiku()
    {
        $string = 'this string has too many syllables to be converted into a haiku';
        $this->haiku->setText($string);

        $this->assertFalse($this->haiku->isHaiku);
    }

    /** @test */
    public function a_seventeen_syllable_string_with_syllable_breaks_inside_words_cannot_be_haiku()
    {
        $string = 'this string has words spanning a line break but has seventeen syllables';
        $this->haiku->setText($string);

        $this->assertFalse($this->haiku->isHaiku);
    }

    /** @test */
    public function a_seventeen_syllable_string_with_clean_word_breaks_between_lines_can_be_haiku()
    {
        $string = 'this text string has just enough syllables to be made into haiku';
        $this->haiku->setText($string);

        $this->assertTrue($this->haiku->isHaiku);
        $this->assertEquals($this->haiku->text, $string);
        $this->assertEquals($this->haiku->first, 'this text string has just');
        $this->assertEquals($this->haiku->second, 'enough syllables to be');
        $this->assertEquals($this->haiku->third, 'made into haiku');
    }

    /** @test */
    public function html_and_special_characters_should_be_stripped_from_the_string()
    {
        $string = 'This <a href="http://www.example.com">string&#39;s</a> got<br /><br> just the right number of syllables&nbsp;&nbsp; to be a haiku.';
        $this->haiku->setText($string);

        $this->assertTrue($this->haiku->isHaiku);
        $this->assertEquals($this->haiku->text, 'This string\'s got just the right number of syllables to be a haiku.');
        $this->assertEquals($this->haiku->first, 'This string\'s got just the');
        $this->assertEquals($this->haiku->second, 'right number of syllables');
        $this->assertEquals($this->haiku->third, 'to be a haiku.');
    }

    /** @test */
    public function should_remove_utf_8_bom_from_strings()
    {
        $string = 'this text string has just enough syllables to be made into haiku';
        $utf8_string = utf8_encode($string);
        $utf8_with_bom = $utf8_string . chr(239) . chr(187) . chr(191);

        $this->haiku->setText($utf8_with_bom);

        $this->assertTrue($this->haiku->isHaiku);
        $this->assertEquals($this->haiku->text, $string);
        $this->assertEquals($this->haiku->first, 'this text string has just');
        $this->assertEquals($this->haiku->second, 'enough syllables to be');
        $this->assertEquals($this->haiku->third, 'made into haiku');
    }
}
