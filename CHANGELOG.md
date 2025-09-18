All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

# 5.0.3 - 2025-09-17
### Added
- Added full HTML parsing when HTML is detected after non-allowed tags are removed. This parsing ensures only visible characters are counted against the limit and ensures broken or left open HTML tags are not returned due to the string being clipped in the middle of an open tag (which was possible in version 5.0.2 released earlier today).
- Added a custom `strip_tags` wrapper method to use internally which ensures that text is not left runtogether when html tags are removed which was also possible in earlier versions.
- Added one more additional parameter to the filter signature (`boolean $trim`) which defaults to false for backward compatibility but when enabled will remove any non-letter-or-number (Unicode supported) characters from the end of the trimmed string (but will allow the end of a html tag (>) to remain when HTML was parsed). When enabled you can rely on there not being any punctuation at the end of the excerpt so you can safely add your own ellipsis or whatever. Be advised this only does the trim when the content was trimmed. If no visible text was removed then the ending punctuation is left intact. You can test for this before adding your own on the front end. See the README.md for more.

# 5.0.2 - 2025-09-17
### Added
- Added 3rd parameter to filter call to allow integrator to pass in the `$allowed_tags` value to use in the `strip_tags` function call internally. This allows the integrator to allow links to remain in place, for example.

# 5.0.1 - 2025-03-31
### Added
- Added proper handling of html entities to convert them to actual characters before processing. This ensures escaped html entity strings are not returned in the output.

# 5.0.0 - 2024-06-20
### Added
- Added support for CraftCMS 5.x

# 2.0.0 - 2022-11-17
### Added
- Added support for CraftCMS 4.x
### Removed
- Removed support for CraftCMS 3.x (Use 1.x.x for Craft3)

# 1.0.1 - 2021-07-13
### Fixed
- `exerptify()` would erroneously remove the last word of the provided text if the provided text character count was already less than `$characterCount` and `$forceBreakWord` was not set to true. 
### Removed
- `composer.lock` from repo since this is a "library" and is not needed.

# 1.0.0 - 2020-03-11
### Added
- Initial release
