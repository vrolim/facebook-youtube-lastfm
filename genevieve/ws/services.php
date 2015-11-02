<?php
require_once('lib/nusoap.php');

$server = new nusoap_server();

$server->configureWSDL('server', 'urn:server');

$server->wsdl->schemaTargetNamespace = 'urn:server';

//$server->debug_flag = true;


$server->register('history'
    , array('token' => 'xsd:string')
    , array('return' => 'tns:ListArrays')
    , 'urn:server'   //namespace
    , 'urn:server#historyList'
    , 'rpc'
    , 'encoded'
    , 'Retorna o historico do dia especificado pelo usuario');



//SOAP complex type return type (an array/struct)
$server->wsdl->addComplexType(
    'Array_primary',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'song_name' => array('name' => 'song_name', 'type' => 'xsd:string'),
        'artist' => array('name' => 'artist', 'type' => 'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'Array_secondary'
    , 'complexType'
    , 'array'
    , ''
    , 'SOAP-ENC:Array'
    , array()
    , array(
        array(
            'ref' => 'SOAP-ENC:arrayType',
            'wsdl:arrayType' => 'tns:Array_primary[]'
        )
    )
);

$server->wsdl->addComplexType(
    'ListArrays'
    , 'complexType'
    , 'array'
    , ''
    , 'SOAP-ENC:Array'
    , array()
    , array(
        array(
            'ref' => 'SOAP-ENC:arrayType',
            'wsdl:arrayType' => 'tns:Array_secondary[]'
        )
    )
);

function history($token){
    include_once('../connections.php');
    $result = mysqli_query($conn, "SELECT * from historico WHERE user_id='$token'");
    $lista = array();
    $aux = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $parcial = array();
            $parcial[] = $row['song_name'];
            $parcial[] = $row['artist'];
            $parcial[] = $row['data_listen'];
            $aux[] = $parcial;
        }
        $lista[] = $aux;
    }
    return $lista;
}

$server->register('getFavorite'
    , array('token' => 'xsd:string')
    , array('return' => 'tns:ListArrays')
    , 'urn:server'   //namespace
    , 'urn:server#numberPlayed'
    , 'rpc'
    , 'encoded'
    , 'Retorna a musica mais reproduzida pelo usuario');

function getFavorite($token){
    include_once('../connections.php');
    $result = mysqli_query($conn, "SELECT count(*) as played,song_name,artist FROM historico where user_id='$token' group by song_name order by played desc limit 1 ");
    $favorite = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $favorite[] = array($row['song_name'],$row['artist']);
        }
    }
    return $favorite;
}

$server->register('calcEstatic'
    , array('favoriteSong' => 'xsd:string', 'favoriteArtist' => 'xsd:string', 'token' => 'xsd:string')
    , array('return' => 'xsd:int')
    , 'urn:server'   //namespace
    , 'urn:server#dataUser'
    , 'rpc'
    , 'encoded'
    , 'Retorna dados de consumo do usuario');

function calcEstatic($favoriteSong,$favoriteArtist,$token){
    include_once('../connections.php');
    $count = mysqli_query($conn, "SELECT count(*) as played FROM historico where user_id='$token' and song_name='$favoriteSong' and artist='$favoriteArtist'");

    $result = mysqli_query($conn, "SELECT count(*) as played FROM historico where user_id='$token'");
    if ($result->num_rows > 0) {
        while (($row = $result->fetch_assoc()) & ($row2 = $count->fetch_assoc())) {
            if(intval($row['played'])!=0){
                $total = intval($row2['played'])*100/intval($row['played']);
            }else{
                $total = 0;
            }
        }
    }
    return $total;
}

$server->register('getData'
    , array('token' => 'xsd:string')
    , array('return' => 'xsd:int')
    , 'urn:server'   //namespace
    , 'urn:server#dataUser'
    , 'rpc'
    , 'encoded'
    , 'Retorna dados de consumo do usuario');

function getData($token){
    include_once('../connections.php');
    $wsdl = "http://localhost:3434/genevieve/ws/services.php?wsdl";

    //create client object
    $client = new nusoap_client($wsdl, 'wsdl');

    $favorite = $client->call('getFavorite', array('token' => $token));
    $favoriteSong = $favorite[0]['item'][0];
    $favoriteArtist = $favorite[0]['item'][1];

    $estatistica = $client->call('calcEstatic', array('favoriteSong' => $favoriteSong, 'favoriteArtist' => $favoriteArtist, 'token' => $token));

    return $estatistica;
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>