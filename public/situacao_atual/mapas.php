<?php
$unidade = $_GET['un'];
$tempo = $_GET['temp'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title></title>
        <style>
        body{
            width:98%;
        }
        #img{
            display:block;
            text-align:center;
            background-repeat:no-repeat;
            background-size:contain;
            margin-left:10%;
            width:80%;
            height:90%;
            min-height:500px;
            background-image:url('./media/focos_<?= $unidade ?>_<?= $tempo ?>_mapa.png');
        }
        #leg{
            display:block;
            text-align:center;
            background-repeat:no-repeat;
            background-size:contain;
            margin-left:20%;
            width:60%;
            height:10%;
            min-height:40px;
            background-image:url('../images/v_focos_<?= $unidade ?>_<?= $tempo ?>.png');
        }
        </style>
    </head>
    <body>
        <div id="img"></div>
        <div id="leg"></div>
    </body>
</html>