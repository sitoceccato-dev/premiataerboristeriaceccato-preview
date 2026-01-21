# 🌿 Erboristeria Ceccato - Homepage CMS

Homepage dinamica con gestione contenuti da database + Pannello Admin.

## 📁 File inclusi

```
erboristeria-home-cms/
├── index.php           ← Homepage (layout identico all'HTML)
├── config.php          ← Configurazione database
├── database.sql        ← Tabelle e dati iniziali
├── README.md           ← Questo file
└── admin/
    ├── index.php       ← Login admin
    ├── dashboard.php   ← Dashboard
    ├── prodotti.php    ← Gestione prodotti
    ├── categorie.php   ← Gestione categorie
    ├── slider.php      ← Gestione slider
    ├── fornitori.php   ← Gestione fornitori
    ├── impostazioni.php← Impostazioni
    └── logout.php      ← Logout
```

## 🚀 Installazione (5 minuti)

### 1. Crea il database

Accedi a **phpMyAdmin** (o simile) e:

1. Crea un nuovo database chiamato `erboristeria_db`
2. Importa il file `database.sql`

Oppure da terminale:
```bash
mysql -u root -p < database.sql
```

### 2. Configura la connessione

Apri `config.php` e modifica:

```php
define('DB_HOST', 'localhost');      // Host database
define('DB_NAME', 'erboristeria_db'); // Nome database
define('DB_USER', 'root');            // Utente
define('DB_PASS', '');                // Password
```

### 3. Carica i file

Carica tutti i file nella cartella del tuo sito via FTP o GitHub.

### 4. Fatto! ✅

Apri `index.php` nel browser.

---

## 🔐 Pannello Admin

Accedi a: `tuosito.it/admin/`

**Credenziali default:**
- Username: `admin`
- Password: `erboristeria2026`

⚠️ **Cambia la password** modificando `admin/index.php`

### Cosa puoi fare:
- ➕ Aggiungere/modificare/eliminare **prodotti**
- 📁 Gestire **categorie**
- 🖼️ Modificare lo **slider**
- 🏭 Gestire i **fornitori**

---

## 📝 Come modificare i contenuti

### Prodotti

Nel database, tabella `prodotti`:

| Campo | Descrizione |
|-------|-------------|
| nome | Nome prodotto |
| descrizione | Descrizione breve |
| prezzo | Prezzo attuale |
| prezzo_originale | Prezzo barrato (opzionale) |
| icona | Emoji prodotto (🌼, 💎, ecc.) |
| badge | "OFFERTA", "NUOVO" o NULL |
| in_evidenza | TRUE per mostrarlo in homepage |

### Slider

Tabella `slider`:
- titolo, sottotitolo
- colore_bg (es: `linear-gradient(135deg, #4a7c23 0%, #8fbc3b 100%)`)

### Fornitori

Tabella `fornitori`:
- nome, icona

---

## 🎨 Come modificare il layout

Apri `index.php` e modifica:
- **HTML**: struttura pagina
- **CSS**: stili (nel tag `<style>`)
- **JavaScript**: funzionalità (nel tag `<script>`)

Il layout è **identico** alla versione HTML originale.

---

## 🔧 Requisiti

- PHP 7.4+
- MySQL 5.7+
- PDO extension

---

## 📞 Supporto

Per problemi: controlla che i dati in `config.php` siano corretti.
