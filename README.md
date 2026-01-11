# Opinio - Platforma Testów Poglądowych i Społeczności

##  Opis Projektu
Aplikacja webowa umożliwiająca użytkownikom rozwiązywanie ankiet dotyczących poglądów, dopasowywanie się w pary na podstawie wyników oraz komunikację w czasie rzeczywistym poprzez czat. Projekt został zrealizowany w autorskiej architekturze MVC bez użycia gotowych frameworków.

---

##  Technologie
* **Backend:** PHP 
* **Frontend:** HTML, CSS, JavaScript 
* **Baza danych:** PostgreSQL 
* **Konteneryzacja:** Docker 
* **Wersjonowanie:** GIT

---

##  Screeny Aplikacji

Kluczowe moduły aplikacji oraz ich interfejsy:

### 1. Panel Logowania i Rejestracji
* **Logowanie:** Formularz z walidacją sesji i obsługą błędów autoryzacji.
<img width="919" height="683" alt="image" src="https://github.com/user-attachments/assets/0d3d79b1-a0da-4846-b508-9c067ef3e320" />

* **Rejestracja:** System tworzenia nowego profilu użytkownika wraz z domyślnym przypisaniem ról.
<img width="1875" height="830" alt="image" src="https://github.com/user-attachments/assets/930a89ed-c4db-4631-be90-88003d10a991" />


### 2. Discover (System Swipe/Match)
* **Przeglądanie profili:** Interfejs oparty na kartach, pozwalający na polubienie lub odrzucenie profilu innego użytkownika.
<img width="763" height="688" alt="image" src="https://github.com/user-attachments/assets/16373619-0bad-4efc-a99b-e6000ba710ca" />

### 3. Czat Real-Time (AJAX)
* **Komunikacja:** Dynamiczne okno rozmowy działające bez odświeżania strony dzięki Fetch API.
  <img width="1204" height="734" alt="image" src="https://github.com/user-attachments/assets/b6f5600e-bceb-4a96-b007-573630c0bb13" />

* **Lista konwersacji:** Widok aktywnych par użytkownika pobierany z bazy przez widok `v_friends_details`.
  <img width="861" height="857" alt="image" src="https://github.com/user-attachments/assets/ed55844b-8e85-47a0-a7dd-c3c1cbd1336b" />

### 4. Test Poglądów i Profil
* **Ankiety:** Interfejs rozwiązywania pytań z dynamicznym zapisem wyników do tabeli `user_scores`.
  <img width="2532" height="1230" alt="image" src="https://github.com/user-attachments/assets/f47cd2c5-f672-4e69-9705-2d27e964fb65" />
  <img width="979" height="562" alt="image" src="https://github.com/user-attachments/assets/0881ead1-4238-4383-a595-f117a641c6b7" />


### 5. Profil
* **Mój Profil:** Edycja danych, zdjęcia profilowego oraz wizualizacja własnych poglądów.
<img width="847" height="1231" alt="image" src="https://github.com/user-attachments/assets/275def02-cfe7-4f00-b66f-a6fdd7d28346" />


### 6. Panel Administratora
* Moduł przeznaczony do zarządzania platformą, dostępny wyłącznie dla użytkowników z uprawnieniami admin.
<img width="1063" height="927" alt="image" src="https://github.com/user-attachments/assets/2df56725-2534-4828-86c6-5567f3f35337" />
<img width="1024" height="342" alt="image" src="https://github.com/user-attachments/assets/88d7163d-8332-42b9-9650-84db0b6be477" />
<img width="1021" height="1042" alt="image" src="https://github.com/user-attachments/assets/860d9bf9-9c68-473d-ad5e-d35c5edfe245" />




---



##  Architektura Aplikacji
Projekt bazuje na wzorcu **MVC (Model-View-Controller)**:


* **Controller:** Obsługa żądań HTTP, zarządzanie logiką sesji i uprawnień użytkowników.
* **Repository:** Warstwa dostępu do danych, wykorzystująca PDO i Prepared Statements dla bezpieczeństwa.
* **Views:** Szablony PHP/HTML odpowiedzialne za generowanie interfejsu użytkownika.
* **Routing:** System mapowania adresów URL na konkretne metody kontrolerów.




    

### *Przykładowy diagram warstwowy:*
  
  <img width="636" height="639" alt="image" src="https://github.com/user-attachments/assets/2f041da5-f3db-4cd6-b151-df077dee3fe4" />

---

##  Baza Danych
Struktura bazy danych została zaprojektowana zgodnie z zasadami **3. postaci normalnej (3NF)**, eliminując redundancję danych.


### Diagram ERD
<img width="1567" height="1083" alt="image" src="https://github.com/user-attachments/assets/8d1bcdd9-5c4c-4b24-afc7-2fff65763ac6" />

### Typy relacji:
* **Jeden-do-jednego (1:1):** Tabela `users` ↔ `user_scores` (wyniki testu przypisane do konkretnego konta).
* **Jeden-do-wielu (1:N):** Tabela `surveys` ↔ `questions` (każda ankieta posiada wiele pytań).
* **Wiele-do-wielu (M:N):** Relacja `users` ↔ `users` realizowana przez tabele łączące `friends` oraz `interactions`.



