<?php

namespace PhpHaiku;

use DaveChild\TextStatistics as TS;

class Haiku
{
	public static function generate($string)
	{
		/*
		The HTML
		is stripped from the text string
		to prep for counting.
		 */
		$string = self::cleanString($string);

		/*
		Count the syllables
		for the entire text string
		as a quick first check.
		 */
		$syllableCount = self::countTotalSyllables($string);

		/*
		Haiku can only
		have seventeen syllables.
		Yeah, I know. It's strange.
		 */
		if ($syllableCount !== 17) {
			return FALSE;
		}

		return self::buildHaiku($string);
	}

	private static function cleanString($string)
	{
		$string = utf8_decode($string);
		$string = str_replace('&nbsp;', ' ', $string); // Convert &nbsp; to actual spaces
		$string = html_entity_decode($string, ENT_QUOTES); // Decoded HTML entities
		$string = strip_tags($string); // Removes HTML tags
		$string = preg_replace('/\s\s+/', ' ', $string); // Removes multiple spaces
		$string = trim($string); // Trims whitespace
		return $string;
	}

	private static function countTotalSyllables($string)
	{
		return TS\Syllables::totalSyllables($string);
	}

	private static function buildHaiku($string)
	{
		$words = explode(' ', $string);

		$firstRow = array(
			'syllables' => 0,
			'words' => array()
		);
		$secondRow = array(
			'syllables' => 0,
			'words' => array()
		);
		$thirdRow = array(
			'syllables' => 0,
			'words' => array()
		);

		foreach ($words as $word) {
			$cnt = TS\Syllables::syllableCount($word);

			if ($firstRow['syllables'] < 5) {
				/*
				The syllables
				are added to the first row
				and it's checked for length.
				 */
				$firstRow['syllables'] += $cnt;

				if ($firstRow['syllables'] > 5) {
					return FALSE;
				}

				array_push($firstRow['words'], $word);
				continue;
			}

			/*
			The first row must have
			exactly five syllables
			or it's not haiku.
			 */
			if ($firstRow['syllables'] !== 5) {
				return FALSE;
			}

			/*
			The second row is
			like the first, except it has
			seven syllables.
			 */
			if ($secondRow['syllables'] < 7) {
				$secondRow['syllables'] += $cnt;

				if ($secondRow['syllables'] > 7) {
					return FALSE;
				}

				array_push($secondRow['words'], $word);
				continue;
			}

			/*
			Check the second row
			to make sure it's exactly
			seven syllables.
			 */
			if ($secondRow['syllables'] !== 7) {
				return FALSE;
			}

			/*
			The third row has five
			syllables, just like the first.
			Room to refactor.
			 */
			if ($thirdRow['syllables'] < 5) {
				$thirdRow['syllables'] += $cnt;

				if ($thirdRow['syllables'] > 5) {
					return FALSE;
				}

				array_push($thirdRow['words'], $word);
				continue;
			}

			/*
			The third row must have
			exactly five syllables
			or it's not haiku
			 */
			if ($firstRow['syllables'] !== 5) {
				return FALSE;
			}
		}

		/*
		Return an array
		with the completed haiku
		and find inner peace.
		 */
		return array(
			'first' => implode(' ', $firstRow['words']),
			'second' => implode(' ', $secondRow['words']),
			'third' => implode(' ', $thirdRow['words'])
		);
	}
}