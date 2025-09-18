<?php
/**
 * Excerptify Twig Filter plugin for Craft CMS 4.x
 *
 * @link      http://www.belniakmedia.com
 * @copyright Copyright (c) 2017 Belniak Media Inc.
 */

namespace belniakmedia\craftExcerptify\twigextensions;

use DOMDocument;
use DOMElement;
use DOMText;
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

    private function stripTags(string $html, null|string|array $allowed_tags = null): string
    {
        $phString = '{!{rs}!}';
        $html = str_replace('<', ' <', $html);
        $html = preg_replace('/>([^\s\w]+)/', ">$phString$1", $html);
        $html = strip_tags($html, $allowed_tags);
        $html = trim(preg_replace('/\s+/', ' ', $html));
        return trim(preg_replace('/\s+' . preg_quote($phString, '/') . '/', '', $html));
    }

    // Works with plain strings (will strip all HTML tags but retain html text content).
    public function excerptify($text = null, $characterCount = 200, $forceBreakWord = false, $allowedTags = null, bool $trim = false): string
    {

        // Sanitize allow tags variable
        if(!empty($allowedTags) && !is_string($allowedTags) && !is_array($allowedTags)) {
            $allowedTags = null;
        }

        // ensure a positive character count
        if(!is_int($characterCount) || $characterCount <= 0) { $characterCount = 200; }

        // get all text stripped of html as one line.
        $text = preg_replace('/[\r\n]/', " ", $this->stripTags($text, $allowedTags));

        // replace all occurrences of more than one consecutive space with a single space.
        $text = trim(preg_replace('/\s+/', " ", $text));

        // Allow entities
        $text = html_entity_decode(str_replace('&amp;', '&', $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $parsedHtml = $contentRemoved = false;

        if(strip_tags($text) != $text) {

            $parsedHtml = true;

            // Initialize dom object with faked body root element and the provided content within
            $dom = new DOMDocument;
            @$dom->loadHTML(
                '<body>' . mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8') . '</body>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $charactersLeft = $characterCount;
            $parse = function(DOMElement $element) use (&$parse, &$contentRemoved, &$charactersLeft, $forceBreakWord, $dom): string
            {
                $content = '';
                foreach($element->childNodes as $childNodeRef) {
                    if($charactersLeft <= 0) { break; }
                    $childNode = $childNodeRef->cloneNode(true);
                    if($childNode instanceof DOMText) {
                        $nodeText = trim(preg_replace('/\s+/', " ", $childNode->textContent));
                        $textLen = mb_strlen($nodeText);
                        if($textLen <= $charactersLeft) {
                            $extractedText = $nodeText;
                            if(!$content) {
                                $content = $extractedText;
                                $charactersLeft = $charactersLeft - $textLen;
                            } else {
                                $content .= ' ' . $extractedText;
                                $charactersLeft = $charactersLeft - ($textLen + 1);
                            }
                        } else {
                            $extractedText = mb_substr($nodeText, 0, $charactersLeft - 1);
                            if(!$forceBreakWord) {
                                // break at last occurrence of space character in resulting text.
                                $spacePos = mb_strrpos($extractedText, " ");
                                $extractedText = rtrim(mb_substr($extractedText, 0, $spacePos));
                            }
                            $content = trim($content . ' ' . $extractedText);
                            $charactersLeft = 0;
                            $contentRemoved = true;
                        }

                    } elseif ($childNode instanceof DOMElement) {
                        if($internalContent = $parse($childNode)) {
                            // Clear node content
                            while ($childNode->firstChild) {
                                $childNode->removeChild($childNode->firstChild);
                            }
                            // Set node content to the returned content
                            $childNode->appendChild(new DOMText($internalContent));
                            // Extract outer HTML to append to the trimmed content like normal
                            $content .= ' ' . $dom->saveHTML($childNode);
                        }
                    } else {
                        throw new \Exception("Should not be here");
                    }
                }
                return trim($content);
            };

            // Process the dom tree adding non-breaking spaces where applicable (heavy recursion within)
            $text = $parse($dom->documentElement);

        } else {

            if($forceBreakWord) {
                // Return trimmed string cut exactly at character count if $forceBreakWord is set.
                $trimmed = trim(mb_substr($text, 0, $characterCount));
                $contentRemoved = mb_strlen($text) !== mb_strlen($trimmed);

            } else if(mb_strlen($text) <= $characterCount) {
                // Avoid losing last word if text length is already less than character count
                $trimmed = trim($text);
                $contentRemoved = false;
            } else {
                // break string at character count.
                $trimmed = trim(mb_substr($text, 0, $characterCount));
                // break at last occurrence of space character in resulting text.
                $spacePos = mb_strrpos($trimmed, " ");
                // return trimmed text.
                $trimmed = trim(mb_substr($trimmed, 0, $spacePos));
                $contentRemoved = mb_strlen($text) !== mb_strlen($trimmed);
            }

            $text = $trimmed;
        }

        if($contentRemoved && $trim && (!$parsedHtml || !str_ends_with($text, '>'))) {
            $text = preg_replace('/[^\p{L}\p{N}]+$/u', '', $text);
        }

        return $text;
    }
}