### Mechanizmy SQL:
* **Widoki (Views):**
    * `v_friends_details` – łączy dane o znajomych, ich profilach i wynikach testów.
    * `v_survey_questions` – złączenie pytań z metadanymi ankiet.
* **Wyzwalacz (Trigger):** `tr_after_match_clean` – automatycznie usuwa rekordy z tabeli `interactions` po pomyślnym dodaniu rekordu do `friends`.
* **Funkcja (Function):** `are_friends(uid1, uid2)` – funkcja SQL sprawdzająca status relacji między użytkownikami.
* **Transakcje:** Zastosowane w `MatchRepository` (metoda `handleLike`) z poziomem izolacji *Read Committed*, zapewniające atomowość procesu tworzenia dopasowań.
* **Akcje na referencjach:** Zastosowanie `ON DELETE CASCADE` dla kluczy obcych (np. usunięcie użytkownika automatycznie czyści jego wiadomości i wyniki).

---

##  Instrukcja Uruchomienia

Projekt został w pełni skonfigurowany przy użyciu Docker Compose. Cała baza danych (tabele, widoki, triggery oraz dane testowe) importuje się automatycznie przy pierwszym uruchomieniu.

1. Sklonuj repozytorium na dysk lokalny.
2. Uruchom środowisko dockerowe:
   ```bash
   docker-compose up --build
Gdy kontenery zostaną uruchomione, aplikacja będzie dostępna pod adresami:

- **HTTPS (zalecane):** https://localhost:8443  
- **HTTP:** http://localhost:8081  

###  Dane do logowania (konta testowe)

Aby sprawdzić funkcjonalności systemu (Czat, Match, Swipe) bez rejestracji nowych kont, użyj poniższych danych:
mail : michaelciesielczyk12@gmail.com
haslo : qw

---

##  Scenariusz Testowy (Validation & QA)
Poniższy scenariusz opisuje kroki niezbędne do zweryfikowania wszystkich kluczowych mechanizmów aplikacji: od zabezpieczeń serwerowych, przez logikę biznesową, aż po automatyzację bazy danych.

---

## 1. Bezpieczeństwo i Obsługa Błędów HTTP
Celem tego testu jest sprawdzenie, czy system autoryzacji w `AppController` oraz routing poprawnie zarządzają dostępem.

| Krok | Akcja | Oczekiwany Wynik | Kod HTTP |
|:--- |:--- |:--- |:--- |
| **1.1** | Próba wejścia na `/dashboard` bez logowania. | Przekierowanie do widoku błędu (Unauthorized). | `401` |
| **1.2** | Zwykły użytkownik wchodzi na `/adminPanel`. | Odmowa dostępu (Forbidden). | `403` |
| **1.3** | Wpisanie nieistniejącego adresu `/xyz`. | Wyświetlenie strony 404. | `404` |
| **1.4** | Wpisanie adresu akcji POST (np. `/saveSurvey`) w pasek adresu. | Komunikat o niedozwolonej metodzie (Method Not Allowed). | `405` |

---

## 2. Test Poglądów i Ankiet (Frontend & Backend)
Weryfikacja płynności interfejsu oraz poprawności zapisu danych.

1. **Interakcja (JS):** Otwórz ankietę. Spróbuj przejść dalej bez zaznaczenia opcji.
   * *Wynik:* Skrypt `hop.js` blokuje nawigację i wyświetla komunikat ostrzegawczy.
2. **Przesyłanie (AJAX):** Ukończ ankietę i kliknij "Wyślij".
   * *Wynik:* Dane są wysyłane asynchronicznie (Fetch API). Strona nie przeładowuje się, a użytkownik otrzymuje potwierdzenie.
3. **Przeliczanie (Logic):** Wejdź w "Mój Profil".
   * *Wynik:* `CalculatorService` pobiera nowe dane z `user_scores` i aktualizuje współrzędne na wykresie.



---

## 3. System Match & Real-Time Chat
Sprawdzenie mechanizmów społecznościowych i automatyzacji SQL.

* **Match (SQL Trigger):** Wykonaj wzajemne "polubienie" dwóch profili w module Discover.
    * *Weryfikacja:* Sprawdź, czy wyzwalacz `tr_after_match_clean` poprawnie usunął rekordy z `interactions` i utworzył nowy rekord w `friends`.
* **Czat (AJAX):** Wyślij wiadomość do dopasowanej osoby.
    * *Weryfikacja:* Wiadomość powinna pojawić się w oknie czatu natychmiast, a odbiorca powinien ją zobaczyć bez odświeżania strony dzięki cyklicznym zapytaniom `getMessagesJson`.

---

## 4. Zarządzanie Administracyjne (CRUD)
Testowanie pełnego cyklu zarządzania danymi przez administratora.

* **Create:** Dodaj nową ankietę i zestaw pytań w panelu admina. Sprawdź, czy są dostępne dla użytkowników.
* **Update:** Zmień wagę punktową istniejącego pytania.
* **Delete (Cascade):** Usuń użytkownika lub ankietę.
    * *Weryfikacja:* Dzięki więzom `ON DELETE CASCADE`, sprawdź czy wszystkie powiązane rekordy (wyniki, wiadomości) zostały usunięte z bazy danych, zachowując jej spójność.

---

## 5. Wydajność: Widoki SQL
* **Test v_friends_details:** Wyświetl listę znajomych.
    * *Weryfikacja:* Dane (imię, nazwisko, zdjęcie profilowe) powinny być pobierane przez dedykowany widok SQL, co minimalizuje liczbę złączeń (JOIN) wykonywanych bezpośrednio w kodzie PHP i optymalizuje czas odpowiedzi serwera.

---

### Jak ręcznie wymusić błąd 405 (Test techniczny)?
Aby udowodnić poprawność obsługi metod HTTP, wklej poniższy kod w konsoli przeglądarki (F12) na stronie profilu:

```javascript
fetch('/profile', { method: 'POST' })
    .then(res => console.log("Status odpowiedzi serwera: " + res.status));
