<?php

use PhpHaiku\Haiku;

class HaikuTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_has_a_text_string()
    {
        $string = 'this text string has just enough syllables to be made into haiku';
        $haiku = new Haiku($string);

        $this->assertEquals($haiku->getText(), $string);
    }

    /** @test */
    public function it_has_three_lines()
    {
        $string = 'this text string has just enough syllables to be made into haiku';
        $haiku = new Haiku($string);

        $this->assertTrue($haiku->isHaiku());
        $this->assertEquals($haiku->getFirstLine(), 'this text string has just');
        $this->assertEquals($haiku->getSecondLine(), 'enough syllables to be');
        $this->assertEquals($haiku->getThirdLine(), 'made into haiku');
    }

    /** @test */
    public function it_cannot_have_more_or_less_than_seventeen_syllables()
    {
        $shortString = 'this string has too few syllables';
        $longString = 'this string has too many syllables to be converted into a haiku';

        $shortHaiku = new Haiku($shortString);
        $longHaiku = new Haiku($longString);
        $this->assertFalse($shortHaiku->isHaiku);
        $this->assertFalse($longHaiku->isHaiku);
    }

    /** @test */
    public function it_cannot_have_words_with_a_syllable_break_between_lines()
    {
        $string = 'this string has words spanning a line break but has seventeen syllables';

        $haiku = new Haiku($string);
        $this->assertFalse($haiku->isHaiku);
    }

    /** @test */
    public function it_has_seventeen_syllables_and_clean_word_breaks_between_lines()
    {
        $string = 'this text string has just enough syllables to be made into haiku';

        $haiku = new Haiku($string);
        $this->assertTrue($haiku->isHaiku);
        $this->assertEquals($haiku->text, $string);
        $this->assertEquals($haiku->first, 'this text string has just');
        $this->assertEquals($haiku->second, 'enough syllables to be');
        $this->assertEquals($haiku->third, 'made into haiku');
    }

    /** @test */
    public function it_strips_html_and_special_characters_from_the_string()
    {
        $string = 'This <a href="http://www.example.com">string&#39;s</a> got<br /><br> just the right number of syllables&nbsp;&nbsp; to be a haiku.';

        $haiku = new Haiku($string);
        $this->assertTrue($haiku->isHaiku);
        $this->assertEquals($haiku->text, 'This string\'s got just the right number of syllables to be a haiku.');
        $this->assertEquals($haiku->first, 'This string\'s got just the');
        $this->assertEquals($haiku->second, 'right number of syllables');
        $this->assertEquals($haiku->third, 'to be a haiku.');
    }

    /** @test */
    public function it_removes_utf8_byte_order_markers_from_the_string()
    {
        $string = 'this text string has just enough syllables to be made into haiku';
        $utf8_with_bom = chr(239) . chr(187) . chr(191) . $string;

        $haiku = new Haiku($utf8_with_bom);
        $this->assertTrue($haiku->isHaiku);
        $this->assertEquals($haiku->text, $string);
        $this->assertEquals($haiku->first, 'this text string has just');
        $this->assertEquals($haiku->second, 'enough syllables to be');
        $this->assertEquals($haiku->third, 'made into haiku');
    }
}
