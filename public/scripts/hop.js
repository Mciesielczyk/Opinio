document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ Skrypt hop.js załadowany');

    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    let current = 0;

    function showQuestion(index) {
        console.log('Pytanie:', index + 1, 'z', questions.length);
        questions.forEach((q, i) => {
            q.style.display = (i === index) ? 'block' : 'none';
        });

        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
        nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
    }

    showQuestion(current);

    questions.forEach((question, qIdx) => {
        const labels = question.querySelectorAll('.option-label');
        labels.forEach(label => {
            const input = label.querySelector('input[type="radio"]');

            label.addEventListener('click', () => {
                console.log(`Kliknięto pytanie ${qIdx}, wartość: ${input.value}`);
                labels.forEach(l => l.classList.remove('selected'));
                label.classList.add('selected');
                input.checked = true;
            });

            if (input.checked) {
                label.classList.add('selected');
            }
        });
    }); // <--- Poprawiona pozycja klamry

    function hasAnswer(index) {
        const checked = questions[index].querySelector('input[type="radio"]:checked');
        console.log(`Sprawdzanie odpowiedzi dla pytania ${index}:`, checked ? 'TAK' : 'BRAK');
        return checked !== null;
    }

    nextBtn.addEventListener('click', () => {
        if (!hasAnswer(current)) {
            alert('Zaznacz odpowiedź przed przejściem dalej');
            return;
        }
        if (current < questions.length - 1) {
            current++;
            showQuestion(current);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (current > 0) {
            current--;
            showQuestion(current);
        }
    });

    submitBtn.addEventListener('click', () => {
        console.log(' Próba wysłania ankiety...');

        if (!hasAnswer(current)) {
            alert('Zaznacz odpowiedź przed wysłaniem');
            return;
        }

        const answers = {};
        document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
            answers[input.name] = parseInt(input.value);
        });

        console.log('Dane do wysyłki (JSON):', JSON.stringify({ answers }));


// POBIERANIE ID ANKIETY Z URL (np. z /survey?id=5 pobierze "5")
    const urlParams = new URLSearchParams(window.location.search);
    const surveyId = urlParams.get('id');

    // BUDOWANIE KOMPLETNEGO OBIEKTU
    const payload = { 
        survey_id: parseInt(surveyId), // Dodajemy to pole!
        answers: answers 
    };


        fetch('/saveSurvey', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ payload })
        })
        .then(async res => {
            const text = await res.text();
            console.log('Surowa odpowiedź serwera (tekst):', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Błąd parsowania JSON. Serwer zwrócił coś innego niż JSON!');
                throw new Error("Serwer nie zwrócił poprawnego formatu JSON");
            }
        })
        .then(data => {
            console.log('Zinterpretowany JSON:', data);
            if (data.status === 'ok') {
                alert('Ankieta zapisana poprawnie');
                window.location.href = '/questions';
            } else {
                alert('Serwer zwrócił błąd: ' + data.message);
            }
        })
        .catch(err => {
            console.error('Błąd Fetch:', err);
            alert('Krytyczny błąd połączenia. Sprawdź konsolę (F12).');
        });
    });
});