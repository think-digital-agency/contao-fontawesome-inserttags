# Changelog

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [1.0.0] - 2026-06-14

Initial release — extracted from the Contao Design+ theme into a standalone bundle.

### Added
- Insert tag `{{fas::icon}}` — FontAwesome Solid
- Insert tag `{{far::icon}}` — FontAwesome Regular
- Insert tag `{{fab::icon}}` — FontAwesome Brands
- Insert tag `{{fa::icon}}` — generic (v4 compatibility)
- Optional modifier parameters: `{{fas::rocket::spin::2x}}` → `fa-spin fa-2x`
- XSS validation: icon name and modifiers validated against `^[a-z0-9-]+$`
- `aria-hidden="true"` on every generated `<i>` element
- `OutputType::html` — result is safe HTML, not escaped again by Contao's insert tag pipeline
