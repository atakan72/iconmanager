# Contributing to Icon Manager

Thank you for considering a contribution! :tada:

## Code of Conduct
Be respectful. No harassment, personal attacks or discrimination. When in doubt, stay constructive.

## Workflow
1. Fork the repository.
2. Create a feature branch: `feat/your-topic` or `fix/issue-123`.
3. Commit in small logical steps (imperative style): `Add bulk import nonce check`.
4. Open a Pull Request (PR) against `main`.
5. Reference related issues using `Closes #123` when applicable.

## Issues
Before opening a new issue:
- Search existing issues.
- Provide steps to reproduce (if bug) and expected vs actual behaviour.
- Add environment info: WP version, PHP version, plugin version.

## Branch Naming
- Features: `feat/...`
- Fixes: `fix/...`
- Docs: `docs/...`
- Chore/refactor: `chore/...` or `refactor/...`

## Coding Standards
- Follow WordPress PHP Coding Standards.
- Function prefix: `iconmanager_`.
- Escape output (`esc_html`, `esc_attr`, `esc_url`) appropriately.
- Nonces for state-changing AJAX or form actions.
- Translatable strings via `__()`, `_e()`, etc. Text domain: `iconmanager`.

## PHP Version
Target PHP 7.4+ (typed properties avoided for broader compatibility). Keep code compatible unless there's a strong reason.

## Internationalization
- Wrap user-facing strings.
- Keep stable original strings to avoid unnecessary re-translation.

## Security
- Sanitize all input (`sanitize_text_field`, `sanitize_file_name`, etc.).
- Check capabilities (e.g. `manage_options`, `upload_files`).
- Use nonces for POST/AJAX settings changes.

## Performance
- Cache where sensible using `wp_cache_*` and clear on version bump or relevant state change.

## Adding New Features
Provide:
- Motivation / use case
- Minimal UI impact
- Backwards compatibility

## Testing
Manual testing steps in PR description are fine for now. (Automated tests may be added later.)

## Commits & Changelog
Add a brief entry suggestion in the PR body for the next `Unreleased` section of `CHANGELOG.md`.

## Release Process (Maintainer)
1. Update `CHANGELOG.md` (move Unreleased -> version).
2. Bump `Version` header + `ICONMANAGER_VERSION` constant.
3. Tag: `git tag vX.Y.Z && git push --tags`.

## License
By contributing you agree your code is released under GPL-2.0-or-later.

Thanks! :heart:
