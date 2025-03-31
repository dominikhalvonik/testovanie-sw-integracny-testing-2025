# Jednoduchý blog systém

Toto je jednoduchý blog systém vytvorený v PHP s MySQL databázou a ukladaním obrázkov na disk.

## Požiadavky

- PHP 8.0 alebo novší
- MySQL alebo MariaDB
- Composer

## Inštalácia

1. Naklonujte repozitár tak aby vaša doména vyzerala: http://localhost/testovanie-sw-integracny-testing-2025
2. Nainštalujte závislosti: `composer install`
3. Vytvorte databázu `simple_blog` a upravte `config/database.php`
4. Spustite testy: `vendor/bin/phpunit tests`

## Funkcie

- Pridávanie článkov s titulkom a obsahom
- Nahrávanie obrázkov k článkom
- Zoznam všetkých článkov
- Základné CRUD operácie

## Testovanie

Systém obsahuje PHPUnit testy pre základnú funkcionalitu. Testy používajú testovaciu MySQL/MariaDB databázu pre izoláciu.

## Integračné testy

Študenti by mali napísať integračné testy pre:
1. Vytvorenie článku s obrázkom
2. Aktualizáciu článku so zmenou obrázka
3. Mazanie článku a pridruženého obrázka
4. Overenie správneho zobrazenia článkov