# Raumübersicht 1.2.0 · Build 7

Neue Raumillustrationen wie in Raumkachel 2.4.0, zusätzliche Leuchtenart Wegelicht, unabhängiger Titel und korrigierte Registrierung für maximierte HTML-Kacheln. Autor: CMelektro.

## Update

Den Inhalt des entpackten Ordners `Raumuebersicht` in das bestehende Übersicht-Repository hochladen. `library.json` und `RoomOverview` liegen direkt auf der obersten Ebene; den neuen Ordner `RoomOverview/assets/rooms` vollständig übertragen. Auch die zugehörige Raumkachel auf **2.4.0 / Build 17** aktualisieren.

In Symcon beide Bibliotheken aktualisieren, bestehende Instanzen öffnen und Änderungen übernehmen. Danach die Visu vollständig neu öffnen. Instanzen und Verknüpfungen behalten; Kennungen sind unverändert.

## Konfiguration

- Unter Allgemein den eigenen Titel einstellen. Dieser verändert den Instanznamen nicht mehr und lässt sich ausblenden.
- Das passende Raummotiv auswählen.
- Licht-, Schaltkreis- und Rollladenstatus nach Bedarf aktivieren und verknüpfen. Die Darstellung ist ausschließlich eine Anzeige.
- Die Isttemperatur optional aktivieren und mit der Temperaturvariable in °C verbinden; keine Sollwertbedienung.
- Als Ziel die gewünschte Raumkachel-Instanz oder Raumkategorie auswählen. Das Ziel muss für den verwendeten Visu-Benutzer erreichbar sein.

## Navigation und Vergrößerung

Laut [Symcon-Dokumentation](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/html-sdk/openobject/) öffnet `openObject` Kategorien als Seite und andere Objekte als maximierte Kachel. Beide Module registrieren sich jetzt mit dem [für normale und maximierte HTML-Kacheln vorgesehenen Wert 2](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/module/setvisualizationtype/), dokumentiert seit Symcon 9.0.

Die frühere Registrierung mit Wert 1 war für diese Navigation unvollständig. Die Aussage „Wert 2 erst ab 9.1“ war falsch; die benannten Konstanten werden erst ab 9.1 dokumentiert. Der numerische Wert wird deshalb direkt verwendet.

Zusätzlich wurden globale JavaScript-Namenskonflikte beim erneuten Laden beseitigt. Gemeldete Navigationsfehler werden sichtbar angezeigt und Doppelklicks kurz entprellt. Der äußere Symcon-Vergrößerungspfeil wird nicht als entfernt behauptet; seine Zielansicht ist nun korrekt registriert.

Kein Schreibzugriff auf Licht-, Rollladen- oder Temperaturvariablen aus dieser Übersicht. Der echte Navigations-/Vollbildtest auf der SymBox bleibt erforderlich; lokale Browsertests können den Symcon-Client und dessen Berechtigungen nicht ersetzen.
