<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Chat with ChatGPT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        #chat-container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        #messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .message {
            margin: 5px 0;
            padding: 10px;
            border-radius: 8px;
        }
        .user {
            background: #e0f7fa;
            align-self: flex-end;
        }
        .bot {
            background: #f1f8e9;
            align-self: flex-start;
        }
        #chat-form {
            display: flex;
        }
        #chat-form input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-right: 5px;
        }
        #chat-form button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        #chat-form button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div id="chat-container">
        <div id="messages"></div>
        <form action="<?php echo e(route('message.send')); ?>" method="post" id="chat-form">
            <input type="text" id="message" placeholder="Type your message..." autocomplete="off">
            <button type="submit">Send</button>
            <input type="hidden" id="login-token" name="_token" value="<?php echo e(csrf_token()); ?>" />
        </form>
    </div>

    <script>
        const messagesContainer = document.getElementById('messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Get user input
            const userMessage = messageInput.value.trim();
            if (!userMessage) return;

            // Display user message
            displayMessage(userMessage, 'user');

            // Clear input field
            messageInput.value = '';

            try {
                // Send message to backend
                const response = await fetch('/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ message: userMessage }),
                });

                const data = await response.json();

                // Display bot response
                if (data.response && data.response.choices) {
    displayMessage(data.response.choices[0].message.content, 'bot');
} else {
    displayMessage('No response from ChatGPT', 'bot');
}
            } catch (error) {
                console.error('Error:', error);
                displayMessage('Error communicating with ChatGPT', 'bot');
            }
        });

        function displayMessage(content, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.textContent = content;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight; // Auto-scroll
        }
    </script>
</body>
</html>
