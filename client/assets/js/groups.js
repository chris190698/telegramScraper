$('#createGroup').on('click', _ =>{
    if($('input[name="groupTitle"]').val() !== ""){
        if($('input[name="groupFile"]').get(0).files.length !== 0){
            createGroup();
        }else{
            $('#alertText').text('Necessario caricare il file');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 3000);
        }
    }else{
        $('#alertText').text('Inserisci il titolo del gruppo');
        $('#alertError').addClass('show');
        setTimeout(_ => $('#alertError').removeClass('show'), 3000);
    }
});

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

createGroup = ()=> {
    $('#modalStripe').attr('aria-valuenow', 100).width('100%');
    var formData = new FormData();
    formData.append("titleGroup", $('input[name="groupTitle"]').val());
    formData.append("groupFile", $('input[name="groupFile"]')[0].files[0]);
    formData.append("createGroup", 1);
    $.ajax({
        type: "POST",
        url: serverUrl + "functions/createGroup.php",
        processData: false,
        contentType: false,
        data: formData,
        timeout: 0,
        success: (result) => {
            $('input[name="groupTitle"]').val("");
            $('input[name="groupFile"]').val("");
            $('#TxtMessage').text(result.title);
            var list = "";
            for(var i=0; i < result.users.length; i++){
                list += result.users[i] + "<br>";
            }
            $('#receivers').html(list);
            $('#modalResult').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
        },
        error: (e) => {
            $('#modalLoading').modal('hide');
            $('#modalHash').modal('hide');
            $('#alertText').text('Si è verificato un errore durante l\'elaborazione della richiesta.');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 4000);
        }
    });
}