# Interne Anmeldeplattform

Webanwendung, über die die Arbeitsgruppenleiter:innen eines Instituts ihre Mitarbeitenden als Betreuungspersonen für die Physikalischen Praktika anmelden. Die Anwendung erinnert automatisch an die Meldefrist und läuft auf einem institutsinternen Server.

Entwickelt im Rahmen des TPSE an der TU Darmstadt.

## Inhalt

1. Repository herunterladen
2. Anwendung starten
3. Änderungen übernehmen
4. Bibliotheken (Composer)
5. Automatisierte Tests

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

Niemand arbeitet direkt auf `main`. Jedes Issue wird in einer eigenen Branch umgesetzt und erst nach erfolgreichen Tests und einem Code Review in `main` übernommen. So bleibt `main` immer lauffähig.

### Änderungen anderer Teammitglieder herunterladen

1. GitHub Desktop öffnen.
2. Oben bei **Current branch** `main` auswählen.
3. **Fetch origin** anklicken.
4. Falls neue Änderungen vorhanden sind, **Pull origin** auswählen.
5. Falls sich `composer.json` oder `composer.lock` geändert hat: `composer install` ausführen (siehe Abschnitt 4).

### Neues Issue beginnen

1. Zuerst die aktuellen Änderungen von `main` herunterladen (siehe oben).
2. **Branch → New branch…** auswählen.
3. Als Namen die Issue-Nummer und eine kurze Beschreibung eingeben, z. B. `12-csv-export`.
4. Auf **Create branch** klicken.
5. Auf **Publish branch** klicken, damit die Branch auch auf GitHub existiert.

### Eigene Änderungen hochladen

1. Prüfen, dass oben bei **Current branch** die eigene Branch ausgewählt ist (nicht `main`).
2. Dateien in Visual Studio Code bearbeiten und speichern.
3. In GitHub Desktop die Änderungen mit einer Beschreibung versehen.
4. **Commit to [Branch-Name]** anklicken.
5. **Push origin** anklicken. Regelmäßig pushen, z. B. am Ende jeder Arbeitssitzung. Bei jedem Push laufen die automatisierten Tests.

### Pull Request erstellen (wenn das Issue fertig ist)

1. In GitHub Desktop **Branch → Create pull request** auswählen. GitHub öffnet sich im Browser.
2. Prüfen, dass oben `base: main` ← `compare: [eigene Branch]` steht.
3. Einen Titel eingeben und ggf. Beschreibung.
4. Auf **Create pull request** klicken.
6. Warten, bis unten die Tests durchgelaufen sind (✓ grün oder ✗ rot). Bei ✗ den Fehler beheben, committen und erneut pushen.
7. Rechts bei **Reviewers** den Teammitglied auswählen, der dein Code reviewen soll, und ihm Bescheid geben.

### Review und Merge (Reviewer)

1. Im Pull Request unter **Files changed** den Code anhand der Code-Review-Checkliste prüfen.
2. Wenn etwas geändert werden muss: Kommentar schreiben (bzw. Kleinigkeiten selbst ändern). Die Autorin bzw. der Autor ändert den Code in derselben Branch und pusht erneut. Der Pull Request aktualisiert sich automatisch.
3. Wenn alles passt und die Tests grün sind: **Review changes → Approve**.
4. **Merge pull request** und **Confirm merge** anklicken.
5. **Delete branch** anklicken.

### Wenn sich `main` in der Zwischenzeit geändert hat

1. In GitHub Desktop die eigene Branch auswählen.
2. **Branch → Update from main** auswählen. Damit werden die neuen Änderungen aus `main` in die eigene Branch übernommen, die eigenen Änderungen bleiben erhalten.
3. Falls GitHub Desktop Konflikte meldet (zwei Personen haben dieselben Zeilen geändert): Dateien in Visual Studio Code öffnen, entscheiden, welche Version gilt, speichern und committen.
4. **Push origin** anklicken.

## 4. Bibliotheken (Composer)

Composer installiert die PHP-Bibliotheken, die das Projekt braucht.

- `composer.json`: Liste aller Bibliotheken des Projekts.
- `composer.lock`: die genauen Versionen, die installiert werden. Dadurch haben alle Rechner, GitHub Actions und die Instituts-VM dieselben Versionen.
- `vendor/`: Ordner, in den die Bibliotheken installiert werden. Er wird nicht ins Repository hochgeladen (steht in `.gitignore`), weil ihn jeder mit `composer install` selbst erzeugen kann.

Alle Befehle werden im Projektordner ausgeführt: in GitHub Desktop **Repository → Open in Command Prompt** (macOS: **Open in Terminal**).

### Composer installieren (einmalig)

1. Installer von https://getcomposer.org/download/ herunterladen und ausführen.
2. Falls der Installer nach PHP fragt: `C:\xampp\php\php.exe` (macOS: `/Applications/XAMPP/xamppfiles/bin/php`).
3. Prüfen: `composer --version` zeigt eine Versionsnummer.

### Bibliotheken installieren

`composer install`

Nötig nach dem ersten Klonen und nach jedem Pull, bei dem sich `composer.json` oder `composer.lock` geändert hat. Ohne diesen Schritt funktionieren lokal weder die Tests noch Teile der Anwendung, die Bibliotheken verwenden.

### Neue Bibliothek hinzufügen

1. In der eigenen Branch arbeiten (siehe Abschnitt 3).
2. `composer require name/paket`, z. B. `composer require phpmailer/phpmailer`
   Bibliotheken, die nur für Tests gebraucht werden: `composer require --dev name/paket`
3. `composer.json` und `composer.lock` committen und pushen, danach wie gewohnt Pull Request.

`composer.json` nicht von Hand bearbeiten, sonst passt `composer.lock` nicht mehr dazu und die Bibliothek wird nicht installiert.

### Bibliotheken aktualisieren

1. In einer eigenen Branch `composer update` ausführen. Das installiert die neuesten Versionen, die `composer.json` erlaubt, und aktualisiert `composer.lock`.
2. `composer.lock` committen, pushen und Pull Request erstellen. Die automatisierten Tests zeigen, ob mit den neuen Versionen noch alles funktioniert.

## 5. Automatisierte Tests

Die Tests liegen im Ordner `tests/`. Sie laufen bei jedem Push und jedem Pull Request automatisch über GitHub Actions. Das Ergebnis (✓ oder ✗) steht im Pull Request und in der Spalte **Actions**. Dort sieht man bei einem Fehler auch, welcher Test fehlgeschlagen ist.

### Neuen Test schreiben

1. `tests/BeispielTest.php` kopieren und umbenennen, z. B. `tests/StichtagTest.php`.
2. Dateiname und Klassenname müssen gleich sein und auf `Test` enden.
3. Jede `public function`, deren Name mit `test` beginnt, wird automatisch ausgeführt.
4. Test zusammen mit dem Code, den er prüft, in derselben Branch committen und pushen.

### Tests lokal ausführen (optional)

Nicht nötig, weil die Tests ohnehin bei jedem Push laufen. Lokal sieht man das Ergebnis aber sofort, ohne pushen zu müssen.

1. Composer installieren und `composer install` ausführen (siehe Abschnitt 4).
2. Windows: `vendor\bin\phpunit tests`, macOS: `vendor/bin/phpunit tests`

### Workflow-Datei

Die Datei `.github/workflows/automatisierte-tests.yml` legt fest, was GitHub bei jedem Push und Pull Request ausführt. Sie betrifft die Tests aller Teammitglieder, deshalb nur nach Absprache ändern.
