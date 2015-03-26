<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Sorteio</title>
        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="css/sorteio.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            var raffled = '';

            $(document).ready(function() {

                getToast();
                getRaffleds();

                $('#button').on('click', function(e) {
                    e.preventDefault();
                    $('#participant').attr('readonly', 'readonly');
                    raffle();
                });

                $('#btnConfirm').live('click', function(e) {
                    e.preventDefault();
                    confirmRaffle();
                });

                $('#btnRaffleAgain').live('click', function(e) {
                    e.preventDefault();
                    raffle();
                });

                $('#btnRaffleUserExclude').live('click', function(e) {
                    e.preventDefault;
                    raffleUserExclude();
                });

                $( 'input[name=btnDelete]' ).live( 'click', function(e) {
                    e.preventDefault();
                    deleteRaffled(this.id);
                });
            });

            function getToast()
            {
                $.ajax({
                    url: 'getToast',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data == false) {
                            $('#containerToast').hide();
                            if ( $('#sorted tbody tr').length ) {
                                $('#containerToastMessage').html( '<h1>Obrigado pela participação!</h1>' );
                            } else {
                                $('#containerToastMessage').html( '<h1>Nenhum brinde a ser sorteado!</h1>' );
                            }
                            $('#containerToastMessage').show();
                            return;
                        }
                        $('#containerToast').show();
                        $('#containerToastMessage').hide();
                        $('#toastId').val( data.brindeId );
                        $('#toastDescription').val( data.descricao );
                        $('#toastDescriptionTitle').html( data.descricao );
                    },
                    error: function() {
                    }
                });
            }

            function getRaffleds()
            {
                $.ajax({
                    url: 'getRaffleds',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data == false) {
                            $('#containerSorted').hide();
                            return;
                        }
                        $('#containerSorted').show();
                        $.each( data, function( index, value ) {
                            $('#sorted tbody').append('<tr><td>' + value.ticket + '</td><td>' + value.nome + '</td><td>' + value.descricao + '</td><td><input class="btn-danger btn-mini" id="' + value.pbid + '" type="button" name="btnDelete" value="Apagar"></td></tr>');
                        });
                    },
                    error: function() {
                    }
                });
            }

            function confirmRaffle()
            {
                $.ajax({
                    url: 'saveRaffle',
                    type: 'POST',
                    dataType: 'json',
                    data: 'ticket=' + raffled.ticket + '&toastId=' + $('#toastId').val(),
                    success: function(data) {
                        if (data == null) {
                            return;
                        }

                        $('#sorted tbody').append('<tr><td>' + raffled.ticket + '</td><td>' + raffled.participant + '</td><td>' + $('#toastDescription').val() + '</td></td><td><input class="btn-danger btn-mini" id="' + data + '" type="button" name="btnDelete" value="Apagar"></td></tr>');
                        $('#containerSorted').show();

                        raffled = '';
                        getToast();
                    },
                    error: function() {
                    }
                });
            }

            function raffleUserExclude()
            {
                $.ajax({
                    url: 'raffleUserExclude',
                    type: 'POST',
                    dataType: 'json',
                    data: 'ticket=' + raffled.ticket,
                    success: function(data) {
                        if (data == null) {
                            return;
                        }

                        raffled = '';
                        getToast();
                    },
                    error: function() {
                    }
                });
            }

            function raffle()
            {
                $.ajax({
                    url: 'raffle',
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#loading').modal();
                    },
                    success: function(data) {

                        $('#loading').modal('hide');

                        if (data == null) {
                            window.alert('Todos os nomes foram sorteados');
                            $('#button').attr('disabled', 'disabled');
                            return;
                        }

                        $('#myModal').modal();
                        $('#participant').html( data.nome );

                        raffled = {'ticket':data.ticket, 'participant':data.nome};
                    },
                    error: function() {
                    }
                });
            }

            function deleteRaffled(id)
            {
                $.ajax({
                    url: 'deleteRaffle',
                    type: 'POST',
                    dataType: 'json',
                    data: 'pbid=' + id,
                    success: function(data) {
                        if (data == null) {
                            window.alert('Não foi possível apagar o registro :(');
                            return;
                        }

                        $( '#' + id ).parent( "td" ).parent("tr").remove();
                        getToast();

                        raffled = '';
                    },
                    error: function() {
                    }
                });
            }
        </script>
    </head>
    <body>
        <div style="background-color: #fea927; overflow: hidden; height: 150px;">
            <div style="width: 170px; overflow: hidden; float: left;"><img src="img/logo.png" style="width: 85%; padding: 15px;"></div>
            <div style="float: left; font-family: Raleway; font-size: 54px; color: #fff; line-height: 150px;">Web Dev Summit 2015</div>
        </div>
        <div class="container">
            <div id="containerToast" class="container hide">
                <div style="overflow: hidden;">
                    <h1 style="float: left;">Prêmio: </h1><h2 id="toastDescriptionTitle" style="float: left;"></h2>
                    <input id="toastId" type="hidden" value="" />
                    <input id="toastDescription" type="hidden" value="" />
                </div>
                <input id="button" type="submit" value="Sortear" class="btn-primary btn-large" />
            </div>
            <div id="containerToastMessage" class="container hide" style="text-align: center; padding: 20px 0;">
            </div>
            <div id="containerSorted" class="hide">
                <h2>Sortudos</h2>
                <table id="sorted" class="table table-bordered table-striped">
                    <thead>
                        <tr><th scope="col">Ticket</th><th scope="col">Nome</th><th scope="col">Prêmio</th><th width="56px">&nbsp;</th></tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal hide" id="loading">
            <div class="modal-body">
                <p style="text-align: center;">
                    Aguarde enquanto o sorteio é efetuado...
                </p>
                <p style="text-align: center;">
                    <img src="img/loadingAnimation.gif" />
                </p>
            </div>
        </div>

        <div class="modal hide" id="myModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>E o sortudo foi:</h3>
            </div>
            <div class="modal-body">
                <h1 id="participant"></h1>
            </div>
            <div class="modal-footer">
                <button id="btnConfirm" type="button" class="btn-primary btn-large" data-dismiss="modal">Confirmar</button>
                <button id="btnRaffleAgain" type="button" class="btn-primary btn-large" data-dismiss="modal">Sortear novamente</button>
                <button id="btnRaffleUserExclude" type="button" class="btn-primary btn-large" data-dismiss="modal">Remover do sorteio</button>
            </div>
        </div>
    </body>
</html>