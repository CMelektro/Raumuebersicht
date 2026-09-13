# Raumübersicht 1.0.4

Korrektur in 1.0.2: Die HTML-Darstellung verwendet den mit Symcon 9.0 kompatiblen Visualisierungstyp 1. Typ 2 steht erst ab Symcon 9.1 zur Verfügung.

Eigenständige Anzeige- und Navigationskachel für Symcon 9.0, Autor **CMelektro**.

Die Kachel zeigt das ausgewählte Raummotiv sowie den Status von bis zu vier Lichtkreisen, zwei Schaltkreisen und zwei Rollläden. Sie enthält keine Bedienung. Ein Klick öffnet die ausgewählte Raumkachel-Instanz als maximierte Kachel. Alternativ kann weiterhin eine Kategorie als Ziel verwendet werden.

## Installation

1. Dieses Paket in ein **eigenes öffentliches GitHub-Repository** hochladen. `library.json` und `RoomOverview` müssen direkt auf der obersten Ebene liegen.
2. Die Repository-Adresse in Symcon unter **Modules** hinzufügen.
3. Auf der Start- beziehungsweise Übersichtsseite für jeden Raum eine Instanz **Raumübersicht** anlegen.
4. Raumname und Raummotiv einstellen. Unter „Ziel beim Anklicken“ direkt die vorhandene Raumkachel-Instanz auswählen.
5. Die benötigten Statuskreise aktivieren und jeweils deren Rückmeldevariable auswählen.

Für Lichtkreise akzeptiert die Kachel Boolean-Rückmeldungen und numerische Dimmwerte. `false` beziehungsweise `0` gilt als aus; jeder Wert größer als `0` gilt als ein. Schaltkreise benötigen Boolean-Variablen. Rollläden benötigen eine numerische Positionsrückmeldung. Der Maximalwert kann auf `1`, `100` oder `255` eingestellt und die Richtung umgekehrt werden.

Aktivierte Kreise ohne gültige Rückmeldevariable werden als **unbekannt** gezählt. Die Kachel reagiert unmittelbar auf Änderungen der verknüpften Variablen. Sie sendet selbst keine Schalt- oder Fahrbefehle.

## Empfohlene Struktur

```text
Startseite
├── Küche              (Instanz Raumübersicht)
├── Wohnzimmer         (Instanz Raumübersicht)
└── Flur                (Instanz Raumübersicht)

Räume
├── Küche               (Kategorie mit vorhandener Raumkachel)
├── Wohnzimmer          (Kategorie mit vorhandener Raumkachel)
└── Flur                 (Kategorie mit vorhandener Raumkachel)
```

Das Ziel muss in derselben Visualisierung erreichbar sein. Die Navigation verwendet die offizielle HTML-SDK-Funktion `openObject`.

## Update von 1.0.2

Den Inhalt dieses Ordners in das bestehende Raumübersicht-Repository hochladen und die Bibliothek in Symcon auf **1.0.3 / Build 4** aktualisieren. Danach die Übersicht öffnen, unter Allgemein die gewünschte Raumkachel-Instanz als Ziel auswählen und übernehmen. Bestehende Statusvariablen bleiben erhalten.

Navigation: https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/html-sdk/openobject/

## Version 1.0.4 / Build 5

Unter Allgemein lässt sich die eigene Überschrift jetzt über „Titel anzeigen“ deaktivieren. Der Raumname im Objektbaum und der äußere Symcon-Titel bleiben unverändert. Grafik, Status und Navigation funktionieren auch mit deaktivierter Überschrift. Zum Update den entpackten Inhalt in das bestehende Raumübersicht-Repository hochladen, in Symcon aktualisieren und die Einstellungen übernehmen.
