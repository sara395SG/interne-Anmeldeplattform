# Build- und Startanleitung

## Voraussetzungen

- [XAMPP](https://www.apachefriends.org/de/index.html) mit Apache und PHP
- [GitHub Desktop](https://desktop.github.com/)
- [Visual Studio Code](https://code.visualstudio.com/)

## 1. Repository herunterladen

1. GitHub Desktop öffnen.
2. **File → Clone repository…** auswählen.
3. Das Repository `interne-Anmeldeplattform` auswählen.
4. Als lokalen Speicherort den Ordner `C:\xampp\htdocs\TPSE_Webanwendung\interne-Anmeldeplattform` festlegen
   (macOS: /Applications/XAMPP/xamppfiles/htdocs/TPSE_Webanwendung/interne-Anmeldeplattform).
6. Auf **Clone** klicken.

## 2. Anwendung starten

1. XAMPP Control Panel öffnen.
2. Bei **Apache** auf **Start** klicken.
3. Visual Studio Code öffnen.
4. Den Projektordner `interne-Anmeldeplattform` öffnen.
5. Im Browser folgende Adresse aufrufen:

   `http://localhost/TPSE_Webanwendung/interne-Anmeldeplattform/`

Die Anwendung wird über den lokalen Apache-Webserver ausgeführt.

**Hinweis:** Für die aktuelle Minimalversion ist kein separater Build-Schritt erforderlich. MySQL/MariaDB wird erst benötigt, wenn die Datenbankfunktionen verwendet werden.

## 3. Änderungen übernehmen

Um Änderungen anderer Teammitglieder herunterzuladen:

1. GitHub Desktop öffnen.
2. **Fetch origin** anklicken.
3. Falls neue Änderungen vorhanden sind, **Pull origin** auswählen.

Um eigene Änderungen hochzuladen:

1. Dateien in Visual Studio Code bearbeiten und speichern.
2. GitHub Desktop öffnen.
3. Änderungen mit einer Beschreibung versehen.
4. **Commit to main** anklicken.
5. **Push origin** anklicken.
