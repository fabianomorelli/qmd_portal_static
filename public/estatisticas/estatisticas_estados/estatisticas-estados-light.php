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
$url_base = "../estatisticas_estados_light/grafico_";
$url_qmd = "//queimadas.dgi.inpe.br/queimadas/";
//$lbl_hoje = date("d/m/Y");
?>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="pragma" content="no-cache">
    <title>Monitoramento dos Focos Ativos por Estado, Região ou Bioma - Programa Queimadas - versão light</title>
<script>
        function updateRefs(val) {
            if(val=="")return;
            document.getElementById('link_tabela_hist').href='<?=$url_base?>historico_estado_'+val+'_titulo.html?_=<?=$date?>';
            document.getElementById('link_tabela_hist_mes_atual').href = '<?=$url_base?>historico_mes_atual_estado_'+val+'_titulo.html?_=<?= $date ?>';
            document.getElementById('link_serie_hist').href='<?=$url_base?>serie_historica_estado_'+val+'.html?_=<?=$date?>';
            document.getElementById('link_comparativo_saz').href='<?=$url_base?>comparativo_sazonal_estado_'+val+'.html?_=<?=$date?>';
            document.getElementById('link_comparativo_prim').href='<?=$url_base?>comparativo_primeiro_semestre_estado_'+val+'.html?_=<?=$date?>';
            document.getElementById('link_comparativo_seg').href='<?=$url_base?>comparativo_segundo_semestre_estado_'+val+'.htm?_=<?=$date?>l';
        }

        function changedVis(val) {
            document.getElementById('estado_area').style = "display: none";
            document.getElementById('regiao_area').style = "display: none";
            document.getElementById('bioma_area').style = "display: none";
            document.getElementById(val+'_area').style = "display: unset";
            updateRefs(document.getElementById("select_"+val).value);
        }
        window.onload = function(){changedVis('estado')}
</script>
<style>
</style>
</head>
<body>
<h5><a href="https://gov.br">BRASIL</a></h5>
<h4><a href="//www.inpe.br">INSTITUTO NACIONAL DE PESQUISAS ESPACIAIS</a></h4>
<h1><a href="<?=$url_qmd?>">Programa Queimadas</a></h1>
<hr>
<h1>Monitoramento dos Focos Ativos por Estado, Região ou Bioma - versão light</h1>
<a href="<?=$url_qmd?>portal-static/estatisticas_estados/">Alternar para visualização padrão</a>

<p>
    <label>Selecione o filtro que deseja aplicar:</label>
    <select id="select_visualizacao" onchange="changedVis(this.value)">
        <option value="estado">Estado</option>
        <option value="regiao">Região</option>
        <option value="bioma">Bioma</option>
    </select>
</p>
<p>
    <p id="estado_area"> 
        <label>Selecione o estado:</label>
        <select id="select_estado" onchange="updateRefs(this.value)">
            <option value="acre">Acre</option><option value="alagoas">Alagoas</option><option value="amapa">Amapá</option><option value="amazonas">Amazonas</option>
            <option value="bahia">Bahia</option><option value="ceara">Ceará</option><option value="distrito_federal">Distrito Federal</option>
            <option value="espirito_santo">Espírito Santo</option><option value="goias">Goiás</option><option value="maranhao">Maranhão</option>
            <option value="mato_grosso">Mato Grosso</option><option value="mato_grosso_do_sul">Mato Grosso do Sul</option>
            <option value="minas_gerais">Minas Gerais</option><option value="para">Pará</option><option value="paraiba">Paraíba</option><option value="parana">Paraná</option>
            <option value="pernambuco">Pernambuco</option><option value="piaui">Piauí</option><option value="rio_de_janeiro">Rio de Janeiro</option>
            <option value="rio_grande_do_norte">Rio Grande do Norte</option><option value="rio_grande_do_sul">Rio Grande do Sul</option>
            <option value="rondonia">Rondônia</option><option value="roraima">Roraima</option><option value="santa_catarina">Santa Catarina</option>
            <option value="sao_paulo" selected="selected">São Paulo</option><option value="sergipe">Sergipe</option>
            <option value="tocantins">Tocantins</option>
        </select>
    </p>

    <p id="regiao_area" style="display: none">
        <label>Selecione a região:</label>
        <select id="select_regiao" onchange="updateRefs(this.value)">
            <option value="norte">Norte</option><option value="nordeste">Nordeste</option><option value="centro-oeste">Centro-Oeste</option>
            <option value="sul">Sul</option><option value="sudeste">Sudeste</option><option value="amazonia_legal">Amazônia Legal</option>
            <option value="vale_do_paraiba">Vale do Paraíba</option>
        </select>
    </p>

    <p id="bioma_area" style="display: none">
        <label>Selecione o bioma:</label>
        <select id="select_bioma" onchange="updateRefs(this.value)">
            <option value="caatinga">Caatinga</option><option value="cerrado">Cerrado</option>
            <option value="pantanal">Pantanal</option><option value="pampa">Pampa</option><option value="amazonia">Amazônia</option>
            <option value="mata_atlantica">Mata Atlântica</option>
        </select>
    </p>
</p>
<ul>
    <li><a id="link_tabela_hist" href="#" target="_blank">Tabela de histórico de focos ativos</a></li>
    <li><a id="link_tabela_hist_mes_atual" href="#" target="_blank">Tabela de comparação do total de focos ativos detectados dia a dia pelo satélite de referência para a data de <?= $label ?>.</a></li>
    <li><a id="link_serie_hist" href="#" target="_blank">Gráfico da série Histórica</a></li>
    <li><a id="link_comparativo_saz" href="#" target="_blank">Gráfico de comparativo mensal</a></li>
    <li><a id="link_comparativo_prim" href="#" target="_blank">Gráfico de comparativo 1º semestre</a></li>
    <li><a id="link_comparativo_seg" href="#" target="_blank">Gráfico de comparativo 2º semestre</a></li>
</ul>
</body>
</html>
