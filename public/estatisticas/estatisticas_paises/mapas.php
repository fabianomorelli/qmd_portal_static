<?php
$tmp = strip_tags($_GET["tmp"]);
$un = strip_tags($_GET["un"]);
$dt = date("dmYh");
$img = "../focos_".$un."_".$tmp."_mapa.png?d=$dt";
$leg = "../leg_focos_".$un."_".$tmp.".png?d=$dt";
$leg = "../legenda.png?d=$dt";
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title></title>
        <style>
        body{width:98%;}
         #img{display:block;text-align:center;background-repeat:no-repeat;background-size:contain;margin-left:10%;width:80%;height:90%;min-height:500px;background-image:url('<?= $img ?>');}
         #leg{display:block;text-align:center;background-repeat:no-repeat;background-size:contain;margin-left:20%;width:60%;height:10%;min-height:40px;background-image:url('<?= $leg ?>');}
        </style>
    </head>
    <body>
        <div id="img"></div>
        <div id="leg"></div>
    </body>
</html>