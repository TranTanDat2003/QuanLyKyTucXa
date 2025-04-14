@extends('layouts.student')

@section('title', 'Chat - Ký Túc Xá')

@section('content')
    <section class="container chat-container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card chat-card">
                    <div class="card-header">
                        <h3>{{ __('Chat') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Messages Area -->
                            <div class="col-md-8">
                                <div class="messages-wrapper">
                                    <ul id="messages" class="list-unstyled"></ul>
                                </div>
                                <form class="message-form" id="message-form">
                                    <div class="input-group">
                                        <input id="message" type="text" class="form-control" placeholder="Nhập tin nhắn..." autocomplete="off">
                                        <button id="send" type="submit" class="btn btn-primary">Gửi</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Users Online -->
                            <div class="col-md-4">
                                <div class="users-wrapper">
                                    <h4>Người dùng Online</h4>
                                    <ul id="users" class="list-unstyled"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        .card:hover {
            transform: none;
        }
    </style>
@endpush

@push('scripts')
    <script type="module">
        const usersElement = document.getElementById('users');
        const messagesElement = document.getElementById('messages');

        Echo.join('chat')
            .here((users) => {
                users.forEach((user, index) => {
                    const element = document.createElement('li');
                    element.innerText = user.name;

                    usersElement.appendChild(element);
                });
            })
            .joining((user) => {
                const element = document.createElement('li');
                element.innerText = user.name;

                usersElement.appendChild(element);
            })
            .leaving((user) => {
                const element = document.getElementById(user.id);
                element.parentNode.removeChild(element);
            })
            .error((error) => {
                console.error(error);
            })
            .listen('MessageSent', (e) => {
                const element = document.createElement('li');
                element.innerText = e.user.username + ': ' + e.message;
                element.classList.add('text-primary');

                messagesElement.appendChild(element);
                messagesElement.scrollTop = messagesElement.scrollHeight;
            })
    </script>

    <script type="module">
        const messageElement = document.getElementById('message');
        const sendElement = document.getElementById('send');

        sendElement.addEventListener('click', (e) => {
            e.preventDefault();

            const message = messageElement.value;

            if (message.trim() === '') {
                return;
            }

            window.axios.post('/chat/message', {
                message: message
            })

            messageElement.value = '';
        });
    </script>
@endpush