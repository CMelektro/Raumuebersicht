# Kompatibilitätskorrektur: Raumkachel 2.4.1 / Raumübersicht 1.2.1

## Bestätigter Fehler im ausgelieferten Code
Beide Vorgängerversionen riefen bedingungslos SetVisualizationType(2) auf, obwohl als Mindestversion 9.0 angegeben war. Die aktuell abgerufene offizielle Dokumentation nennt für Typ 2 ausdrücklich 9.1. Der bisherige Test-Dummy akzeptierte jeden Wert und konnte diesen Kompatibilitätsfehler nicht erkennen. Die frühere Aussage „Typ 2 seit 9.0“ war falsch.

## Korrektur
Ohne die vom Server bereitgestellte Konstante INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN verwenden Create und ApplyChanges den klassischen HTML-Typ 1. Nur bei vorhandener Konstante wird Vollbild registriert. Es wird keine Konstante im Produktcode nachträglich definiert.

Ohne HTML-Vollbildunterstützung löst die Übersicht eine gewählte Instanz zur nächsten übergeordneten Kategorie auf. Unzulässige Ziele, fehlende Kategorie und zyklische Elternketten werden abgefangen. Die gespeicherte Zielzuordnung wird nicht verändert. In Symcon 9.0 muss die Raumkachel innerhalb ihrer sichtbaren Raumkategorie liegen. Die Freigaben der tatsächlichen Visu können hier nicht überprüft werden.

## Prüfung dieses Updates
- 409 Raumkachel- und 39 Übersicht-PHP-Prüfungen bestanden. Die API-Simulation lehnt Typ 2 ohne passende SDK-Unterstützung jetzt ausdrücklich ab.
- Neue und bestehende Instanzen, Umschaltung bei vorhandener Vollbild-Konstante, Kategorien, verknüpfte und verschachtelte Instanzen, Wurzelobjekte, zyklische Elternketten und ungültige Ziele geprüft.
- 21 gezielte Browserprüfungen der vom PHP-Modul erzeugten HTML-Ausgabe: eingebettete Bilder dekodieren, wiederholter Ansichtswechsel ohne JavaScript-Fehler, Klick übergibt Kategorie 500 statt Instanz 600 an openObject.
- HTML-Ausgaben etwa 93 KB und 108 KB; ZIP-Integrität, Modulkennungen und enthaltene Grafikdateien geprüft.

## Grenzen
Die PHP-Schnittstelle und openObject sind lokal simuliert; das ist kein Test auf einer echten SymBox. Ein abschließender Anlagentest ist weiterhin nötig. Der native Vergrößerungspfeil bleibt bestehen. Unter Symcon 9.0 wird dessen HTML-Vollbildansicht nicht unterstützt; der Pfeil wurde nicht durch einen Eingriff in den Symcon-Client verdeckt. Die normale Raumkachel und die Navigation über Kategorien verwenden die für 9.0 dokumentierten Funktionen.

Die Raumgrafiken, Wegelicht, Klimafunktionen und unabhängigen Titel entsprechen der letzten Gestaltung und wurden für diese Fehlerkorrektur nicht neu entworfen. Es handelt sich um illustrative Lichtdarstellungen, keine physikalische Lichtsimulation.

Quellen:
https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/module/setvisualizationtype/
https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/konstanten/
https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/html-sdk/openobject/
