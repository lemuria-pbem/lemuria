# Lemuria

Lemuria ist ein „Play-by-eMail", wird also über E-Mail gespielt, und ist
inspiriert durch Eressea und Fantasya.

## Aufbau

Der Programmcode ist in Bibliotheken aufgeteilt. Diese Bibliothek beinhaltet die
gemeinsame Basis für alle anderen Bibliotheken:

- _Singleton_-Klasse zur Repräsentation fester Spielelemente wie Gebäudearten,
Gegenstände, Landschaftstypen, Rassen, Schiffstypen und Talente, sowie damit
erstellbare _ItemSet_-Sammlungen
- _Entity_-Klasse zur Repräsentation von Spielparteien und individuellen
Spielerobjekten wie Einheiten, Gebäude, Regionen und Schiffe (diese haben eine
global eindeutige ID), sowie damit erstellbare _EntitySet_-Sammlungen
- _Catalog_-Definition als speicherbare Sammlung der Spielerobjekte und ihres
Inventars
- Definitionen von _Calendar_ und _World_ zur Abbildung von Spielzügen in einer
veränderlichen Spielwelt in einem Koordinatensystem

Lemuria unterstützt somit unterschiedliche Implementierungen von Spielwelten und
Spielregeln, die sich an den Prinzipien der Vorbilder wie Eressea und Fantasya
orientieren. Dabei ist die Form der Befehlsabgabe oder der Aufbau von
Spielbefehlen nicht vorgegeben.

### Exceptions

Lemuria folgt der Unterscheidung von Logikfehlern und Laufzeitfehlern aus PHP.

#### LemuriaException

Wenn ein nicht vorhersehbarer Implementierungsfehler auftritt, wird eine
_LemuriaException_ geworfen. Dies bedeutet normalerweise das Vorhandensein eines
Programmierfehlers.

#### RuntimeException

Davon zu unterscheiden sind Fehler, die von PHP oder Fremdcode verursacht werden
und deren Ursache nicht im Code von Lemuria liegt. In diesem Fall wird eine
_RuntimeException_ geworfen.

### Logging

Lemuria implementiert PSR-3-kompatibles Logging mittels der _Monolog_-
Bibliothek.
