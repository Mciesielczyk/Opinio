CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    "password" VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE surveys (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    image_url VARCHAR(255) DEFAULT NULL
);

create table questions (
    id SERIAL PRIMARY KEY,
    survey_id INTEGER NOT NULL REFERENCES surveys(id) ON DELETE CASCADE,
    question_text TEXT NOT NULL,
    score_lewa_prawa INTEGER DEFAULT 0,
    score_wladza_wolnosc INTEGER DEFAULT 0,
    score_postep_konserwa INTEGER DEFAULT 0,
    score_globalizm_nacjonalizm INTEGER DEFAULT 0
);

INSERT INTO surveys (title) VALUES
('Sondaż poparcia dla partii politycznych przed wyborami'),
('Ocena działań rządu w zakresie polityki klimatycznej'),
('Percepcja bezpieczeństwa narodowego w kontekście międzynarodowym'),
('Ankieta na temat zmian w systemie edukacji publicznej'),
('Badanie postaw obywateli wobec reformy sądownictwa'),
('Ocena skuteczności kampanii informacyjnych Unii Europejskiej'),
('Zadowolenie mieszkańców z lokalnych władz samorządowych'),
('Analiza preferencji wyborczych w grupie wiekowej 18-25 lat'),
('Opinia publiczna na temat polityki imigracyjnej kraju'),
('Barometr nastrojów społecznych: Zaufanie do instytucji państwowych');




INSERT INTO questions (survey_id, question_text) VALUES
(1, 'Na którą partię polityczną najprawdopodobniej odda Pan/Pani swój głos?'),
(1, 'W jakim stopniu jest Pan/Pani zadowolony/a z obecnej sytuacji politycznej w kraju?'),
(1, 'Jak ocenia Pan/Pani działania głównej partii opozycyjnej?'),
(1, 'Jaki problem uważa Pan/Pani za najważniejszy w tej kampanii wyborczej?'),
(1, 'Czy uważa Pan/Pani, że wybory powinny odbyć się w terminie, czy zostać przyspieszone/opóźnione?'),
(1, 'W skali od 1 do 5 (gdzie 5 to bardzo duże zaufanie), jak ocenia Pan/Pani zaufanie do Państwowej Komisji Wyborczej?'),
(1, 'Czy uważa Pan/Pani, że głosy mniejszości są odpowiednio reprezentowane w obecnym systemie politycznym?'),
(1, 'Który lider polityczny najbardziej przekonuje Pana/Panią do siebie?'),
(1, 'Czy Pana/Pani decyzja wyborcza jest już ostateczna, czy może się jeszcze zmienić?'),
(1, 'Jak duży wpływ mają media społecznościowe na Pana/Pani opinie polityczne?');

