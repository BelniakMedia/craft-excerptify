<?php
/**
 * Excerptify Twig Filter plugin for Craft CMS 4.x
 *
 * @link      http://www.belniakmedia.com
 * @copyright Copyright (c) 2017 Belniak Media Inc.
 */

namespace belniakmedia\craftExcerptify\twigextensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author    Belniak Media Inc.
 * @package   craft-excerptify
 * @since     1.0.0
 */
class ExcerptifyTwigExtension extends AbstractExtension
{
	// Public Methods
	// =========================================================================

	/**
	 * @inheritdoc
	 */
	public function getFilters(): array
    {
		return [
			new TwigFilter('excerptify', [$this, 'excerptify'], ['pre_escape' => 'html', 'is_safe' => array('html')]),
		];
	}

	// Works with plain strings (will strip all HTML tags but retain html text content).
	public function excerptify($text = null, $characterCount = 200, $forceBreakWord = false): string
    {

		// ensure a positive character count
		if(!is_int($characterCount) || $characterCount <= 0) { $characterCount = 200; }

		// get all text stripped of html as one line.
		$text = preg_replace('/\r|\n/', " ", strip_tags($text));

		// replace all occurrences of more than one consecutive space with a single space.
		$text = trim(preg_replace('/\s+/', " ", $text));

		// return trimmed string cut exactly at character count if $forceBreakWord is set.
		if($forceBreakWord) {
			return trim(mb_substr($text, 0, $characterCount));
		}

		// avoid losing last word if text length is already less than character count
		if(mb_strlen($text) <= $characterCount) {
			return $text;
		}

		// break string at character count.
		$text = mb_substr($text, 0, $characterCount);

		// break at last occurrence of space character in resulting text.
		$spacePos = mb_strrpos($text, " ");

		// return trimmed text.
		return rtrim(mb_substr($text, 0, $spacePos));

	}

}
