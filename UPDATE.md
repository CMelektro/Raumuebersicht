# Raumuebersicht 1.2.2: zusätzliche Raummotive

Ausschließlich die gewünschte Grafik-Erweiterung auf Basis von 1.2.1:
- Gästezimmer als neues Raummotiv.
- Automatisch platzierte LED-Leiste im vorhandenen Treppenhaus in die Wand oberhalb des Handlaufs versetzt.

Das Gästezimmer steht in Raumkachel und Raumübersicht unter Allgemein → Raummotiv zur Wahl. Bestehende Raumauswahlen und Zuordnungen bleiben erhalten. Für die neue Standardposition der Treppenhaus-LED muss deren automatische Positionierung aktiv sein; eigene manuelle Positionen bleiben erhalten.

## Installation
ZIP entpacken und den Inhalt von Raumuebersicht in das bestehende entsprechende Repository hochladen. library.json und RoomOverview müssen auf der obersten Ebene liegen. Die neue Datei unter RoomOverview/assets/rooms vollständig mit hochladen. Bibliothek in Symcon aktualisieren und Visu neu öffnen. Kein Löschen oder Neuanlegen von Instanzen notwendig.

## Prüfungen und unveränderte Funktionen
454 PHP-Prüfungen und 23 gezielte Browserprüfungen bestanden. Gästezimmer und Treppenhaus gerendert betrachtet, LED Ein/Aus/Deaktiviert sowie vier separate LED-Kreise geprüft. Alte Grafikdateien, CSS und die Skripte für Klima und Navigation sind bytegleich mit der Vorversion. Die einzige PHP-Änderung ergänzt zwei zulässige Bildnamen; Befehlsverarbeitung, Symcon-Registrierung und Variablenzuordnung sind unverändert. Vorherige Einschränkungen gelten weiterhin. Kein Test auf einer echten SymBox.

Gästezimmer mit der integrierten Bildgenerierung erzeugt: Einzelbett, Nachttisch, Gepäckbank und Schrank. Dunkler Architektur-CGI-Stil, keine eingebrannten Leuchten; Leuchten bleiben separat schaltbare Grafikelemente.
