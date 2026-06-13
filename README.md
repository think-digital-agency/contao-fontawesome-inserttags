# Contao FontAwesome Insert Tags

[![Packagist](https://img.shields.io/packagist/v/think-digital-agency/contao-fontawesome-inserttags.svg)](https://packagist.org/packages/think-digital-agency/contao-fontawesome-inserttags)
[![Downloads](https://img.shields.io/packagist/dt/think-digital-agency/contao-fontawesome-inserttags.svg)](https://packagist.org/packages/think-digital-agency/contao-fontawesome-inserttags)
[![License](https://img.shields.io/packagist/l/think-digital-agency/contao-fontawesome-inserttags.svg)](LICENSE)

**[English]** Use FontAwesome icons anywhere in Contao 5 via insert tags — in text fields, headlines, HTML modules, navigation labels, RSCE templates, and anywhere else Contao processes insert tags.

```bash
composer require think-digital-agency/contao-fontawesome-inserttags
```

---

**FontAwesome-Icons überall in Contao.** Kein Copy-Paste von `<i>`-Tags. Kein HTML-Modul. Einfach `{{fas::rocket}}` in beliebige Felder tippen — fertig.

---

## Verwendung

```
{{fas::rocket}}                      → <i class="fas fa-rocket" aria-hidden="true"></i>
{{far::circle-check}}                → <i class="far fa-circle-check" aria-hidden="true"></i>
{{fab::github}}                      → <i class="fab fa-github" aria-hidden="true"></i>
{{fa::star}}                         → <i class="fa fa-star" aria-hidden="true"></i>

{{fas::rocket::spin}}                → <i class="fas fa-rocket fa-spin" aria-hidden="true"></i>
{{fas::rocket::spin::2x}}            → <i class="fas fa-rocket fa-spin fa-2x" aria-hidden="true"></i>
```

| Tag | FontAwesome-Stil |
|-----|-----------------|
| `{{fas::…}}` | Solid |
| `{{far::…}}` | Regular |
| `{{fab::…}}` | Brands |
| `{{fa::…}}` | Generisch (v4-Kompatibilität) |

Parameter 1 = Icon-Name, Parameter 2+ = optionale Modifier (`spin`, `2x`, `lg`, `fw`, …). Das Präfix `fa-` wird automatisch gesetzt.

---

## Features

- **Alle Contao-Felder** — Text, Überschrift, Navigation, HTML-Module, RSCE, Twig-Templates via `insert_tag_raw`-Filter
- **4 Tag-Varianten** — `{{fas::}}`, `{{far::}}`, `{{fab::}}`, `{{fa::}}`
- **Modifier-Parameter** — beliebig viele Klassen-Modifier pro Tag, automatisches `fa-`-Präfix
- **XSS-sicher** — Icon-Name und Modifier werden per Regex `^[a-z0-9-]+$` validiert und mit `htmlspecialchars` escaped
- **`aria-hidden="true"`** — Icons sind für Screenreader automatisch unsichtbar

---

## Perfekt kombiniert: Contao Design+ Theme

Diese Extension ist nativ im **[Contao Design+ Theme](https://themes.contao.org/de/index/contao-design-plus)** integriert. Design+ liefert FontAwesome 6 Free als selbst-gehostete Assets mit und nutzt die Insert Tags in Navigationen, Breadcrumbs, Kalender- und RSCE-Templates.

---

## Voraussetzungen

- PHP 8.2 oder höher
- Contao 5.3 oder höher
- FontAwesome CSS/Webfonts (selbst gehostet oder via CDN — **nicht** in diesem Bundle enthalten)

---

## Installation

```bash
composer require think-digital-agency/contao-fontawesome-inserttags
php bin/console cache:clear && php bin/console cache:warmup
```

Die Extension registriert sich automatisch über den Contao Manager Plugin. Keine weitere Konfiguration erforderlich.

**FontAwesome-Assets einbinden** (falls noch nicht vorhanden) — z. B. via CDN im Layout:

```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
```

Oder selbst gehostet über einen `generatePage`-Hook bzw. direkt im Layout-Template.

---

## Verwendung in Twig-Templates

```twig
{# Standard — OutputType::html, kein weiteres |raw nötig: #}
{{ '{{fas::arrow-right}}'|insert_tag_raw }}

{# Mit |raw (harmlos, aber redundant): #}
{{ "{{far::arrow-alt-circle-down}}"|insert_tag_raw|raw }}
```

---

## Lizenz

LGPL-3.0-or-later — siehe [LICENSE](LICENSE).

Entwickelt von [Think Digital Agency](https://think-digital.agency).
