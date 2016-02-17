<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim(
    array(
        'templates.path' => __DIR__ . DIRECTORY_SEPARATOR . 'views',
    )
);

$app->setName( 'AppRaffle' );
$db = new PDO('sqlite:' . __DIR__ . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'database');

$app->get('/', function() use( $app, $db ) {
    $app->render( 'view.php' );
});

$app->post( '/saveRaffle', function() use( $app, $db ) {
    $ticket = $app->request()->post( 'ticket' );
    $toastId = $app->request()->post( 'toastId' );

    $sth = $db->query( 'INSERT INTO participantesBrindes (ticket, brindeId ) values ( \'' . $ticket . '\', ' . $toastId . ' );' );
    if ( $sth === false ) return;

    echo json_encode( $db->lastInsertId() );
});

$app->get( '/getToast', function() use( $app, $db ) {
    $sth = $db->query( 'SELECT * from brindes where brindeId not in ( SELECT brindeId from participantesBrindes ) order by brindeId limit 1;' );
    $toast = $sth->fetch( PDO::FETCH_ASSOC );

    echo json_encode( $toast );
});

$app->get( '/getRaffleds', function() use( $app, $db ) {
    $sth = $db->query( 'SELECT participantes.ticket, participantesBrindes.participanteBrindeId as pbid, participantes.nome, participantes.email, participantes.empresa, participantes.cpf, brindes.descricao from participantes inner join participantesBrindes on participantes.ticket = participantesBrindes.ticket inner join brindes on participantesBrindes.brindeId = brindes.brindeId order by participantesBrindes.ParticipanteBrindeId asc;' );
    $raffleds = '';
    if ($sth) {
        $raffleds = $sth->fetchAll( PDO::FETCH_ASSOC );
    }
    echo json_encode( $raffleds );
});

$app->get( '/raffle', function() use( $app, $db ) {
    $sth = $db->query( 'SELECT ticket, nome from participantes where ticket not in ( SELECT ticket from participantesBrindes ) and ticket not in ( SELECT ticket from participantesExcluidos ) order by nome;' );
    $users = $sth->fetchAll( PDO::FETCH_ASSOC );

    if ( empty( $users ) ) return;

    $indexRandomized = rand(0, count( $users ) - 1);

    sleep(1);

    echo json_encode( $users[$indexRandomized] );
});

$app->post( '/raffleUserExclude', function() use( $app, $db ) {
    $ticket = $app->request()->post( 'ticket' );
    if ( empty( $ticket ) ) {
        return;
    }

    $sth = $db->query( 'INSERT INTO participantesExcluidos (ticket) values ( \'' . $ticket . '\' );' );
    if ( $sth === false ) return;

    echo json_encode( true );
});

$app->post( '/deleteRaffle', function() use( $app, $db ) {
    $pbid = $app->request()->post( 'pbid' );
    if ( empty( $pbid ) ) {
        return;
    }

    $sth = $db->query( 'DELETE from participantesBrindes where participanteBrindeId = \'' . addslashes( $pbid ) . '\';' );
    echo json_encode( 'ok' );
});

$app->run();