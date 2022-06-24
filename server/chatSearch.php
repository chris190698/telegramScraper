<?php

require __DIR__ . '/functions/checkToken.php';
require __DIR__.'/functions/getDialogs.php';
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
<div id="page_body" class="container" >
    <div class="alert alert-danger fade" id="alertError" style="margin-top: 5px;" role="alert">
        <strong>Errore: </strong><span id="alertText">Seleziona almeno una chat.</span>
    </div>
    <fieldset class="border mt-3 p-2">
        <legend>Imposta i parametri</legend>
        <p class="text-center">Seleziona almeno un criterio di ricerca, oltre la parola da cerca (max 200 risultati)</p>
        <div class="row mt-4">
            <div class="col"><label for="dataInizio" class="mb-2">Parola:</label> 
            <input  class="form-control" name="wordSearch" />
                <div class="invalid-feedback">Inserisci una parola da ricercare</div>
            </div>
            <div class="col"><label for="dataFine" class="mb-2">Chat:</label> 
                <select name="selectChat" class="form-control">
                    <option disabled selected value="">Seleziona una chat</option>
                    <option value="">Tutte</option>
                    <?php 
                    foreach($chat_list as $chat){ ?>
                    <option data-peertype="<?php echo $chat['peer']['_']?>" data-peeridtype="<?php echo array_keys($chat['peer'])[1]?>" value="<?php echo $chat['id']?>"><?php echo $chat['name'] ?></option>
                    <?php 
                    }
                    ?>
                </select>
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <label for="dataInizio" class="mb-2">Data inizio:</label> 
                <input id="dataInizio" name="dataInizio" type="date" value='<?php echo date('Y-m-d'); ?>' min='2013-08-14' class="form-control" max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
            <div class="col">
                <label for="dataFine" class="mb-2">Data fine:</label> 
                <input id="dataFine" name="dataFine" type="date" value='<?php echo date('Y-m-d'); ?>' min='2013-08-14' class="form-control" max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data fine maggiore della data di inizio</div>
            </div>
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-6">
                <button class="btn btn-danger" id="back" style="width: 80%;">Torna indietro</button>
            </div>
            <div class="col-6">
                <button class="btn btn-success" id="searchMsg" style="width: 80%;">Ricerca</button>
            </div>
        </div>
    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend>Risultati</legend>
        <div class="row" style="height: 300px;overflow: auto;">
            <div class="col tableFixHead">
                <table id="idTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Immagine profilo</th>
                        <th scope="col">Chat</th>
                        <th scope="col">Data</th>
                        <th scope="col">Inviato da</th>
                        <th scope="col">Messaggio</th>
                    </tr>
                    </thead>
                    <tbody id="chat_list">
                
                    </tbody>
                </table>
            </div>
        </div>

    </fieldset>
</div>


<!-- Modal -->
<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Ricerca in corso...</h5>
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
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/chatSearch.js"></script>
</body>
</html>