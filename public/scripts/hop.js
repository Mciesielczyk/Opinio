const questions = document.querySelectorAll('.question');
let current = 0;

const showQuestion = (index) => {
    questions.forEach((q, i) => {
        q.style.display = i === index ? 'block' : 'none';
    });
};

document.getElementById('nextBtn').addEventListener('click', () => {
    if(current < questions.length - 1){
        current++;
        showQuestion(current);
    }
});

document.getElementById('prevBtn').addEventListener('click', () => {
    if(current > 0){
        current--;
        showQuestion(current);
    }
});


document.addEventListener('DOMContentLoaded', () => {
    showQuestion(current);
    const questionContainers = document.querySelectorAll('.question');

    questionContainers.forEach(question => {
        const labels = question.querySelectorAll('.option-label');

        labels.forEach(label => {
            const input = label.querySelector('input[type="radio"]');

            label.addEventListener('click', () => {
                labels.forEach(l => l.classList.remove('selected'));
                label.classList.add('selected');
                input.checked = true;
            });
        });
    });

    // Zbieranie odpowiedzi i wysyłanie do PHP
    document.getElementById('surveyForm').addEventListener('submit', function(e) {
        e.preventDefault(); // blokuje reload strony

        const answers = {};
        const checkedInputs = document.querySelectorAll('input[type=radio]:checked');

        checkedInputs.forEach(input => {
            answers[input.name] = parseInt(input.value);
        });

        fetch('/saveSurvey', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ answers })
})
.then(res => res.text())  // najpierw jako tekst
.then(data => console.log(data))  // zobacz co faktycznie wraca
.catch(err => console.error(err));

    });
});