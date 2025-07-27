<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

# 🛠️ Rezervační systém restaurace

Tento projekt je ukázková aplikace pro správu rezervací stolů v restauraci.

---

## 🚀 Jak spustit projekt
1. `git clone ...`
2. `composer install`
3. `cp .env.example .env` a nastav připojení k DB.
4. `php artisan migrate --seed`
5. `php artisan serve`
6. `npm install`
7. `npm run dev`

---

## ✅ Jak otestovat hledání volného stolu
- **Jak otestovat:**
    - Přihlaste se jednoduše (pro zjednodušení přístupu je email a heslo vždy stejné) -> například Email: test1@vue.com, heslo: test1@vue.com
    - Otevři formulář pro vytvoření rezervace.
    - Napiš počet osob (od 1 do 10), vyber datum
    - Následně se načtou a zobrazí volné hodiny pro daný den a danou kapacitu
    - K dispozici je celkem 10 stolů s následující kapacitou: 3 stoly pro 2 osoby, 3 stoly pro 4 osoby, 3 stoly pro 8 osob a 1 stůl pro 10 osob.
    - Pro nejjednodušší a nejrychlejší otestování doporučuji napsat počet osob 9 nebo 10 (bude to hledat pouze ve stolech s kapacitou 10, který je pouze 1)

---
