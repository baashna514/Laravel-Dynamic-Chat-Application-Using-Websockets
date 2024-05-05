$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function (){
    $('.user-list').click(function (){
        $('.user-list').removeClass('selected');
        $(this).addClass('selected');

        $('#chat-container').html('');

        var get_user_id = $(this).attr('data-id');
        receiver_id = get_user_id;
        $('.start-head').hide();
        $('.chat-section').show();

        loadOldChat();
    });

    // Submit chat form...
    $('#chat-form').submit(function (e){
        e.preventDefault();
        var message = $('#message').val();

        $.ajax({
            url: "/save-chat",
            type: "POST",
            data: {sender_id:sender_id, receiver_id:receiver_id, message:message},
            success: function (result){
                if(result.success){
                    $('#message').val('');
                    let chat = result.data.message;
                    let html = `
                        <div class="current-user-chat" id='`+result.data.id+`-chat'>
                            <h5>
                            <span>`+chat+`</span>
                            <i class="fa fa-trash" aria-hidden="true" data-id="`+result.data.id+`" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                            </h5>
                        </div>
                    `;
                    $('#chat-container').append(html);
                    scrollChat()
                }else{
                    alert(result.msg);
                }
            }
        });
    });


    $(document).on('click', '.fa-trash', function(){
        var id = $(this).attr('data-id');
        $('#delete-chat-id').val(id);
        $('#delete-message').text($(this).parent().text());
    });


    // Submit chat delete form...
    $('#delete-chat-form').submit(function (e){
        e.preventDefault();
        var id = $('#delete-chat-id').val();

        $.ajax({
            url: "/delete-chat",
            type: "GET",
            data: {id:id},
            success: function (result){
                if(result.success){
                    $('#'+id+'-chat').remove();
                    $('#exampleModal').modal('hide');
                }else{
                    alert(result.msg);
                }
            }
        });
    });
}); // document.ready end

// load old chat
function loadOldChat(){
    $.ajax({
        url: "/load-chat",
        type: "GET",
        data: {sender_id:sender_id, receiver_id:receiver_id},
        success: function (result){
            if(result.success){
                let chats = result.data;
                let html = '';
                for (let i=0; i < chats.length; i++){
                    let addClass = '';
                    if(chats[i].sender_id == sender_id){
                        addClass = 'current-user-chat'
                    }else{
                        addClass = 'distance-user-chat'
                    }


                    html += `
                        <div class="`+addClass+`" id="`+chats[i].id+`-chat">
                            <h5>
                                <span>`+chats[i].message+`</span>`;

                    if(chats[i].sender_id == sender_id){
                        html += `
                             <i class="fa fa-trash" aria-hidden="true" data-id="` + chats[i].id +`" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                        `;
                    }

                    html += `
                            </h5>
                        </div>
                    `;
                }
                $('#chat-container').append(html);
                scrollChat()
            }else{
                alert(result.msg);
            }
        }
    });
}

// scroll div
function scrollChat(){
    $('#chat-container').animate({
        scrollTop: $('#chat-container').offset().top + $('#chat-container')[0].scrollHeight
    }, 0);
}

Echo.join('status-update')
    .here( (users) => {
        for(let x = 0; x < users.length; x++){
            if(sender_id != users[x]['id']){
                $('#'+users[x]['id']+'-status').removeClass('offline');
                $('#'+users[x]['id']+'-status').addClass('online');
                // $('#'+users[x]['id']+'-status').text('Online');
            }
        }
    })
    .joining((user)=>{
        $('#'+user.id+'-status').removeClass('offline');
        $('#'+user.id+'-status').addClass('online');
        // $('#'+user.id+'-status').text('Online');
    })
    .leaving((user)=>{
        $('#'+user.id+'-status').addClass('offline');
        $('#'+user.id+'-status').removeClass('online');
        // $('#'+user.id+'-status').text('Offline');
    })
    .listen('UserStatusEvent', (e)=>{

    })

Echo.private('broadcast-message').listen('.getChatMessage', (response) => {
    if(sender_id == response.chat.receiver_id && receiver_id == response.chat.sender_id){
        let html = `
            <div class="distance-user-chat" id="`+response.chat.id+`-chat">
                <h5>
                    <span>`+response.chat.message+`</span>
                    <i class="fa fa-trash" aria-hidden="true" data-id="` + response.chat.id +`" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                </h5>
            </div>
        `;
        $('#chat-container').append(html);
        scrollChat()
    }
});

// delete chat message listen
Echo.private('message-deleted').listen('MessageDeleteEvent', (data) => {
    $('#'+data.id+'-chat').remove();
});
