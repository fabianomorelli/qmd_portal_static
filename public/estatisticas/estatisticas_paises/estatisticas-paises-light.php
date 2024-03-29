<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
$ontem = time() - (24*3600);
$label_date = (date("d")=='01'?$ontem:time());
$label_mes = $meses[intval(date("m", $label_date))-1];
$label_dias = cal_days_in_month(CAL_GREGORIAN,intval(date("m",$label_date)),intval(date("Y",$label_date)));
$date = date("mdyh");
$label = "01/".$label_mes." até ".$label_dias."/".$label_mes;
$lbl_ontem = date("d/m/Y",$ontem);
$url_base = '../grafico_';
$url_qmd = "//queimadas.dgi.inpe.br/queimadas/";
//$lbl_hoje = date("d/m/Y");
?>
<html lang="en"><head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Monitoramento dos Focos Ativos por País - Programa Queimadas - versão light</title>
    <script>
            function changed(pais) {
                document.getElementById('link_tabela_hist').href = '<?=$url_base?>historico_pais_'+pais+'_titulo.html?_=<?= $date ?>';
                document.getElementById('link_tabela_hist_mes_atual').href = '<?=$url_base?>historico_mes_atual_pais_'+pais+'_titulo.html?_=<?= $date ?>';
                document.getElementById('link_serie_hist').href = '<?=$url_base?>serie_historica_pais_'+pais+'.html?_=<?= $date ?>';
                document.getElementById('link_comparativo_saz').href = '<?=$url_base?>comparativo_sazonal_pais_'+pais+'.html?_=<?= $date ?>';
                document.getElementById('link_comparativo_prim').href = '<?=$url_base?>comparativo_primeiro_semestre_pais_'+pais+'.html?_=<?= $date ?>';
                document.getElementById('link_comparativo_seg').href = '<?=$url_base?>comparativo_segundo_semestre_pais_'+pais+'.html?_=<?= $date ?>';
            }
            window.onload = function(){changed('brasil')}
    </script>
</head>
<body>
    <h5><a href="//gov.br">BRASIL</a></h5>
    <h4><a href="//www.inpe.br">INSTITUTO NACIONAL DE PESQUISAS ESPACIAIS</a></h4>
    <h1><a href="<?=$url_qmd?>">Programa Queimadas</a></h1>
    <hr>
    <h1>Monitoramento dos Focos Ativos por País - versão light</h1>
    <a href="<?=$url_qmd?>portal-static/estatisticas_paises/">Alternar para visualização padrão</a>
	<p>
        <label>Selecione o país:</label>
        <select onchange="changed(this.value)">
            <option value="equador">Ecuador</option>
            <option value="uruguai">Uruguay</option>
            <option value="argentina">Argentina</option>
            <option value="bolivia">Bolivia</option>
            <option value="ilhas_malvinas">Falkland Islands</option>
            <option value="guyana_francesa">French Guiana</option>
            <option value="guyana">Guyana</option>
            <option value="brasil" selected="">Brasil</option>
            <option value="chile">Chile</option>
            <option value="colombia">Colombia</option>
            <option value="venezuela">Venezuela</option>
            <option value="paraguay">Paraguay</option>
            <option value="perú">Peru</option>
            <option value="suriname">Suriname</option>
            <option value="america_do_sul">América do Sul</option>
        </select>
    </p>
    <h2 id="title">Os dados abaixo compreendem o período entre 1998 e <?= $lbl_ontem ?>.</h2>
    <ul>

        <li><a id="link_tabela_hist" href="#" target="_blank">Tabela de histórico de focos ativos</a></li>
        <li><a id="link_tabela_hist_mes_atual" href="#" target="_blank">Tabela de comparação do total de focos ativos detectados dia a dia pelo satélite de referência para a data entre <?= $label ?>.</a></li>
        <li><a id="link_serie_hist" href="#" target="_blank">Gráfico da série histórica</a></li>
        <li><a id="link_comparativo_saz" href="#" target="_blank">Gráfico de comparativo mensal</a></li>
        <li><a id="link_comparativo_prim" href="#" target="_blank">Gráfico de comparativo 1º semestre</a></li>
        <li><a id="link_comparativo_seg" href="#" target="_blank">Gráfico de comparativo 2º semestre</a></li>
    </ul>
</body>
</html>
