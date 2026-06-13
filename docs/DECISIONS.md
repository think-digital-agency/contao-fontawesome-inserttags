# DECISIONS.md – Architecture Decision Records

---

## ADR-001: Vier separate Tags statt einem Tag mit Stil-Parameter

**Date:** 2026-06-14
**Status:** Accepted

**Context:**
Zwei mögliche Insert-Tag-Designs:
1. `{{fa::solid::rocket}}` — ein einziger Tag, Stil als erster Parameter
2. `{{fas::rocket}}`, `{{far::rocket}}`, `{{fab::rocket}}` — je ein Tag pro Stil

Option 1 erfordert nur eine `#[AsInsertTag]`-Registrierung, macht aber den Parameter-Index um eins komplizierter und weicht von der FontAwesome-Konvention ab.

**Decision:**
Option 2 — je ein Tag pro Stil, exakt analog zu den FontAwesome-CSS-Klassen (`fas`, `far`, `fab`). Ein vierter generischer Tag `{{fa::...}}` für v4-Kompatibilität.

**Consequences:**
- (+) Syntax entspricht direkt den FA-Klassen — keine mentale Übersetzungsschicht
- (+) Bestehende `{{fa::...}}`-Verwendungen aus FA v4-Zeiten funktionieren unverändert
- (+) Jeder Tag liefert den Stil-Klassennamen bereits über seinen Namen — keine Fallunterscheidung im Code nötig
- (-) Vier `#[AsInsertTag]`-Attribute statt einem — minimaler Mehraufwand, kein Nachteil in der Praxis

---

## ADR-002: `InsertTagResolverNestedResolvedInterface` statt `InsertTagResolverInterface`

**Date:** 2026-06-14
**Status:** Accepted

**Context:**
Contao bietet zwei Resolver-Interfaces:
- `InsertTagResolverInterface` — erhält rohe, noch nicht aufgelöste Parameter
- `InsertTagResolverNestedResolvedInterface` — erhält Parameter nach vollständiger Auflösung verschachtelter Insert Tags

**Decision:**
`InsertTagResolverNestedResolvedInterface` — Parameter kommen bereits fertig aufgelöst an.

**Consequences:**
- (+) Verschachtelte Insert Tags im Icon-Namen (theoretisch: `{{fas::{{theme::icon}}}}`) werden korrekt aufgelöst
- (+) Kein manuelles Parsen oder Re-Resolven nötig
- (-) Keine — für einen stateless String-Resolver gibt es keinen Nachteil

---

## ADR-003: `OutputType::html` statt `OutputType::text`

**Date:** 2026-06-14
**Status:** Accepted

**Context:**
`OutputType::text` würde das Resultat von Contao nochmals escapen — `<i class="...">` würde als HTML-Entitäten ausgegeben.
`OutputType::html` signalisiert, dass der Rückgabewert bereits sicheres HTML ist und nicht mehr escaped werden darf.

**Decision:**
`OutputType::html` — der Resolver erzeugt direkt valides, escaptes HTML.

**Consequences:**
- (+) Das `<i>`-Tag wird korrekt als HTML gerendert, nicht als literal String
- (+) Konsistent mit Contaos eigenem Muster für Insert Tags, die HTML ausgeben
- (-) Die Implementierung trägt Verantwortung für korrektes Escaping — wird durch `htmlspecialchars` auf dem Class-Attribut erfüllt; Icon-Name und Modifier sind zusätzlich durch Regex-Validierung gesichert

---

## ADR-004: `aria-hidden="true"` auf dem `<i>`-Element

**Date:** 2026-06-14
**Status:** Accepted

**Context:**
Dekorative Icons sollen von Screenreadern nicht vorgelesen werden. FontAwesome empfiehlt `aria-hidden="true"` auf dem `<i>`-Element. Die Alternative — kein Attribut und stattdessen dem Nutzer überlassen — führt zu inkonsistenter Barrierefreiheit.

**Decision:**
`aria-hidden="true"` wird immer ausgegeben, ohne Opt-out. Wer ein semantisch bedeutsames Icon benötigt, kombiniert es selbst mit einem `aria-label` auf dem umgebenden Element.

**Consequences:**
- (+) Konsistent barrierefreie Ausgabe ohne Konfiguration
- (+) Entspricht FontAwesomes eigener Empfehlung für dekorative Icons
- (-) Kein Opt-out über einen Parameter — wer das Icon explizit vorlesen lassen will, muss das umgebende Element annotieren; in der Praxis nicht relevant, da Icon-Insert-Tags stets dekorativ eingesetzt werden

---

## ADR-005: Regex-Validierung statt HTML-Escaping allein

**Date:** 2026-06-14
**Status:** Accepted

**Context:**
Der Icon-Name und die Modifier werden in einen HTML-Class-Attribut-Wert eingesetzt. `htmlspecialchars` allein würde XSS verhindern, aber erlaubt potenziell ungültige Zeichen im Klassennamen (Leerzeichen, Sonderzeichen).

**Decision:**
Zweistufige Validierung: erst Regex `^[a-z0-9-]+$` (lässt nur gültige FA-Klassennamen durch), dann `htmlspecialchars` auf dem zusammengesetzten Klassen-String. Ungültige Werte werden stillschweigend übersprungen; ein fehlender Icon-Name liefert einen leeren String.

**Consequences:**
- (+) Keine XSS-Angriffsfläche über manipulierte Insert-Tag-Parameter
- (+) Ausgabe enthält ausschließlich gültige CSS-Klassennamen
- (+) Kein 500-Fehler bei ungültiger Eingabe — stilles Fallback auf leeren String
- (-) Modifier mit Großbuchstaben (z. B. `FA-Spin`) werden abgewiesen — in der Praxis kein Problem, da FA-Klassen durchgehend lowercase sind
