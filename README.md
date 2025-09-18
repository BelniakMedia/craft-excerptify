# Excerptify Plugin for CMS 3.x

Provides the `excertify` twig filter which truncates the provided variable's text or html to the nearest whole word based on the provided character length.

Parameters
--
```
excerptify(int $characterCount, bool $forceBreakWord, null|string|array $allowedTags)
```
| Parameter       | Type                | Default | Description                                                                                                                                                                                                                                                                                                                 |
|:----------------|:--------------------|:--------|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| $characterCount | int                 | 200     | Number of characters to limit result to.                                                                                                                                                                                                                                                                                    |
| $forceBreakWord | bool                | false   | When false, the text will be broken at the last occurring word break character. When true it will return the text up to the exact character limit, breaking a word if necessary.                                                                                                                                            |
| $allowedTags    | null\|string\|array | null    | A string representing a single HTML tag or an array of strings representing HTML tags. *Added in 5.0.2*                                                                                                                                                                                                                     |
| $trim           | bool                | false   | When enabled, will remove any non unicode letter or number characters at the end of the returned excerpt. If no visible text was was removed, then the trim does not occur. If HTML was allowed and left in when parsing the content, the > character is also allowed when trimming would otherwise occur. *Added in 5.0.3* |

## Usage Examples

---

### Prevent certain HTML tags from being stripped from the output

Pass a string or array of strings to the third parameter representing the tags that should not be removed:
```
<p>{{ postContent | excerptify(200, false, '<a>') }}</p>
```

*This is a new feature as of 5.0.2*
<br><br>

### Trim left-over punctuation characters from the end of the returned excerpt

Set the 4th parameter ($trim) to true:
```
<p>{{ postContent | excerptify(200, false, '<a>', true) }}</p>
```
NOTE: Trimming is only performed, when enabled, if visible text was removed. Any non Unicode safe letter or number will be removed from the end. When HTML was allowed and left in for the text being processed, the end HTML character `>` is also allowed as to not accidentally scenarios where the text ended with an allowed HTML tag.

Enabling this feature will allow you to add your own ellipsis (...) after the excerpt in your use-case without fear of ending up with something like `however,...` which would be undesirable.

*This is a new feature as of 5.0.3*

>**WARNING:**<br>If no visible text was removed then full string less non-allowed html tags will be returned and the trim will not occur. In this case, if the text you passed in ended with a period, the period will be retained in the output. You should test for this before blindly adding your ellipsis or other punctuation. I recommend two ways to accomplish this:
>
>1. Get two copies of the excerpt from the filter where one uses a very high character count to ensure no text is removed, and also get your actual excerpt. You can compare the raw lengths of the values to determine if content was trimmed and handle it as needed:
>```twig
>{% set fullText = postContent | excerptify(20000000, false, '<a>', true) %}
>{% set excerpt = postContent | excerptify(200, false, '<a>', true) %}
>{% set ending = '.' %}
>{% if fullText|length > excerpt|length %}
>    {% set ending = '...' %}
>{% endif %}
><p>{{ excerpt | raw }}{{ ending }}</p>
>```
>2. Use a regex match to check if a non-word character is present at the end of the excerpt:
>```twig
>{% set excerpt = postContent | excerptify(200, false, '<a>', true) %}
>{% set ending = '.' %}
>{% if excerpt matches '/[^\p{L}\p{N}]+$/u' %}
>    {% set ending = '...' %}
>{% endif %}
><p>{{ excerpt | raw }}{{ ending }}</p>
>```
<br><br>
#### Break at nearest word
This is the default as in the same sas passing no arguments. Will return full words and the resulting string will not exceed 200 characters.
```
<p>{{ postContent | excerptify(200) }}</p>
```

To break exactly at the specified character count, even mid word, pass true for the forceBreakWord parameter:
```
<p>{{ postContent | excerptify(200, true) }}</p>
``` 


Brought to you by [Belniak Media Inc.](http://www.belniakmedia.com)
