$('#back').click(function(){
    window.location.href = "message.php";
});

$('#sendMsg').on('click', _ => {
    if (getCheckedContacts().length > 0 || $('input[name="userCsv"]').get(0).files.length !== 0) {
        if($('input[name="messageTextSend"]').val() !== ""){
            sendMsg();
        }else{
            $('#alertText').text('Inserisci il corpo del messaggio');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 3000);
        }
        
    } else {
        $('#alertText').text('Seleziona almeno un contatto oppure carica un file csv');
        $('#alertError').addClass('show');
        setTimeout(_ => $('#alertError').removeClass('show'), 3000);
    }
});

$("#check_all_chats").click(function () {
    if($("#check_all_chats").is(":checked")) {
        $('#checkboxlist').find('input:checkbox').prop("disabled", "disabled");
        $('#search').prop("disabled", "disabled");
    }else{
        $('#checkboxlist').find('input:checkbox').prop("disabled", false);
        $('#search').prop("disabled", false);
    }
    $("#chat_list tr").filter(":visible").filter(function () {
    $(this).find("input[type=checkbox][name='user']").not(this).prop('checked', $("#check_all_chats").prop('checked'));
    });
});

sendMsg = (contacts = getCheckedContacts()) =>{
    $('#modalStripe').attr('aria-valuenow', 100).width('100%');
    var formData = new FormData();
    formData.append("message", $('textarea[name="textMessage"]').val());
    formData.append("sendMessage", 1);
    if(contacts.length > 0){
        formData.append("contacts", JSON.stringify(contacts));
    }
    if($('input[name="userCsv"]').get(0).files.length !== 0){
        formData.append("userCSV", $('input[name="userCsv"]')[0].files[0]);
    }
    $.ajax({
        type: "POST",
        url: serverUrl + "functions/sendMessage.php",
        data: formData,
        processData: false,
        contentType: false,
        timeout: 0,
        success: (result) => {
            $('input[name="user"]:checked').each(function () {
                $(this).prop("checked", false);
            });
            $("#search").val("");
            $('input[name="userCsv"]').val("");
            $('textarea[name="textMessage"]').val("");
            $("#chat_list tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf("") > -1);
            });
            $('#TxtMessage').text(result[0].message);
            var list = "";
            for(var i=0; i < result.length; i++){
                list += result[i].contacts.name + "<br>";
            }
            $('#receivers').html(list);
            $('#modalResult').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
        },
        error: (e) => {
            $('#modalLoading').modal('hide');
            $('#modalHash').modal('hide');
            $('#alertText').text('Si è verificato un errore durante l\'invio del messaggio.');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 4000);
        }
    });
}

$(document).ready(function () {
    $('#modalLoading').modal({backdrop: 'static', keyboard: false, show: true, focus: true}).modal('show');

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

    $("#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var showAll = true;
        $("#chat_list tr").hide();  
        if (showAll) {
            $("#chat_list tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        }


    });
});

getCheckedContacts = () => {
    let contacts = [];
    $('input[name="user"]:checked').each(function () {
        contacts.push({
            "user_id": $(this).parent().parent().find("input[type='hidden'][name='user_id']").val(),
            "name": $(this).parent().parent().find("input[type='hidden'][name='nameUser']").val()
        });
    });
    return contacts;
}