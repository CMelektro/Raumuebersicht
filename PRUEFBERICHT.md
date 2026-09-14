# Prüfung · Raumkachel 2.4.0 / Raumübersicht 1.2.0

## Umfang und Ergebnis

- 407 Raumkachel-PHP-/Konfigurationsprüfungen bestanden.
- 30 Raumübersicht-PHP-/Konfigurationsprüfungen bestanden.
- 2.396 Browserprüfungen bestanden: echte vom PHP-Modul erzeugte HTML-Ausgaben, 12 Wechsel Übersicht/Raum im selben JavaScript-Dokument, 256 Aktivierungskombinationen, Klimabedienung, synchrone und asynchrone Navigationsfehler sowie alle 14 Motive.
- 594 zusätzliche Beleuchtungsprüfungen bestanden: 14 Motive × 7 Leuchtenarten mit Ein/Aus/Deaktiviert, gültigen Lichtquellenpositionen und vier getrennten Wegelicht-Kreisen.
- 13 neue Raumillustrationen erstellt, Neutral verwendet Büro; Bilder in beiden Modulen identisch und lokal enthalten. Einzelbilder für die Auslieferung etwa 25–51 KB, HTML-Ausgabe deutlich unter 1 MB.

## Sichtprüfung

Alle Motive als vollständige Übersichtskachel mit aktiven Leuchten und Rollläden gerendert und betrachtet. Zusätzlich eine Vergleichsansicht aller sieben Leuchtenarten, Wegelicht im Durchgangsflur und Treppen-LED erstellt. Lichtkegel beginnen an den Leuchtenöffnungen. Bei Wandleuchten werden getrennte Verläufe für die obere und untere Abstrahlung verwendet. Wegelicht strahlt nach unten und endet in einer kurzen Bodenaufhellung; die Einbaupunkte liegen neben den Flurtüren. Treppen-LED folgt dem Handlauf und hellt den Bereich darunter auf. Die Leuchtenkörper haben geschlossene Materialflächen.

## Ursache der leeren maximierten Ansicht

Die alte Registrierung nutzte Typ 1, also nur normale HTML-Ansicht. Laut aktueller Symcon-Dokumentation aktiviert der numerische Typ 2 normale und maximierte HTML-Ansichten seit 9.0. Beide Module verwenden jetzt diesen Wert in Create und ApplyChanges. Die vorherige Behauptung, Wert 2 erfordere 9.1, verwechselte den Wert mit den erst ab 9.1 dokumentierten Konstantennamen.

Ein zusätzlicher Fehler der alten Fassung ließ sich lokal reproduzieren: erneutes Laden im selben Dokument erzeugte doppelte globale Deklarationen. Die neue Fassung kapselt ihre Hilfsfunktionen. Der öffentliche SDK-Einstieg bleibt window.handleMessage.

## Grenzen

PHP 8.5.10 lief mit einer simulierten Symcon-API; Chromium diente als Browser. Es gab keinen Zugriff auf eine echte SymBox oder KNX-Anlage. Ein Browser-Test mit simuliertem openObject belegt nicht die Berechtigungen, Objektfreigaben oder das Verhalten des tatsächlichen Symcon-Clients. Daher bleibt ein abschließender Test von Navigation, Vollbild und Geräteaktionen auf der Anlage erforderlich.

Die Lichtdarstellung ist illustrativ, keine berechnete Lichtplanung. Reflektionen und Abschattungen durch Möbel werden nicht physikalisch simuliert. Bei sehr kleinen Raumkacheln mit vielen aktiven Kreisen bleibt vertikales Scrollen möglich.

Quellen: [SetVisualizationType](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/module/setvisualizationtype/), [openObject](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/html-sdk/openobject/).
