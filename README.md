# Excerptify Plugin for CMS 3.x

Provides the `excertify` twig filter which truncates the provided variable's text or html to the nearest whole word based on the provided character length.

Parameters
--
```
excerptify(int $characterCount, bool $forceBreakWord)
```
| Parameter | Type | Default | Description |
| :-------- | :--- | :------ | :---------- |
| $characterCount | Int | 200 | Number of characters to limit result to. |
| $forceBreakWord | Bool | false | When false, the text will be broken at the last occurring word break character. When true it will return the text up to the exact character limit, breaking a word if necessary. |


Usage Examples
--
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
