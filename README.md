# Raumübersicht 1.0.0

Eigenständige Anzeige- und Navigationskachel für Symcon 9.0, Autor **CMelektro**.

Die Kachel zeigt das ausgewählte Raummotiv sowie den Status von bis zu vier Lichtkreisen, zwei Schaltkreisen und zwei Rollläden. Sie enthält keine Bedienung. Ein Klick auf die Kachel öffnet die konfigurierte Raumkategorie, in der beispielsweise die vorhandene bedienbare Raumkachel liegt.

## Installation

1. Dieses Paket in ein **eigenes öffentliches GitHub-Repository** hochladen. `library.json` und `RoomOverview` müssen direkt auf der obersten Ebene liegen.
2. Die Repository-Adresse in Symcon unter **Modules** hinzufügen.
3. Auf der Start- beziehungsweise Übersichtsseite für jeden Raum eine Instanz **Raumübersicht** anlegen.
4. Raumname, Raummotiv und Zielkategorie einstellen.
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

Die Zielkategorie muss in derselben Visualisierung erreichbar sein. Die Navigation verwendet die offizielle HTML-SDK-Funktion `openObject`.
