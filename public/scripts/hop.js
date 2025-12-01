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
    const questionContainers = document.querySelectorAll('.question');

    questionContainers.forEach(question => {
        const labels = question.querySelectorAll('.option-label');

        labels.forEach(label => {
            const input = label.querySelector('input[type="radio"]');

            label.addEventListener('click', () => {
                // zaznacz wybraną opcję
                labels.forEach(l => l.classList.remove('selected'));
                label.classList.add('selected');

                // ustaw radio na checked (potrzebne przy submit)
                input.checked = true;
            });
        });
    });
});
