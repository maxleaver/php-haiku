<?php

namespace PhpHaiku;

use PhpHaiku\Morae;
use DaveChild\TextStatistics as TS;

class Haiku
{
	public $text;
	public $isHaiku = false;
	public $first;
	public $second;
	public $third;

	/**
	 * Sets text and attempts to build the haiku
	 * @param str $text String of text to attempt to make haiku
	 */
	public function setText($text)
	{
		$this->text = $this->sanitizeText($text);

		$result = $this->buildHaiku();
		$this->setHaiku($result);
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
	 * Sets isHaiku boolean
	 * @param boolean $isHaiku Boolean value of haiku attempt
	 */
	protected function setHaiku($isHaiku)
	{
		$this->isHaiku = $isHaiku;
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
	 * Sets the first line of the haiku
	 * @param string $text First line of haiku
	 */
	protected function setFirstLine($text)
	{
		$this->first = $text;
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
	 * Sets the second line of the haiku
	 * @param string $text Second line of haiku
	 */
	protected function setSecondLine($text)
	{
		$this->second = $text;
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
	 * Sets the third line of the haiku
	 * @param string $text Third line of haiku
	 */
	protected function setThirdLine($text)
	{
		$this->third = $text;
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
	 * Attempts to build the haiku
	 * @return boolean TRUE/FALSE if haiku was created
	 */
	protected function buildHaiku()
	{
		$totalSyllables = TS\Syllables::totalSyllables($this->text);

		// Can only be haiku if string has EXACTLY 17 syllables
		if ($totalSyllables !== 17) {
			return FALSE;
		}

		// Split string into an array of words
		$words = explode(' ', $this->text);

		// Build the first line of the haiku
		$firstLine = new Morae(5);
		if (!$firstLine->build($words)) {
			return FALSE;
		}

		$this->setFirstLine($firstLine->text);

		// Build the second line of the haiku
		$secondLine = new Morae(7);
		if (!$secondLine->build($firstLine->remaining)) {
			return FALSE;
		}

		$this->setSecondLine($secondLine->text);

		// Build the third line of the haiku
		$thirdLine = new Morae(5);
		if (!$thirdLine->build($secondLine->remaining)) {
			return FALSE;
		}

		$this->setThirdLine($thirdLine->text);

		$this->third = $thirdLine->text;

		return TRUE;
	}

	/**
	 * Sanitizes text strings
	 * @param  str $string Text string
	 * @return str         Sanitized text string
	 */
	protected function sanitizeText($string)
	{
		$string = preg_replace('/\x{FEFF}/u', '', $string); // Remove UTF-8 BOM
		$string = utf8_decode($string); // Decode UTF-8 string
		$string = str_replace('&nbsp;', ' ', $string); // Convert &nbsp; to actual spaces
		$string = html_entity_decode($string, ENT_QUOTES); // Decoded HTML entities
		$string = strip_tags($string); // Removes HTML tags
		$string = preg_replace('/\s\s+/', ' ', $string); // Removes multiple spaces
		$string = trim($string); // Trims whitespace
		return $string;
	}
}