```


---
## ✅ Checklista Wymagań Projektowych

| Lp. | Wymaganie | Status | Opis |
| :--- | :--- | :---: | :--- |
| 1 | **DOKUMENTACJA W README.MD** | ✅ | Kompletny opis projektu, instrukcja uruchomienia i scenariusze testowe. |
| 2 | **ARCHITEKTURA APLIKACJI MVC / FRONT-BACKEND** | ✅ | Podział na warstwy Model, View, Controller oraz separacja frontendu od backendu. |
| 3 | **KOD NAPISANY OBIEKTOWO (CZĘŚĆ BACKENDOWA)** | ✅ | Logika biznesowa zrealizowana w oparciu o klasy i obiekty w PHP. |
| 4 | **DIAGRAM ERD** | ✅ | Graficzne przedstawienie struktury i relacji bazy danych. |
| 5 | **GIT** | ✅ | Zarządzanie wersjami kodu udokumentowane historią commitów. |
| 6 | **REALIZACJA TEMATU** | ✅ | Aplikacja w pełni realizuje założenia systemu dopasowań światopoglądowych. |
| 7 | **HTML** | ✅ | Semantyczny kod HTML5 stanowiący szkielet interfejsu. |
| 8 | **POSTGRESQL** | ✅ | Wykorzystanie relacyjnej bazy danych PostgreSQL. |
| 9 | **ZŁOŻONOŚĆ BAZY DANYCH** | ✅ | Rozbudowana struktura tabel z licznymi powiązaniami. |
| 10 | **EKSPORT BAZY DO PLIKU .SQL** | ✅ | Dołączony kompletny zrzut struktury i danych (dump). |
| 11 | **PHP** | ✅ | Skrypty po stronie serwera napisane w PHP 8. |
| 12 | **JAVA SCRIPT** | ✅ | Logika po stronie klienta odpowiedzialna za interakcję. |
| 13 | **FETCH API (AJAX)** | ✅ | Dynamiczna komunikacja asynchroniczna (czat, wyszukiwarka). |
| 14 | **DESIGN** | ✅ | Estetyczny i nowoczesny wygląd dopasowany do tematyki aplikacji. |
| 15 | **RESPONSYWNOŚĆ** | ✅ | Interfejs działający poprawnie na urządzeniach mobilnych i desktopach (RWD). |
| 16 | **LOGOWANIE** | ✅ | Bezpieczny system uwierzytelniania użytkowników. |
| 17 | **SESJA UŻYTKOWNIKA** | ✅ | Zarządzanie stanem zalogowania i danymi w sesji. |
| 18 | **UPRAWNIENIA UŻYTKOWNIKÓW** | ✅ | Weryfikacja dostępu do konkretnych zasobów aplikacji. |
| 19 | **ROLE UŻYTKOWNIKÓW (MIN. DWIE)** | ✅ | System ról: `User` (podstawowy) oraz `Admin` (zarządzanie). |
| 20 | **WYLOGOWYWANIE** | ✅ | Mechanizm bezpiecznego kończenia sesji. |
| 21 | **WIDOKI, WYZWALACZE, FUNKCJE, TRANSAKCJE** | ✅ | Zaawansowana logika po stronie bazy danych PostgreSQL. |
| 22 | **AKCJE NA REFERENCJACH** | ✅ | Zastosowanie `ON DELETE CASCADE` dla utrzymania integralności. |
| 23 | **BEZPIECZEŃSTWO** | ✅ | Obsługa błędów HTTP (401, 403, 405) i ochrona przed nieuprawnionym dostępem. |
| 24 | **BRAK REPLIKACJI KODU** | ✅ | Przestrzeganie zasady DRY w strukturze plików i funkcji. |
| 25 | **CZYSTOŚĆ I PRZEJRZYSTOŚĆ KODU** | ✅ | Kod sformatowany, skomentowany i logicznie uporządkowany. |



