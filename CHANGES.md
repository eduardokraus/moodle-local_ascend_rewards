# Changelog

All notable changes to the Ascend Rewards plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.1] - 2025-12-10

### Changed
- Streamlined plugin to DEMO version with 7 active badges
- Reduced avatars to 2 complete sets (Elf/Lynx/Dryad and Imp/Hamster/Mole)
- Removed inactive badge awarding logic (On a Roll, Level Up, Early Bird, Deadline Burner, Sharp Shooter, Activity Ace, Mission Complete, High Flyer, Learning Legend)
- Optimized pet catalog to 2 active pets (Lynx, Hamster)
- Optimized villain catalog to 2 active villains (Dryad, Mole)
- Prepared plugin for Moodle plugins directory submission

### Added
- README.md with comprehensive documentation
- CHANGES.md changelog file
- Proper GPL license headers on all files
- Enhanced privacy API implementation
- thirdpartylibs.xml for vendor dependencies

### Fixed
- Code standards compliance with Moodle coding style
- PHPDoc blocks on all classes and methods
- Proper namespace declarations
- Database schema documentation

## [1.2.2] - 2026-01-28

### Added
- Primary navigation hook to surface Ascend Rewards in the top navbar
- Instructions HTML bundle for demo walkthroughs

### Changed
- Updated demo badge coin defaults
- Demo gift rewards tab now displays PRO-only notice

### Fixed
- Mystery box locked-avatar reward now plays hero video

## [1.2.0] - 2025-12-01

### Added
- Weekly gameboard tracking system
- Badge cache warming for improved performance
- Performance caching layer for badge checks
- Hook callbacks for output customization
- Task for rebuilding badge activity cache

### Changed
- Improved badge awarding logic efficiency
- Enhanced database indexing for better performance
- Updated privacy provider for GDPR compliance

## [1.1.0] - 2025-11-15

### Added
- Mystery box reward system
- Villain unlock functionality
- Pet adoption system
- Avatar selection and unlocking
- Store interface for purchasing rewards

### Changed
- Refactored coin and XP awarding logic
- Improved leaderboard calculations
- Enhanced navigation integration

## [1.0.0] - 2025-11-01

### Added
- Initial release
- Seven core badge types (Getting Started, Halfway Hero, Master Navigator, Feedback Follower, Steady Improver, Tenacious Tiger, Glory Guide)
- Coin and XP economy system
- Basic leaderboard functionality
- Admin dashboard and badge management
- Privacy API implementation
- Moodle 4.0+ compatibility
