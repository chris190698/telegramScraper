<?php
require __DIR__ . '/functions/checkToken.php';
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Gestione gruppi";
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
        <legend>Aggiungi partecipanti ad un gruppo</legend>
        <div class="row mt-4">
        
            <div class="row mt-3">
                <div class="col"></div>
                <div class="col-4">
                    <div class="form-check">
                        <label class="mb-2">Nome Gruppo</label>
                        <input type="text" name="groupTitle" class="form-control" />
                    </div>
                    
                </div>
                <div class="col"></div>
            </div>
            <div class="row mt-3">
                <div class="col"></div>
                <div class="col-4">
                    <div class="form-check">
                        <label class="mb-2">CSV</label>
                        <input type="file" accept=".csv" name="groupFile" class="form-control">
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div class="row mt-4">
                <div class="col"></div>
                <div class="col-4">
                    <button type="button" id="back" style="width: 100%;" class="btn btn-danger">Torna indietro</button>
                </div>
                <div class="col-4">
                    <button type="button" id="createGroup" style="width: 100%;" class="btn btn-success">Carica</button>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </fieldset>
</div>
<!-- Modal -->
<div class="modal fade" id="modalResult" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Gruppo creato correttamente</h5>
            </div>
            <div class="modal-body">
                <ul style="list-style-type:none;">
                    <li><b>Nome: </b><p id="TxtMessage" class="text-break"></p></li>
                    <li><b>Partecipanti: </b><p id="receivers" class="text-break"></p></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/groups.js"></script>
</body>
</html>