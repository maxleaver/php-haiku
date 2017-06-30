<?php

namespace PhpHaiku;

use DaveChild\TextStatistics as TS;
use Exception;

class Morae
{
	private $words = [];
	private $totalWords = [];
	private $maxSyllables;
	private $totalSyllables = 0;

	public $text;
	public $remaining = [];

	public function __construct($maxSyllables, $wordArray)
	{
		$this->setMaxSyllables($maxSyllables);
		$this->setTotalWords($wordArray);
	}

	/**
	 * Sets the maximum number of syllables
	 * @param int $count Maximum syllable count
	 */
	protected function setMaxSyllables($count)
	{
		$this->maxSyllables = $count;
	}

	/**
	 * Sets the total words to use when building the line
	 * @param array $wordArray Array of words (strings)
	 */
	protected function setTotalWords($wordArray)
	{
		$this->totalWords = $wordArray;
		$this->build();
	}

	/**
	 * Sets the text representation of the words
	 */
	protected function setText()
	{
		$this->text = implode(' ', $this->words);
	}

	/**
	 * Returns text of the morae
	 * @return str Morae text
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Sets the array of remaining (unused) words
	 */
	protected function setRemaining()
	{
		$this->remaining = array_values($this->totalWords);
	}

	/**
	 * Returns array of unused words
	 * @return array Array of unused words
	 */
	public function getRemaining()
	{
		return $this->remaining;
	}

	/**
	 * Builds the Morae (a line matching a specific syllable count)
	 */
	protected function build()
	{
		foreach ($this->totalWords as $key => $word) {
			$syllables = $this->countWordSyllables($word);
			$this->totalSyllables += $syllables;

			// Stop if syllables exceed the maximum number
			if ($this->totalSyllables > $this->maxSyllables) {
				throw new Exception('Syllables exceed the maximum number');
			}

			$this->addWordToLine($key, $word);

			// Stop if we've reached the total number of syllables
			if ($this->totalSyllables === $this->maxSyllables) {
				break;
			}
		}

		$this->setRemaining();
		$this->setText();
	}

	/**
	 * Counts the syllables in a word and adds to the total syllable count
	 * @param  string $word Word to use for syllable count
	 * @return int       	Number of syllables
	 */
	protected function countWordSyllables($word)
	{
		return TS\Syllables::syllableCount($word);
	}

	/**
	 * Adds words to the word array and removes from the total words array
	 * @param int 		$key  Key for word in Total Words array
	 * @param string 	$word Word to add to array
	 */
	protected function addWordToLine($key, $word)
	{
		array_push($this->words, $word);
		unset($this->totalWords[$key]);
	}
}
