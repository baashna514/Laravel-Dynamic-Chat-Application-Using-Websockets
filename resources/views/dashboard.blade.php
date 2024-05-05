<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            @if(count($users) > 0)
                <div class="col-md-3">
                    <ul class="list-group">
                        @foreach($users as $user)
                            @php
                                $lastSentMessage = $user->sentMessages->last();
                                $lastReceivedMessage = $user->receivedMessages->last();
                                $lastMessage = $lastSentMessage && $lastReceivedMessage ?
                                    ($lastSentMessage->created_at > $lastReceivedMessage->created_at ? $lastSentMessage : $lastReceivedMessage) :
                                    ($lastSentMessage ?: $lastReceivedMessage);
                            @endphp
                            <li class="list-group-item list-group-item-dark cursor-pointer user-list d-flex align-items-center" data-id="{{ $user->id }}">
                                <div class="user-image-container position-relative mr-3">
                                    <img src="{{ $user->image ?: 'images/dummy.png' }}" alt="Profile Image" class="user-image">
                                    <!-- Online or offline status indicator -->
                                    <sup><span id="{{ $user->id }}-status" class="status-dot offline"></span></sup>
                                </div>
                                <div class="user-info flex-grow-1">
                                    <h4>{{ $user->name }}</h4>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-9">
                    <h3 class="start-head" style="text-align: center;">Welcome to Dynamic Web Chat Application Dashboard</h3>
                    <div class="chat-section">
                        <div id="chat-container">

                        </div>

                        <div class="chat-form-container">
                            <form action="" id="chat-form">
                                <div class="form-group">
                                    <input type="text" name="message" id="message" placeholder="Enter your message..." class="form-input" required>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-12">
                    <h5>Users not found!</h5>
                </div>
            @endif
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Chat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="delete-chat-form">
                    <input type="hidden" name="id" id="delete-chat-id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this chat message?</p>
                        <p><b id="delete-message"></b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