INSERT INTO questions (survey_id, question_text) VALUES
(2, 'Jak ocenia Pan/Pani rządową politykę w zakresie odnawialnych źródeł energii?'),
(3, 'Czy uważa Pan/Pani, że obywatele mają wystarczający wpływ na procesy legislacyjne?'),
(4, 'Jaka jest Pana/Pani opinia na temat wprowadzenia obowiązkowego drugiego języka obcego w szkołach?'),
(5, 'W skali od 1 do 5, jak ocenia Pan/Pani równowagę między pracą a życiem prywatnym w sektorze publicznym?'),
(6, 'Czy uważa Pan/Pani, że należy zwiększyć finansowanie badań naukowych?'),
(7, 'Jak ocenia Pan/Pani działania lokalnych władz w walce z zanieczyszczeniem powietrza?'),
(8, 'Czy ma Pan/Pani zaufanie do statystyk publikowanych przez agencje rządowe?'),
(9, 'Czy uważa Pan/Pani, że kary za przestępstwa gospodarcze są wystarczająco surowe?'),
(10, 'W jaki sposób politycy powinni komunikować się z obywatelami w dobie kryzysu informacyjnego?'),
(2, 'Czy Pana/Pani zdaniem cele klimatyczne Unii Europejskiej są realistyczne?'),
(3, 'Jaki jest Pana/Pani stosunek do jawności oświadczeń majątkowych polityków?'),
(4, 'Czy program nauczania historii w szkołach jest Pana/Pani zdaniem obiektywny?'),
(5, 'Jakie są Pana/Pani oczekiwania wobec przyszłej polityki senioralnej państwa?'),
(6, 'Czy Pana/Pani zdaniem Polska powinna dążyć do wprowadzenia waluty Euro?'),
(7, 'W skali od 1 do 5, jak ocenia Pan/Pani dostępność i jakość transportu publicznego w swoim regionie?'),
(8, 'Czy Pana/Pani zdaniem należy wprowadzić powszechne głosowanie przez Internet?'),
(9, 'Jak ocenia Pan/Pani efektywność służby zdrowia po ostatnich reformach?'),
(10, 'Czy uważa Pan/Pani, że media publiczne powinny być finansowane wyłącznie z budżetu państwa?'),
(1, 'Która z obietnic wyborczych jest dla Pana/Pani najbardziej wiarygodna?'),
(2, 'Czy polityka energetyczna rządu stawia za duży nacisk na węgiel, czy za duży na OZE?'),
(3, 'Czy zgadza się Pan/Pani z tezą, że system sądownictwa wymaga głębokiej reformy?'),
(4, 'W skali od 1 do 5, jak ocenia Pan/Pani finansowanie szkolnictwa wyższego w Polsce?'),
(5, 'Czy uważa Pan/Pani, że związki zawodowe mają zbyt duży/zbyt mały wpływ na politykę pracy?'),
(6, 'Jaki jest Pana/Pani stosunek do rozszerzenia strefy Schengen o kolejne państwa?'),
(7, 'Czy uważa Pan/Pani, że należy zwiększyć środki na rozwój terenów wiejskich?'),
(8, 'Jakie ma Pan/Pani zdanie na temat uproszczenia procedur administracyjnych dla przedsiębiorców?'),
(9, 'Czy obecny wiek emerytalny jest Pana/Pani zdaniem odpowiedni?'),
(10, 'W jakim stopniu ufa Pan/Pani informacjom przekazywanym przez zagraniczne media?'),
(1, 'Jak ważna jest dla Pana/Pani charyzma lidera przy podejmowaniu decyzji o głosowaniu?'),
(2, 'Czy popiera Pan/Pani wprowadzenie podatku węglowego dla dużych firm?'),
(3, 'W jakim stopniu politycy powinni kierować się opinią ekspertów, a w jakim wolą wyborców?'),
(4, 'Czy uważa Pan/Pani, że lekcje religii powinny odbywać się w szkołach publicznych?'),
(5, 'Jak ocenia Pan/Pani wysokość płacy minimalnej w stosunku do kosztów życia?'),
(6, 'Czy Pana/Pani zdaniem Unia Europejska powinna mieć silniejszą wspólną armię?'),
(7, 'Który aspekt życia w Pana/Pani miejscowości wymaga natychmiastowej interwencji władz?'),
(8, 'Jak ocenia Pan/Pani przejrzystość wydatków publicznych w ostatnich latach?'),
(9, 'Czy uważa Pan/Pani, że należy zwiększyć dostęp do bezpłatnej opieki psychologicznej?'),
(10, 'Jaka jest Pana/Pani preferowana forma odbioru informacji politycznych (TV, prasa, internet)?'),
(1, 'Czy poparłby/aby Pan/Pani koalicję ugrupowań ideologicznie odległych, jeśli zapewniłaby stabilność?'),
(2, 'Czy Polska powinna zwiększyć import gazu spoza Rosji?'),
(3, 'Jakie Pana/Pani zdaniem powinny być główne kryteria przy nominacji na wysokie stanowiska państwowe?'),
(4, 'Czy jest Pan/Pani za zwiększeniem autonomii szkół w doborze programów nauczania?'),
(5, 'W jakim stopniu polityka rządu chroni prawa konsumentów na rynku finansowym?'),
(6, 'Jaką rolę powinna odgrywać Polska w kształtowaniu przyszłości NATO?'),
(7, 'Czy Pana/Pani zdaniem podatki lokalne są ustalane w sposób sprawiedliwy?'),
(8, 'Czy uważa Pan/Pani, że biurokracja utrudnia rozwój małych i średnich przedsiębiorstw?'),
(9, 'Jak ocenia Pan/Pani politykę państwa wobec bezdomności?'),
(10, 'Czy zgadza się Pan/Pani z tezą, że fake newsy są największym zagrożeniem dla demokracji?');

UPDATE questions
SET 
    score_lewa_prawa = (FLOOR(RANDOM() * 11) - 5),
    score_wladza_wolnosc = (FLOOR(RANDOM() * 11) - 5),
    score_postep_konserwa = (FLOOR(RANDOM() * 11) - 5),
    score_globalizm_nacjonalizm = (FLOOR(RANDOM() * 11) - 5);

CREATE TABLE user_scores (
    user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    score_lewa_prawa DOUBLE PRECISION DEFAULT 0.0,
    score_wladza_wolnosc DOUBLE PRECISION DEFAULT 0.0,
    score_postep_konserwa DOUBLE PRECISION DEFAULT 0.0,
    score_globalizm_nacjonalizm DOUBLE PRECISION DEFAULT 0.0,
    calculated_at TIMESTAMP DEFAULT NOW()
);