All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

# 1.0.1 - 2021-07-13
### Fixed
- `exerptify()` would erroneously remove the last word of the provided text if the provided text character count was already less than `$characterCount` and `$forceBreakWord` was not set to true. 
### Removed
- `composer.lock` from repo since this is a "library" and is not needed.

# 1.0.0 - 2020-03-11
### Added
- Initial release
