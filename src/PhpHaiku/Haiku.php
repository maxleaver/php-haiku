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

	public function setText(string $text)
	{
		$this->text = $this->cleanString($text);

		$result = $this->checkForHaiku();
		$this->setHaiku($result);
	}

	public function getText()
	{
		return $this->text;
	}

	protected function setHaiku(bool $isHaiku)
	{
		$this->isHaiku = $isHaiku;
	}

	public function isHaiku()
	{
		return $this->isHaiku;
	}

	protected function setFirstLine($text)
	{
		$this->first = $text;
	}

	public function getFirstLine()
	{
		return $this->first;
	}

	protected function setSecondLine($text)
	{
		$this->second = $text;
	}

	public function getSecondLine()
	{
		return $this->second;
	}

	protected function setThirdLine($text)
	{
		$this->third = $text;
	}

	public function getThirdLine()
	{
		return $this->third;
	}

	protected function checkForHaiku()
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

	protected function cleanString(string $string)
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
