# Deployment auf der Instituts-VM

Diese Anleitung beschreibt, wie die Anwendung auf der VM des Instituts eingerichtet und aktualisiert wird.

Zugangsdaten stehen nicht im öffentlichen Repository und werden vom Auftraggeber vergeben. In den Befehlen müssen daher die Platzhalter `VM-IP`, `BENUTZERNAME`, `INSTITUTSRECHNER` durch die echten Werte ersetzt werden.

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

Ohne VPN verbindet man sich zuerst per SSH mit einem Rechner im Institutsnetz und von dort mit der VM:

```
ssh -CX BENUTZERNAME@INSTITUTSRECHNER
ssh -CX BENUTZERNAME@VM-IP
```

Beim Benutzernamen wird zwischen Groß- und Kleinschreibung unterschieden. Er muss genau so geschrieben werden, wie er vergeben wurde.

Für die Anwendung im Browser ist keine SSH-Verbindung nötig. Es reicht, im Institutsnetz oder mit VPN verbunden zu sein.

## Update der Installation

So wird die VM auf den aktuellen Stand des `main`-Branches gebracht:

1. Mit der VM verbinden (siehe oben).
2. Neue Version von GitHub holen:
   
   ```
   cd /var/www/html/anmeldeplattform
   sudo git pull
   ```
   
   `git pull` lädt nur die Änderungen seit dem letzten Update. `sudo` ist nötig, weil der Ordner dem Administrator (root) gehört. Aus demselben Grund müssen auch andere git-Befehle in diesem Ordner mit `sudo` ausgeführt werden, sonst meldet git `dubious ownership`.
4. Prüfen, ob die VM den aktuellen Stand hat:
   
   ```
   sudo git log -1 --oneline
   ```
   
   Der angezeigte Commit muss mit dem neuesten Commit von `main` auf GitHub übereinstimmen.
6. Prüfen, ob die Anwendung läuft: Im Institutsnetz oder mit VPN im Browser `http://VM-IP/anmeldeplattform/` öffnen. Die Änderungen müssen sichtbar sein.
7. Verbindung beenden mit `exit`.

Die Pakete in `composer.json` stehen unter `require-dev` und werden nur für die Tests in GitHub Actions gebraucht, nicht auf der VM. Falls künftig Pakete unter `require` stehen, muss auf der VM zusätzlich `composer install --no-dev` ausgeführt werden.

Sobald die Datenbank eingerichtet ist, werden hier die Schritte für Änderungen am Datenbankschema ergänzt.

1. System aktualisieren:
   ```
   sudo apt update
   sudo apt upgrade
   ```
   `apt update` lädt die Liste der verfügbaren Versionen, `apt upgrade` installiert die Updates. Grund: Sicherheitsupdates, und neue Pakete lassen sich auf einem aktuellen System problemlos installieren.

   Danach prüfen, ob ein Neustart nötig ist (z. B. nach einem Kernel-Update):
   ```
   ls /var/run/reboot-required
   ```
   Falls die Datei existiert: `sudo reboot`, kurz warten und neu verbinden.

2. Benötigte Software installieren:
   ```
   sudo apt install apache2 php libapache2-mod-php git
   ```
   - `apache2`: Webserver, beantwortet die Anfragen vom Browser. Startet automatisch mit der VM.
   - `php`: führt den PHP-Code aus.
   - `libapache2-mod-php`: Modul, das Apache und PHP verbindet.
   - `git`: holt den Code aus dem GitHub-Repository und wird für Updates gebraucht.

3. Prüfen, ob Apache läuft:
   ```
   curl -I http://localhost
   ```
   Die erste Zeile muss `HTTP/1.1 200 OK` sein. Außerdem mit `sudo ufw status` prüfen, ob die Firewall der VM aktiv ist. Auf der Instituts-VM ist sie inaktiv, der Schutz nach außen kommt von der Firewall des Instituts.

4. Code in den Ordner holen, den Apache im Browser anzeigt (`/var/www/html`):
   ```
   sudo git clone https://github.com/sara395SG/interne-Anmeldeplattform.git /var/www/html/anmeldeplattform
   ```

5. Prüfen wie in Schritt 3 von „Update der Installation“.

## Hinweise für die Entwicklung

- Keine Passwörter oder Zugangsdaten ins Repository einchecken, da es öffentlich ist.
- Pfade zu CSS-, JS- und anderen Dateien im Code relativ angeben (z. B. `frontend/css/style.css`, nicht `/frontend/css/style.css`), weil die Anwendung in einem Unterordner liegt.
