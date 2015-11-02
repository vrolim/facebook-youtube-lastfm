<?php
    require_once("lib/nusoap.php");
    $wsdl = "http://localhost:3434/genevieve/ws/services.php?wsdl";

    //create client object
    $client = new nusoap_client($wsdl, 'wsdl');


    //call second function which return complex type
    $result = $client->call('history', array('token' => '10203096794700362', 'data' => '2015-07-04'));
    //$result2 would be an array/struct
    $result2 = $client->call('getFavorite', array('token' => '10203096794700362'));
    //  $client = new nusoap_client($wsdl, 'wsdl');
    //
    $err = $client->getError();
    if ($err) {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        echo '<h2>Debug</h2>';
        echo '<pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
        exit();
    }
    //
    //$dados = array('token' => 'CAAWokMj9E4gBAGuLf4CUuxAuF15Gd8jVzq6NgwegQsQkPpGyPs8HxgWuzGwWw3u8Lb8vEOKgLzlVFZCYCMsvPfAQreEkzH4fnoofq3yZCzxvNdPT4QfOMSJH7iAbFn66Mx99RxrGXKvGHAztxelqfx2ttZBU04Ry7ZBPEbieQEZAutIF2SQdR5luEJo8SZAXc5yaZBmjbR0BG3cQtax0IdCUjJZBZA9c0SY4ZD',
    //    'data' => '2015-06-30'
    //);
    //$result = $client->call('history',$dados);
    if ($client->fault) {
        echo '<h2>Fault</h2><pre>';
        print_r($result);
        echo '</pre>';
    } else {
        $err = $client->getError();
        if ($err) {
            echo '<h2>Error</h2><pre>' . $err . '</pre>';
        } else {
            echo '<h2>Result</h2><pre>';
            // Decode the result: it so happens we sent Latin-1 characters
            if (isset($result['return'])) {
                $result1 = utf8_decode($result['return']);
            } elseif (!is_array($result)) {
                $result1 = utf8_decode($result);
            } else {
                $result1 = $result;
            }
    //        //$result1 = json_decode($result1, true);
             print_r($result);
             print_r($result2);
    //        //$_SESSION['resultado'] = $result1;
    //
            echo '</pre>';
        }
    }
?>