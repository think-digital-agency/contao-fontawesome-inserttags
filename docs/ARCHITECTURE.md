# ARCHITECTURE.md – ContaoFontAwesomeInsertTagsBundle

## Directory Tree

```
packages/contao-fontawesome-inserttags/
├── composer.json
├── src/
│   ├── ContaoFontAwesomeInsertTagsBundle.php   # Bundle entry point
│   ├── ContaoManager/
│   │   └── Plugin.php                          # BundlePluginInterface — registers bundle after ContaoCoreBundle
│   ├── DependencyInjection/
│   │   └── ContaoFontAwesomeInsertTagsExtension.php  # DI extension, loads services.yaml
│   ├── InsertTag/
│   │   └── FontAwesomeInsertTags.php           # Insert tag resolver — all logic lives here
│   └── Resources/config/
│       └── services.yaml                       # Autowired service scan for ThinkDigital\ContaoFontAwesomeInsertTags\
```

---

## Symfony Service Graph

| Service | Class | Dependencies |
|---|---|---|
| `FontAwesomeInsertTags` | `InsertTag\FontAwesomeInsertTags` | — (stateless, no constructor) |

The class is registered via `#[AsInsertTag('fa')]`, `#[AsInsertTag('fas')]`, `#[AsInsertTag('far')]`, `#[AsInsertTag('fab')]` PHP attributes. Contao autodiscovery picks them up via `autoconfigure: true` in `services.yaml` — no manual service definition needed.

---

## Insert Tag Contract

**Input:** `ResolvedInsertTag` — all parameters already resolved (nested insert tags expanded before `__invoke` is called, because the class implements `InsertTagResolverNestedResolvedInterface`).

| Parameter index | Meaning | Example |
|---|---|---|
| 0 | Icon name (required) | `rocket` |
| 1 … N | Modifier class (optional, repeatable) | `spin`, `2x`, `lg`, `fw` |

**Output:** `InsertTagResult` with `OutputType::html` — Contao treats the result as trusted HTML and does not escape it again.

**Generated HTML:**

```html
<i class="fas fa-rocket fa-spin fa-2x" aria-hidden="true"></i>
```

Class assembly:
1. Tag name (`fas` / `far` / `fab` / `fa`) → first class
2. `fa-{icon}` → second class
3. Each modifier: if it starts with `fa-` → used as-is; otherwise `fa-` is prepended

---

## Validation

Both icon name and modifiers are validated against `^[a-z0-9-]+$` (lowercase letters, digits, hyphens only). Invalid values are silently skipped; an invalid icon name returns an empty string with `OutputType::text`. The class attribute is escaped with `htmlspecialchars(ENT_QUOTES | ENT_SUBSTITUTE)` before being placed in the HTML.

---

## Routes

None — this bundle has no backend routes, no controllers, no frontend listeners.

---

## What this bundle does NOT include

- FontAwesome CSS or webfont files — must be provided separately (CDN, self-hosted, or via theme)
- Any frontend event listener or Contao hook
- Any backend UI
