<?php

namespace PhpHaiku;

use DaveChild\TextStatistics as TS;

class Morae
{
	private $maxSyllables;
	public $text;
	public $remaining = array();

	public function __construct($maxSyllables)
	{
		$this->maxSyllables = $maxSyllables;
	}

	public function getText()
	{
		return $this->text;
	}

	public function getRemaining()
	{
		return $this->remaining;
	}

	public function build(array $words)
	{
		$totalSyllables = 0;
		$wordArray = array();

		foreach ($words as $key => $word) {
			// Success if we've reached total number of syllables
			if ($totalSyllables === $this->maxSyllables) {
				break;
			}

			// Count number of syllables in word
			$wordSyllables = TS\Syllables::syllableCount($word);

			// Add syllables to total count for morae
			$totalSyllables += $wordSyllables;

			// Stop if syllables exceed maximum number
			if ($totalSyllables > $this->maxSyllables) {
				return FALSE;
			}

			// Add word to the word array
			array_push($wordArray, $word);

			// Remove word from array
			unset($words[$key]);
		}

		$this->remaining = array_values($words);
		$this->text = implode(' ', $wordArray);

		return TRUE;
	}
}
