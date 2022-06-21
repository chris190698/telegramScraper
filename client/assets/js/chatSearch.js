$('#back').click(function(){
    window.location.href = "message.php";
});

$('#searchMsg').on('click', _ =>{
    if($('input[name="wordSearch"]').val() === ""){
        $('#alertText').text('Inserisci la parola da ricercare');
        $('#alertError').addClass('show');
        $('input[name="wordSearch"]').attr('class', 'form-control is-invalid');
        setTimeout(_ => $('#alertError').removeClass('show'), 3000);
    }
    if ($("#dataInizio").val() > $("#dataFine").val()) {
        $("#dataFine,#dataInizio").attr('class', 'form-control is-invalid');
        return false;
    }
    searchChat();
});

searchChat = () => {
    $('#modalStripe').attr('aria-valuenow', 100).width('100%');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: serverUrl + "functions/searchChat.php",
        data: {
            chatID: $('select[name="selectChat"] option').filter(':selected').val(),
            peerType: $('select[name="selectChat"] option').filter(':selected').data('peertype'),
            peerIdType: $('select[name="selectChat"] option').filter(':selected').data('peeridtype'),
            dataInizio: $('input[name="dataInizio"]').val(),
            dataFine: $('input[name="dataFine"]').val(),
            wordSearch:$('input[name="wordSearch"]').val()
        },
        timeout: 0,
        success: (result) => {
            
            $('#modalLoading').modal({backdrop: 'static', keyboard: false, show: true, focus: true}).modal('show');
            $('#chat_list').html("");
            if(result.length == 0){
                $('#chat_list').append("<tr><td colspan='12'>Nessun risultato</td></tr>");
            }
            for(var i=0; i < result.length; i++){
                var row = result[i];
                var html = "<tr>" +
                                "<td>" + '<img name="img" src="./functions/profilePicture.php?peerType=' + row.peerType  + '&peerIdType=' + row.peerIdType + '&peerId=' + row.id + '" onerror="this.onerror=null;this.src=\'./assets/images/default_user.png\';" style="border-radius: 50%" width="30px" height="30px">' + "</td>" +
                                "<td>" + row.name + "</td>" +
                                "<td>" + row.date + "</td>" +
                                "<td>" + (row.fromName == null ? row.name : row.fromName) + "</td>" +
                                "<td>" + row.text + "</td>" +
                            "</tr>";
                $('#chat_list').append(html);
            }
            setTimeout( _ => {
                function imageLoaded() {
                    counter--;
                    if (counter === 0) {
                        $('#modalLoading').modal('hide');
                        $('#page_body').show();
                    }
                }
                let images = $('img');
                let counter = images.length;
                images.each(function () {
                    if (this.complete) {
                        imageLoaded.call(this);
                    } else {
                        $(this).one('load', imageLoaded);
                    }
                });
            }, 1000);
            
        },
        error: (e) => {
            $('#modalLoading').modal('hide');
            $('#modalHash').modal('hide');
            $('#alertText').text('Si è verificato un errore durante l\'elaborazione.');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 4000);
        }
    });
};

$(document).ready(function () {
    if ($(window).width() > 992) {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 5) {
                $('#navbar_top').addClass("fixed-top");
                // add padding top to show content behind navbar
                $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
            } else {
                $('#navbar_top').removeClass("fixed-top");
                // remove padding top from body
                $('body').css('padding-top', '0');
            }
        });
    }
});