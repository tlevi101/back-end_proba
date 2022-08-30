# Back-end próba feladat
Routok:
#### /users (GET)
    - Válasz:
      - code 200:
        - Összes felhasználó
    - Hiba üzenetek:
      - code 404:
        - Nem található felhasználó az adatbázisban.
####   /users (POST)
    - request body:
      - first_name, last_name:
        - Csak szöveg, kötelező megadni
      - email:
        - Kötelező, email formátumú
      - phone_number:
        - Nem kötelező, + jellel kezdődjön és 12 karakter hósszú
    - Válasz:
      - code 201: 
        - Létrehozott felhasználó minden adattal, kivéve a jelszó
    - Hiba üzenetek:
      - code 400:
        - Hibás adatok
        
#### /parcels/{parcel_number} (GET)
    - Válasz:
      - A routeban megadott csomag számhoz tartozó csomag, és az ahhoz tartozó felhesználó adatai
    - Hiba üzenetek:
      - code 400:
        - Hiányzik a csomag szám
      - code 404:
        - Nem található a csomag
        
####   /parcels (POST)
    - request body:
      - size:
        - ['S','L', 'M', 'XL'], kötelező
      - user_id
        - Kötelező, szám
    - Válasz:
      - A létre hozott csomag a felhasználó adataival
    - Hiba üzenetek:
      - code 400:
        - Valami adat nem megfelelő

#### Egyéb hiba üzenetek, routoláskor:
    - code 404:
      - Keresett oldal nem található
    - code 501:
      - Az oldal létezik, de a rossz metódus hívás
    - code 400:
      - Hiányzó paraméter
  
