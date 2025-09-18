# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
- (add new entries here)

## [1.1.3.1] - 2025-09-18
### Fixed
- Prevent potential fatal on activation by guarding GitHub updater constants (switch from const to define with checks).

## [1.1.3] - 2025-09-18
### Added
- GitHub auto-update integration (shows update notice when new GitHub release tag exists).

### Notes
- Requires repository folder name to be exactly `iconmanager`.
- Uses latest published GitHub Release (semantic tag starting with `v`).

## [1.1.2] - 2025-09-18
### Added
- English default source strings (previous mixed German).
- Translation template `iconmanager.pot` and German translation file `iconmanager-de_DE.po`.

### Changed
- Replaced inline German admin UI text with English originals to standardize i18n workflow.

### Notes
- Existing German installs still see German via new translation file.
- Custom translations: copy `iconmanager.pot` and create your locale `.po/.mo`.

## [1.1.1] - 2025-09-18
### Fixed
- Dynamisches Aktualisieren der Icon-Listen nach Download / Import (kein manueller Reload nötig).
- Buttons blieben nicht mehr in Disabled/"…" Zustand hängen (zentraler ajaxAction Helper).
- JSON Responses robust gegen vorherige Notices (toleranter Parser + Unterdrückung von Notices bei AJAX).
- Menü von eigenem Top-Level zu Werkzeuge verschoben (besserer Kontext, Filter `iconmanager_admin_parent`).

### Added
- Ladeindikator während Icon-Liste neu aufgebaut wird.

### Changed
- Standard-Admin-Menüposition jetzt unter Werkzeuge.

## [1.1.0] - 2025-09-18
### Added
- Initial public GitHub release (renamed from previous private variant).
- Admin UI with help, options, license panels.
- Batch & single download of Brand (Simple Icons) and UI (Lucide) SVGs.
- Auto-download missing icons (admin context) and object-cache rendering.
- Media Library bulk & single import.
- GDPR/security headers for served assets.
- Legacy compatibility aliases + migration of previous upload directory.

### Changed
- Namespace / function prefix to `iconmanager_`.
- Upload directory to `iconmanager-icons`.

### Removed
- Old plugin specific prefix usage (kept aliases only for backward compatibility).

[Unreleased]: https://github.com/atakan72/iconmanager/compare/v1.1.3.1...HEAD
[1.1.3.1]: https://github.com/atakan72/iconmanager/compare/v1.1.3...v1.1.3.1
[1.1.3]: https://github.com/atakan72/iconmanager/compare/v1.1.2...v1.1.3
[1.1.2]: https://github.com/atakan72/iconmanager/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/atakan72/iconmanager/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/atakan72/iconmanager/releases/tag/v1.1.0
