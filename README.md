# GuessTheNum (Web)

! Projekt vznikol s cieľom overenia schopnosti integrácie LLM nástroja Claude Code v prostredí Githubu.

Jednoduchá webová hra **Hádaj číslo**, postavená na čistom PHP (bez frameworkov
a bez databázy) ako osobný projekt na učenie sa server-side skriptovania
a spracovania HTTP requestov.

## Ako sa hrá

1. Vyber si jednu zo štyroch obtiažností.
2. Server si vygeneruje tajné číslo v danom rozsahu.
3. Hádaj číslo — po každom pokuse ti hra povie, či má byť tvoj ďalší tip
   vyšší alebo nižší.
4. Cieľom je uhádnuť číslo na čo najmenej pokusov, kým ti nedôjde limit.
5. Tvoje najlepšie skóre (najmenší počet pokusov na výhru) sa pre každú
   obtiažnosť ukladá do cookie v prehliadači, takže vydrží aj medzi
   návštevami.

## Obtiažnosti

| Obtiažnosť | Rozsah    | Max. pokusov |
|------------|-----------|---------------|
| Ľahká      | 1 – 50    | 8             |
| Stredná    | 1 – 100   | 10            |
| Ťažká      | 1 – 500   | 12            |
| Expert     | 1 – 1000  | 15            |

## Funkcie

- Moderné, responzívne UI (funguje na mobile aj desktope).
- Výber obtiažnosti s prehľadom vlastného rekordu.
- Počítadlo pokusov a progres bar.
- História doterajších tipov s vyznačením smeru ("vyššie" / "nižšie").
- Ukladanie najlepšieho skóre naprieč sedeniami (cookie, per obtiažnosť).
- Stav hry (aktívna hra, výhra, prehra) uložený v PHP session — obnovenie
  stránky (F5) neopakuje posledný odoslaný tip vďaka Post/Redirect/Get vzoru.

## Štruktúra projektu

```
index.php        # vykresľuje UI podľa aktuálneho stavu hry
game.php         # herná logika, spracovanie POST akcií (start/guess/reset)
config.php       # definícia obtiažností + práca s cookie najlepšieho skóre
assets/style.css # štýly (bez závislosti na externých frameworkoch)
target.png       # ilustrácia na úvodnej obrazovke
```

## Spustenie

Vyžaduje nainštalované PHP (8.0+).

```bash
php -S localhost:8000
```

Potom otvor v prehliadači <http://localhost:8000>.
