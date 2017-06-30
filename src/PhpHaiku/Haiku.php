<?php

namespace PhpHaiku;

use Exception;
use PhpHaiku\Morae;
use DaveChild\TextStatistics as TS;

class Haiku
{
	public $text;
	public $isHaiku;
	public $first;
	public $second;
	public $third;

	private $words = [];

	public function __construct($text)
	{
		$this->setText($text);
		$this->setWords();
		$this->setHaiku();
	}

	/**
	 * Sets the text property
	 * @param string $text The text string
	 */
	protected function setText($text)
	{
		$this->text = $this->sanitizeText($text);
	}

	/**
	 * Returns original text string
	 * @return str Original text string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Builds Haiku from the text string
	 * @return boolean    Result of haiku conversion
	 */
	protected function setHaiku()
	{
		$this->isHaiku = FALSE;

		if ($this->isSeventeenSyllables() === FALSE) {
			return FALSE;
		}

		$firstLine = $this->makeStringWithSyllables(5, $this->words);
		if ($firstLine === FALSE) {
			return FALSE;
		}

		$secondLine = $this->makeStringWithSyllables(7, $firstLine->remaining);
		if ($secondLine === FALSE) {
			return FALSE;
		}

		$thirdLine = $this->makeStringWithSyllables(5, $secondLine->remaining);
		if ($thirdLine === FALSE) {
			return FALSE;
		}

		$this->first = $firstLine->text;
		$this->second = $secondLine->text;
		$this->third = $thirdLine->text;

		$this->isHaiku = TRUE;
		return TRUE;
	}

	/**
	 * Splits the text string into an array of words
	 */
	protected function setWords()
	{
		$this->words = explode(' ', $this->text);
	}

	/**
	 * Returns TRUE/FALSE if text string is a haiku
	 * @return boolean    Result of haiku conversion
	 */
	public function isHaiku()
	{
		return $this->isHaiku;
	}

	/**
	 * Returns the first line of the haiku
	 * @return string First line of haiku
	 */
	public function getFirstLine()
	{
		return $this->first;
	}

	/**
	 * Returns the second line of the haiku
	 * @return string Second line of haiku
	 */
	public function getSecondLine()
	{
		return $this->second;
	}

	/**
	 * Returns the third line of the haiku
	 * @return string Third line of haiku
	 */
	public function getThirdLine()
	{
		return $this->third;
	}

	/**
	 * Sanitizes text strings
	 * @param  str $string Text string
	 * @return str         Sanitized text string
	 */
	protected function sanitizeText($string)
	{
		$string = preg_replace('/\x{FEFF}/u', '', $string); // Remove UTF-8 BOM
		$string = str_replace('&nbsp;', ' ', $string); // Convert &nbsp; to actual spaces
		$string = html_entity_decode($string, ENT_QUOTES); // Decoded HTML entities
		$string = strip_tags($string); // Removes HTML tags
		$string = preg_replace('/\s\s+/', ' ', $string); // Removes multiple spaces
		$string = trim($string); // Trims whitespace
		return $string;
	}

	/**
	 * Checks if the text has exactly seventeen syllables
	 * @return boolean TRUE/FALSE if exactly 17 syllables
	 */
	protected function isSeventeenSyllables()
	{
		return TS\Syllables::totalSyllables($this->text) === 17;
	}

	/**
	 * Creates a string of words with a specific syllable count
	 * @param  int 			$syllableCount Exact number of syllables
	 * @param  array 		$words         Array of words
	 * @return bool/str					   Text string or FALSE
	 */
	protected function makeStringWithSyllables($syllableCount, $words)
	{
		try {
			$line = new Morae($syllableCount, $words);
		} catch (Exception $e) {
			// Too many syllables
			return FALSE;
		}

		return $line;
	}
}
