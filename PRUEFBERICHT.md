# Prüfbericht Raumübersicht 1.0.0

- 16 Backend-Prüfungen bestanden: Konfiguration, Instanzname, Raumauswahl, Statusauswertung, Live-Aktualisierung, Zielkategorie, fehlende Variablen, Rollladenskalierung und Umkehrung, sichere HTML-Einbettung sowie gesperrte Bedienbefehle.
- 77 Prüfungen in Chromium bestanden: 14 Raummotive in vier Kachelgrößen, Statusdarstellung, Navigation mit Maus, Enter und Leertaste, ungültige Zielkategorie sowie vollständige Lesefunktion ohne Bedienelemente.
- HTML-Ausgabe: rund 42 KB und damit deutlich unter 1 MB.
- Vorschau für Küche, Wohnzimmer und Durchgangsflur visuell geprüft.

Die Tests verwenden eine nachgebildete Symcon-API und senden keine KNX-Befehle. Die Navigation zur tatsächlichen Kategorie muss abschließend auf der SymBox geprüft werden.
