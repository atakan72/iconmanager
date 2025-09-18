# Icon Manager

DSGVO-konformes WordPress Icon Management Plugin: Lokaler Download & Verwaltung von Brand (Social) und UI (Lucide) SVG Icons.

## Änderungen gegenüber Vorgänger
- Neuer Plugin-Slug & Namespace: `iconmanager_*`
- Upload-Verzeichnis jetzt: `wp-content/uploads/iconmanager-icons/`
- Alter Präfix (`iconmgmt_dsgvo_`) weiterhin über Legacy-Aliases unterstützt (Deprecated Warnung in Logs)

## Features
- Batch & Einzel-Download (AJAX) für Brand + UI Icons
- Shortcode `[icon name="facebook" type="brand" size="24"]`
- Template-Funktion `iconmanager_render_icon()` und Echo-Helfer `iconmanager_icon()`
- Auto-Download fehlender Icons bei Admin-Seitenaufruf
- Caching (Object Cache) für fertige Render-Ausgaben (7 Tage)
- Security & Cache Header für Icon-Auslieferung
- Optionale Medien-Import Funktion (Icons in die WP Mediathek ziehen für Gutenberg / Auswahl in Blöcken)
- Integriertes Hilfe-Panel (Button "Hilfe / Anleitung") mit Speicherort & Nutzungshinweisen
- Lizenz-Panel im Backend (Button "Lizenzen") inkl. kopierbarem Credit-Snippet

## Installation
1. Ordner `iconmanager` nach `wp-content/plugins/` hochladen.
2. Plugin aktivieren.
3. Im Admin-Menü unter "Icons" Icons laden.
4. (Optional) Altes Plugin `ICONMANAGEMENT.DSGVO` deaktivieren und löschen, sobald keine Legacy-Funktionen mehr genutzt werden.

## Migration vom alten Plugin
| Alt (ICONMANAGEMENT.DSGVO) | Neu (Icon Manager) |
|---------------------------|--------------------|
| `iconmgmt_dsgvo_render_icon` | `iconmanager_render_icon` |
| Upload Pfad: `iconmanagement-dsgvo-icons` | `iconmanager-icons` |
| Textdomain: `iconmanagement-dsgvo` | `iconmanager` |

Bestehende Seiten mit Shortcode `[icon ...]` funktionieren unverändert.

## Nutzung
```php
echo iconmanager_render_icon('facebook','brand',24);
iconmanager_icon('menu','ui',32,'#6FA29D',['class'=>'nav-icon']);
```
Shortcode Beispiel:
```
[icon name="menu" type="ui" size="32" class="w-8 h-8" color="#6FA29D"]
```

### Medienbibliothek Import
Standard: Icons werden direkt aus `uploads/iconmanager-icons` genutzt (performant & update-sicher). Ein Import in die Medienbibliothek ist nur nötig, wenn Redakteure Icons wie normale Medien auswählen sollen (z.B. im Bild-Block). Nutze dazu im Hilfe-Panel:

- "Alle Icons in Mediathek importieren" (Bulk)
- Oder einzelne "Import" Buttons unter jedem Icon

Import legt SVG Dateien im aktuellen datumsbasierten Upload-Ordner ab und versieht Attachment mit Meta `_iconmanager_icon_name` (Duplicate-Check).

### Speicher-Persistenz
Icons bleiben bei Plugin-Updates unverändert erhalten (liegen außerhalb des Plugin-Ordners). Alte Ordner `iconmanagement-dsgvo-icons` werden bei Aktivierung automatisch migriert, falls Ziel-Pfade leer sind.

## DSGVO
- Icons nach erstem Download lokal ausgeliefert
- Keine externen Requests für Besucher
- Headers: Cache + Security (nosniff, frame-deny, referrer-policy)

## Entfernung alter Reste
Nach Prüfung, dass alles läuft: alten Ordner `iconmanagement-dsgvo` löschen, um Doppelcode zu vermeiden.

## Quellen & Lizenzen
Hinweis zu den Icon-Lizenzen:

- Lucide: Lizenztext muss enthalten sein. Credits sind freiwillig. (<https://lucide.dev/license>)
- Simple Icons: Meist frei nutzbar, aber Markenrechte beachten. Credits freiwillig. (<https://github.com/simple-icons/simple-icons/blob/master/DISCLAIMER.md>)

Empfohlener optionaler Credit: `Icons: Brand Icons (Simple Icons) & UI Icons (Lucide).`

## Erweiterungsideen
- Suchfeld / Filter in Admin-Liste (Basis umgesetzt)
- SVG-Upload eigener Icons
- WP-CLI: `wp iconmanager preload`

## Lizenz
GPL-2.0-or-later
