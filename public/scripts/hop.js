document.addEventListener('DOMContentLoaded', () => {

    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    let current = 0;

    // === POKAZYWANIE PYTAŃ ===
    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.style.display = (i === index) ? 'block' : 'none';
        });

        // przyciski
        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
        nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
    }

    showQuestion(current);
    // === zaznaczanie wybranej opcji ===
questions.forEach(question => {
    const labels = question.querySelectorAll('.option-label');
    labels.forEach(label => {
        const input = label.querySelector('input[type="radio"]');

        // przy kliknięciu na label
        label.addEventListener('click', () => {
            // usuń klasę .selected ze wszystkich labeli w tym pytaniu
            labels.forEach(l => l.classList.remove('selected'));

            // dodaj .selected tylko klikniętemu labelowi
            label.classList.add('selected');

            // zaznacz input (dla pewności)
            input.checked = true;
        });

        // === przy ładowaniu, jeśli radio było wcześniej zaznaczone ===
        if (input.checked) {
            label.classList.add('selected');
        }
    });
});


    // === SPRAWDZANIE CZY JEST ODPOWIEDŹ ===
    function hasAnswer(index) {
        return questions[index].querySelector('input[type="radio"]:checked') !== null;
    }

    // === NAWIGACJA ===
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

    // === WYSYŁANIE ANKIETY ===
    submitBtn.addEventListener('click', () => {

        // ostatnie pytanie też musi mieć odpowiedź
        if (!hasAnswer(current)) {
            alert('Zaznacz odpowiedź przed wysłaniem');
            return;
        }

        const answers = {};

        document.querySelectorAll('input[type="radio"]:checked')
            .forEach(input => {
                answers[input.name] = parseInt(input.value);
            });

        console.log('Wysyłane odpowiedzi:', answers);

        fetch('/saveSurvey', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ answers })
        })
        .then(res => res.json())
        .then(data => {
            console.log('Odpowiedź serwera:', data);

            if (data.status === 'ok') {
                alert('Ankieta zapisana poprawnie');
                window.location.href = 'https://localhost:8443/questions';

            } else {
                alert('Błąd: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Błąd połączenia z serwerem');
        });
    });

});
