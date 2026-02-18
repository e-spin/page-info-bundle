# Contao page info (EN)

With the extension you can display more information about the page in the tree structure of the page view.
Which information is displayed is selected via a pull-down in the header area.

The information includes e.g. the page ID, alias, title, description, CSS, searchable, layout etc.

From version 2, various details are also displayed for the articles.

# Contao Seiten- und Artikel-Info (DE)

Mit der Erweiterung kann man in der Baumstruktur der Seitenansicht weitere Informationen zur Seite anzeigen.
Welche Informationen eingeblendet werden, wird über ein Pull-Down im Kopfbereich ausgewählt.

Zu den Informationen gehören z.B. die Seiten-ID, Alias, Titel, Beschreibung, CSS, Suchbar, Layout usw.

Bei dem Layout kann wahlweise das verwendete Layout eingeblendet werden oder nur bei denjenigen Seiten, wo
ein Layout definiert ist.

Die Erweiterung unterstützt die Erweiterung [changelanguage](https://github.com/terminal42/contao-changelanguage)
und zeigt bei übersetzten Seiten den Titel der Stammseite an - eine gute Hilfe, sofern man die übersetzten
Seitentitel nicht lesen kann.

Ab Version 2 werden auch bei den Artikeln verschiedene Angaben ausgegeben.

## Installation

* Version ^2.1 -> Contao 5.7
* Version ^2.0 -> Contao 4.13 bis 5.6

Im Contao-Manager nach `e-spin/page-info-bundle` suchen und installieren

## Tipps

Mit der Anzeige der Titel und Beschreibungen hat man einen schnellen Überblick, bei welchen Seiten für SEO
noch nachgearbeitet werden sollte.

Sucht man eine bestimmte Seite per Alias oder ID mit der Contao-Filterung, sieht man nur die eine Seite - hier
fehlt oft die Orientierung, in welcher Hierarchie die Seite sich befindet - Alternative: den gesamten Seitenbaum
öffnen, Alias bei der Seiteninfo auswählen und über die Suche des Browsers (Strg+f) nach dem Alias suchen. Damit
sieht man gut, wo die Seite sich befindet.

![Screenshot backend](https://github.com/e-spin/page-info-bundle/blob/master/doc/screenshot_01.png?raw=true "Screenshot backend")

Ab Version 2.1 (Contao ^5.7) kann die Reihenfolge des Panels per DCA geändert werden. Um an die letzte Stelle zu
kommen, DCA wie folgt anpassen:

```php
// tl_page.php
$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['panelLayout'] .= ',page_info';
```

bzw.
```php
// tl_article.php
$GLOBALS['TL_DCA']['tl_article']['list']['sorting']['panelLayout'] .= ',article_info';
```

Das Auto-Submit kann auch deaktiviert werden:

```php
// config.php
$GLOBALS['PAGE_AUTO_SUBMIT']    = false;
$GLOBALS['ARTICLE_AUTO_SUBMIT'] = false;
```
