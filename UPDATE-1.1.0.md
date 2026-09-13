# Raumübersicht 1.1.0 · Build 6

Diese Bibliothek bleibt unabhängig von der Raumkachel. Autor: CMelektro.

ZIP entpacken und den **Inhalt** von `Raumuebersicht` in das bestehende Repository der Übersicht hochladen. `library.json` und `RoomOverview` liegen direkt auf der obersten Ebene. In Symcon aktualisieren, **1.1.0 / Build 6** prüfen, vorhandene Instanzen öffnen und Änderungen übernehmen. Anschließend Visu neu laden. Instanzen nicht löschen.

Unter **Raumtemperatur** die Option **Isttemperatur in der Übersicht anzeigen** aktivieren und die Temperaturvariable in °C auswählen. Üblicherweise dieselbe Variable wie in der Raumkachel. Standardmäßig ist die neue Anzeige aus; sie kann unabhängig von der Raumkachel deaktiviert werden.

Die Anzeige zeigt nur die Isttemperatur. Fehlende oder ungeeignete Werte werden als unbekannt gekennzeichnet. Es gibt keine Temperaturbedienung und keinerlei Schreibzugriff auf Statusvariablen. Ein Klick auf die Kachel öffnet weiterhin das konfigurierte Ziel, wahlweise Rauminstanz oder Kategorie. Raumtyp, eigener Titel und Titel-Abschaltung bleiben erhalten.

Alle Raumgrafiken entsprechen der Raumkachel 2.3.0. Materialflächen und Konturen wurden überarbeitet und im Browser durchgesehen. Die HTML-Anbindung für Symcon 9.0 und alle bisherigen Kennungen und Konfigurationsfelder bleiben unverändert.

Lokal geprüft: 30 PHP-/Konfigurationsprüfungen mit simulierter Symcon-API, 77 bestehende Browserprüfungen sowie zusätzliche Klimaprüfungen gemeinsam mit der Raumkachel. Alle 14 Motive mit aktivierter Temperaturanzeige in vier Übersichtsgrößen geprüft. Keine Live-Prüfung auf einer SymBox erfolgt.
