// public/js/chat.js
const chatForm = document.querySelector('.chat-input');
const messageInput = chatForm.querySelector('input[name="message"]');
const messagesWindow = document.querySelector('.messages-window');

// Pobieramy dane z kontenera, które ustawimy w HTML
const container = document.querySelector('.chat-container');
const receiverId = container.getAttribute('data-receiver-id');
const myId = container.getAttribute('data-my-id');

function loadMessages() {
    fetch(`/getMessagesJson?id=${receiverId}`)
        .then(response => response.json())
        .then(messages => {
            messagesWindow.innerHTML = '';
            messages.forEach(msg => {
                const isSent = msg.sender_id == myId;
                const div = document.createElement('div');
                div.className = `message-wrapper ${isSent ? 'sent' : 'received'}`;
                div.innerHTML = `
                    <div class="message-bubble">
                        ${escapeHTML(msg.message)}
                        <span class="time">${msg.created_at.substring(11, 16)}</span>
                    </div>
                `;
                messagesWindow.appendChild(div);
            });
            messagesWindow.scrollTop = messagesWindow.scrollHeight;
        });
}

// Funkcja zabezpieczająca przed XSS (bezpieczniejsze wyświetlanie tekstu)
function escapeHTML(str) {
    const p = document.createElement('p');
    p.textContent = str;
    return p.innerHTML;
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const messageText = messageInput.value;
    if(!messageText.trim()) return;

    fetch('/sendMessageAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            message: messageText,
            receiverId: receiverId
        })
    }).then(() => {
        messageInput.value = '';
        loadMessages();
    });
});

setInterval(loadMessages, 3000);
// Ładujemy od razu po wejściu na stronę
loadMessages();