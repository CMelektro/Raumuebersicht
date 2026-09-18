# Raumuebersicht 1.2.3 – Hauptbadezimmer

Ergänzt ausschließlich die Raumauswahl „Hauptbadezimmer“ und das passende Raummotiv. Alle vorhandenen Räume, LED-Wandposition im Treppenhaus, Bedienfunktionen, Variablenzuordnungen, Titel- und Navigationseinstellungen bleiben unverändert.

## Update
ZIP entpacken. Inhalt von Raumuebersicht in das bestehende entsprechende Repository hochladen; library.json und RoomOverview liegen direkt auf der obersten Ebene. Die neue Datei RoomOverview/assets/rooms/bathroom.jpg mit hochladen. In Symcon aktualisieren und Visu neu öffnen. Unter Allgemein → Raummotiv → Hauptbadezimmer auswählen. Bestehende Instanzen können bleiben.

Raumkachel: 2.4.3 / Build 20. Raumübersicht: 1.2.3 / Build 10. Autor CMelektro.

## Prüfung
454 lokale PHP-Prüfungen und 13 gezielte Browserprüfungen bestanden, beide Ansichten gerendert geprüft. Enthaltenes JPEG dekodiert; LED Ein/Aus/Deaktiviert und vier getrennte LED-Kreise geprüft. Dateivergleich bestätigt: PHP nur um den Bildnamen ergänzt, HTML nur um das Raum-Preset; alle bisherigen Assets und übrigen Funktionsdateien bytegleich mit Vorversion. Kein Test auf der realen SymBox.

## Motiv
Mit der integrierten Bildgenerierung erstellt. Motivvorgabe: hochwertiges dunkles Architektur-CGI eines Hauptbadezimmers mit bodengleicher Dusche, Doppelwaschtisch und freistehender Badewanne; Anthrazit und Nussbaum, ohne eingebrannte Leuchten, Beschriftung oder Personen. Leuchten bleiben interaktive Grafikelemente.
