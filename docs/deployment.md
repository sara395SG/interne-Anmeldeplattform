# Deployment auf der Instituts-VM

Diese Anleitung beschreibt, wie die Anwendung auf der VM des Instituts eingerichtet und aktualisiert wird.

Zugangsdaten stehen nicht im öffentlichen Repository und werden vom Auftraggeber vergeben. In den Befehlen müssen daher die Platzhalter `VM-IP`, `BENUTZERNAME` und `INSTITUTSRECHNER` durch die echten Werte ersetzt werden.

## Umgebung

- Betriebssystem der VM: Linux Mint 22 (basiert auf Ubuntu 24.04)
- Webserver: Apache 2.4 mit PHP 8.3 (über das Apache-Modul `libapache2-mod-php`)
- Installationsordner: `/var/www/html/anmeldeplattform`
- Adresse im Browser: `http://VM-IP/anmeldeplattform/`
- Die VM ist nur aus dem Institutsnetz bzw. per VPN erreichbar. Aus dem übrigen TU-Netz (z. B. eduroam) und aus dem Internet ist sie nicht erreichbar.

## Verbindung zur VM

Mit VPN-Zugang verbindet man sich direkt:

```
ssh -CX BENUTZERNAME@VM-IP
```

Ohne VPN verbindet man sich zuerst mit einem Rechner im Institutsnetz und von dort mit der VM:

```
ssh -CX BENUTZERNAME@INSTITUTSRECHNER
```
```
ssh -CX BENUTZERNAME@VM-IP
```

Beim Benutzernamen wird zwischen Groß- und Kleinschreibung unterschieden. Er muss genau so geschrieben werden, wie er vergeben wurde.

Verbindung beenden mit `exit` (ohne VPN zweimal: einmal für die VM, einmal für den Institutsrechner).

Für die Anwendung im Browser ist keine SSH-Verbindung nötig. Es reicht, im Institutsnetz oder mit VPN verbunden zu sein.

## Ersteinrichtung

Nur nötig, wenn die Anwendung auf einer VM installiert wird, auf der sie noch nicht läuft. Auf der aktuellen Instituts-VM ist das bereits erledigt; dort reicht „Update der Anwendung“.

1. Mit der VM verbinden (siehe oben).

2. System aktualisieren wie unter „System aktualisieren“ beschrieben. Grund: Sicherheitsupdates, und neue Pakete lassen sich auf einem aktuellen System problemlos installieren.

3. Benötigte Software installieren:
   ```
   sudo apt install apache2 php libapache2-mod-php git
   ```
   - `apache2`: Webserver, beantwortet die Anfragen vom Browser. Startet automatisch mit der VM.
   - `php`: führt den PHP-Code aus.
   - `libapache2-mod-php`: Modul, das Apache und PHP verbindet.
   - `git`: holt den Code aus dem GitHub-Repository und wird für Updates gebraucht.

4. Prüfen, ob Apache läuft:
   ```
   curl -I http://localhost
   ```
   Die erste Zeile muss `HTTP/1.1 200 OK` sein.

5. Code in den Ordner holen, den Apache im Browser anzeigt (`/var/www/html`):
   ```
   sudo git clone https://github.com/sara395SG/interne-Anmeldeplattform.git /var/www/html/anmeldeplattform
   ```

6. Im Browser `http://VM-IP/anmeldeplattform/` öffnen. Die Startseite der Anwendung muss erscheinen.

## Update der Anwendung

So wird die VM auf den aktuellen Stand des `main`-Branches gebracht:

1. Mit der VM verbinden (siehe oben).

2. In den Installationsordner wechseln und die neue Version von GitHub holen:
   ```
   cd /var/www/html/anmeldeplattform
   ```
   ```
   sudo git pull
   ```
   `git pull` lädt nur die Änderungen seit dem letzten Update. `sudo` ist nötig, weil der Ordner dem Administrator (root) gehört. Aus demselben Grund müssen auch andere git-Befehle in diesem Ordner mit `sudo` ausgeführt werden, sonst meldet git `dubious ownership`.

3. Prüfen, ob die VM den aktuellen Stand hat:
   ```
   sudo git status
   ```
   Die Ausgabe muss enthalten:
   - `Your branch is up to date with 'origin/main'` → die VM hat denselben Stand wie `main` auf GitHub.
   - `nothing to commit, working tree clean` → auf der VM wurde nichts von Hand geändert.

   Steht dort etwas anderes (z. B. `Changes not staged for commit`), wurden Dateien direkt auf der VM bearbeitet. Dann nichts löschen, sondern im Team klären, woher die Änderungen kommen.

4. Im Browser `http://VM-IP/anmeldeplattform/` öffnen. Die Änderungen müssen sichtbar sein.

5. Verbindung beenden mit `exit`.

### Bibliotheken (Composer)

Auf der VM wird Composer im Moment nicht gebraucht. In `composer.json` steht nur PHPUnit, und zwar unter `require-dev`. Das bedeutet: nur für die Tests (lokal und in GitHub Actions), nicht für die laufende Anwendung.

Sobald die Anwendung selbst eine Bibliothek verwendet (z. B. PHPMailer für die Erinnerungsmails), steht diese unter `require` und muss auch auf der VM installiert werden, weil der Ordner `vendor/` nicht im Repository liegt. Dann:

- einmalig Composer installieren: `sudo apt install composer`
- nach jedem Update, bei dem in der Ausgabe von `git pull` die Datei `composer.json` oder `composer.lock` auftaucht, im Installationsordner ausführen:
  ```
  sudo composer install --no-dev
  ```
  `--no-dev` installiert nur die Bibliotheken unter `require` und lässt die Test-Bibliotheken weg. Composer warnt, dass es als root läuft, und fragt `Continue as root/super user [yes]?`. Mit Enter bestätigen.

## System aktualisieren

Bei der Ersteinrichtung und danach ab und zu (z. B. einmal im Monat), damit die VM Sicherheitsupdates bekommt.

1. Updates installieren:
   ```
   sudo apt update
   ```
   ```
   sudo apt upgrade
   ```
   `apt update` lädt die Liste der verfügbaren Versionen, `apt upgrade` installiert die Updates.

2. Prüfen, ob ein Neustart nötig ist. Manche Updates (z. B. ein neuer Linux-Kernel) wirken erst nach einem Neustart. In dem Fall legt das System automatisch die Datei `/var/run/reboot-required` an. Ihren Inhalt anzeigen:
   ```
   cat /var/run/reboot-required
   ```
   - Ausgabe `*** System restart required ***` → Neustart nötig: `sudo reboot`. Die SSH-Verbindung bricht dabei ab. Etwa eine Minute warten und neu verbinden. Apache startet von selbst wieder.
   - Ausgabe `No such file or directory` → kein Neustart nötig.

## Hinweise für die Entwicklung

- Keine Passwörter oder Zugangsdaten ins Repository einchecken, da es öffentlich ist.
- Alles im Installationsordner ist über den Browser abrufbar. Dateien mit Zugangsdaten oder echten Daten (z. B. eine Konfigurationsdatei mit dem Datenbank-Passwort) deshalb außerhalb von `/var/www/html` ablegen oder als `.php`-Datei, die nichts ausgibt.
- Pfade zu CSS-, JS- und anderen Dateien im Code relativ angeben (z. B. `frontend/css/style.css`, nicht `/frontend/css/style.css`), weil die Anwendung in einem Unterordner liegt.
