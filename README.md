# FooGallery Dynamic Style Manager

Un plugin WordPress per cambiare rapidamente il layout delle gallerie FooGallery (Free) senza toccare il codice.

## Il problema che risolve

FooGallery non offre un modo rapido per applicare lo stesso layout a più gallerie. Ogni volta bisogna aprire la galleria, navigare tra le impostazioni e riconfigurare manualmente ogni parametro. Con molte gallerie questo diventa tedioso.

## Come funziona

Il plugin aggiunge un pannello laterale nell'editor di ogni galleria FooGallery con tre slot — uno per ciascun layout supportato (Justified, Responsive, Masonry).

Il flusso di lavoro è semplice:

1. Configuri una galleria con il layout desiderato (dimensioni, margini, effetti hover, lightbox, ecc.)
2. Apri quella galleria nell'editor e clicchi **💾** nello slot corrispondente — il plugin salva tutte le impostazioni
3. Apri qualsiasi altra galleria e clicchi **Applica** sullo stesso slot — le impostazioni vengono trasferite in un clic

## Layout supportati

- **Justified** — righe di altezza uniforme, larghezza variabile
- **Responsive** — griglia a colonne fisse
- **Masonry** — disposizione a cascata

## Requisiti

- WordPress 6.0 o superiore
- FooGallery Free (testato con FooGallery 2.x)
- PHP 7.4 o superiore

## Installazione

1. Scarica il file `foogallery_dynamic_switcher.php`
2. Caricalo nella cartella `wp-content/plugins/foogallery-dynamic-switcher/`
3. Attivalo da **Plugin → Plugin installati** nel pannello WordPress

## Note tecniche

I preset vengono salvati nella tabella `wp_options` del database WordPress come opzioni globali del sito. Ogni preset occupa due voci:
- `fg_preset_template_{layout}` — il nome del template
- `fg_preset_data_{layout}` — le impostazioni serializzate

Le chiavi meta utilizzate sono `foogallery_template` e `_foogallery_settings`, che corrispondono a quelle scritte da FooGallery Free nel database.

## Limitazioni note

- I preset sono globali: un solo set di impostazioni per ciascun layout. Non è possibile salvare varianti diverse dello stesso layout
- Testato esclusivamente con FooGallery Free — il comportamento con FooGallery PRO non è verificato
- I preset vengono salvati nel sito corrente e non sono esportabili automaticamente

## Contesto di sviluppo

Sviluppato per uso interno su [quaderni.it](https://quaderni.it), un archivio di testi e fotografie. Il plugin nasce dall'esigenza pratica di gestire decine di gallerie fotografiche mantenendo coerenza visiva tra i layout.

Sviluppato con il supporto di Claude (Anthropic).

## Licenza

GPL-2.0 — vedi file LICENSE.
