All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

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
