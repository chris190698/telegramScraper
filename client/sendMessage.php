<?php

require __DIR__ . '/functions/checkToken.php';
require __DIR__ . '/functions/getContacts.php';
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Invia messaggio";
require 'layouts/head.php';

?>

<body class="text-center">
<nav id="navbar_top" class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">
        <img src="<?php echo $link . '/assets/images/logo.svg'; ?>" width="30" height="30"
             class="d-inline-block align-top" alt="">
        Telegram Scraper
    </a>
    <button id="logout" class="btn btn-danger" type="button">Logout</button>
</nav>
<div id="page_body" class="container" style="display:none;">
    <div class="alert alert-danger fade" id="alertError" style="margin-top: 5px;" role="alert">
        <strong>Errore: </strong><span id="alertText">Seleziona almeno un utente.</span>
    </div>
    <fieldset class="border mt-3 p-2">
        <legend>Seleziona gli utenti</legend>
        <div class="row mt-3">
            <div class="col-4 form-check form-switch ps-5" style="padding-right: 200px;">
                    <input class="form-check-input" type="checkbox" id="check_all_chats">
                    <label class="form-check-label" for="check_all_chats">Seleziona tutti gli utenti</label>
            </div>
            <div class="col-4" style="margin: auto 0"></div>
            <div class="col-4"><input class="form-control" id="search" type="text" placeholder="Cerca tra gli utenti...">
            </div>
        </div>
        <div class="row mt-3" style="height: 300px;overflow: auto;">
            <div class="col tableFixHead">
                <table id="idTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Immagine profilo</th>
                        <th scope="col">Nome e Cognome</th>
                        <th scope="col">Seleziona</th>
                    </tr>
                    </thead>
                    <tbody id="chat_list">
                    <?php
                    foreach($contactList as $contact) { ?>
                        <tr>
                                <td><img name="img" src="./functions/profilePicture.php?peerType=peerUser&peerIdType=user_id&peerId=<?php echo $contact->user_id ?>" onerror="this.onerror=null this.src="assets/images/default_user.png" style="border-radius: 50%" width="30px" height="30px"></td>
                                <td> <p><?php echo $contact->name ?></p></td>
                                <td><input type="checkbox" name="user"></td>
                                <input type="hidden" value="<?php echo $contact->user_id?>" name="user_id">
                                <input type="hidden" value="<?php echo $contact->name?>" name="nameUser">
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend>Inserisci il tuo messaggio</legend>
        <div class="row mt-4">
            <div class="col">
                <textarea class="form-control" name="textMessage" ></textarea>
            </div>
        </div>
    </fieldset>
    <div class="row mt-3">
        <div class="col-6">
            <button id="back" style="width: 80%;" class="btn btn-danger" type="button">Torna indietro</button>
        </div>
        <div class="col-6">
            <button id="sendMsg" style="width: 80%;" class="btn btn-info" type="button">Invia messaggio</button>
        </div>
    </div>
    <p class="mt-5 pb-2 text-muted">TG Scraper &copy; 2020-<?php echo date('Y'); ?></p>
</div>

<!-- Modal -->
<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Caricamento dei contatti in corso...</h5>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div id="modalStripe" class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalResult" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Messaggio inviato correttamente</h5>
            </div>
            <div class="modal-body">
                <ul style="list-style-type:none;">
                    <li><b>Messaggio: </b><p id="TxtMessage" class="text-break"></p></li>
                    <li><b>Destinatari: </b><p id="receivers" class="text-break"></p></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/sendMessage.js"></script>
</body>
</html>