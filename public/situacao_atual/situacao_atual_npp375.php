<?php
header('X-Frame-Options: GOFORIT');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$anteontem = time() - (2*24*3600);
$ontem = time() - (24*3600);
$date = date("mdyh");
$arr_meses = ["01"=>"Jan","02"=>"Fev","03"=>"Mar","04"=>"Abr","05"=>"Mai","06"=>"Jun","07"=>"Jul","08"=>"Ago","09"=>"Set","10"=>"Out","11"=>"Nov","12"=>"Dez"];
$dt_anteontem = date("Y-m-d",$anteontem);
$lbl_anteontem = date("d/m/Y",$anteontem);
$lbl_ontem = date("d",$ontem)."/".$arr_meses[date("m",$ontem)];
$lbl_hoje = date("d/m/Y",$ontem);
$lbl_dia_mes = date("d/m",$ontem);
$url_base = "./";
$url_qmd = "http://queimadas.dgi.inpe.br/queimadas/";
?>
<!DOCTYPE html>
<html>
<head lang="pt-br">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="title" content="Área Queimada - Programa Queimadas - INPE">
	<meta name="robots" content="index,follow">
    <title>Situação atual - Programa Queimadas - INPE</title>
    <link rel="stylesheet" href="../assets/css/page.min.css">
    <link rel="stylesheet" href="../assets/css/base_plugin.css?_=<?= $date ?>">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/jquery/jquery-ui.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/leaflet/fullscreen/leaflet.fullscreen.css">
    <link rel="stylesheet" href="../assets/leaflet/geosearch/geosearch.css">
  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
  <?php include_once("../assets/css/situacao-atual.css"); ?>
  </style>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript"></script>
  <script src="../assets/jquery/jquery-ui.js" type="text/javascript"></script>
  <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
  <script src="../assets/leaflet/fullscreen/Leaflet.fullscreen.min.js"></script>
  <script src="../assets/leaflet/geosearch/geosearch.js"></script>
	<script>
		var loaded=0,n_frames;
		function resizeIframe(obj){try{obj.style.height = (obj.id=="comparativa-regiao")?'100px':(obj.contentWindow.document.body.scrollHeight + 'px');}catch(e){console.log("erro:",e)}finally{loaded++;checkLoaded();}}
		function checkLoaded(){if(loaded>=n_frames-1){$(".loader").hide();}}
	</script>
</head>
<body>
	<!--<div id="barra-identidade" ><div id="barra-brasil"><div id="wrapper-barra-brasil"><div class="brasil-flag"><a href="//brasil.gov.br" class="link-barra">Brasil</a></div><span class="acesso-info"><a href="http://www.servicos.gov.br/?pk_campaign=barrabrasil" class="link-barra" id="barra-brasil-orgao">Serviços</a></span><nav><ul id="lista-barra-brasil" class="list"><li><a href="#" id="menu-icon"></a></li><li class="list-item first"><a href="http://www.simplifique.gov.br" class="link-barra">Simplifique!</a></li><li class="list-item"><a href="http://brasil.gov.br/barra#participe" class="link-barra">Participe</a></li><li class="list-item"><a href="http://brasil.gov.br/barra#acesso-informacao" class="link-barra">Acesso à informação</a></li><li class="list-item"><a href="http://www.planalto.gov.br/legislacao" class="link-barra">Legislação</a></li><li class="list-item last last-item"><a href="http://brasil.gov.br/barra#orgaos-atuacao-canais" class="link-barra">Canais</a></li></ul></nav><span id="brasil-vlibras"><a class="logo-vlibras" href="#"></a><span class="link-vlibras"><img src="http://barra.brasil.gov.br/imagens/vlibras.gif">&nbsp;<span>O conteúdo desse portal pode ser acessível em Libras usando o <a href="http://www.vlibras.gov.br">VLibras</a></span></span></span></div></div></div>-->
	<div id="wrapper">
		<div id="header">
			<div>
				<div class="container text-left">
					<ul id="accessibility" class="text-left">
						<li>
							<a accesskey="1" href="#acontent" id="link-conteudo">Ir para o conteúdo <span>1</span></a>
						</li>
						<li>
							<a accesskey="2" href="#anavigation" id="link-navegacao">Ir para o menu <span>2</span></a>
						</li>
						<li>
							<a accesskey="3" href="#asearch" id="link-buscar">Ir para a busca <span>3</span></a>
						</li>
						<li class="last-item">
							<a accesskey="4" href="#afooter" id="link-rodape">Ir para o rodapé <span>4</span></a>
						</li>
					</ul>
				</div>
				<div class="container text-right" >
<?php /*	<!-- <ul id="language" class="text-right">
						<li class="language-es">
								<a href="#">Espa&#241;ol</a>
						</li>
						<li class="language-en last-item">
								<a href="http://www.brazilgovnews.gov.br" target="_blank">English</a>
						</li>
					</ul> --> */ ?>
					<ul id="portal-siteactions" class="text-right">
<?php /*<!--<li id="siteaction-accessibility"><a href="http://www.brasil.gov.br/acessibilidade" title="Acessibilidade" accesskey="5">Acessibilidade</a></li> --> */ ?>
						<li id="siteaction-contraste"><a href="#" title="Alto Contraste" accesskey="6">Alto Contraste</a></li>
						<li id="siteaction-mapadosite" class="last-item"><a href="<?= $url_qmd ?>portal/mapadosite" title="Mapa do Site" accesskey="7">Mapa do Site</a></li>
					</ul>
				</div>
				<div class="container text-left align-top" id="container-logo">
					<div id="logo" class="text-left">
						<a title="" href="<?= $url_qmd ?>portal">
							<img src="../assets/images/simb_logo_conj.png" alt="" title="Programa Queimadas - INPE" class="align-top"/>
							<div class="align-top">
								<span id="portal-title-1">Programa</span>
								<h1 id="portal-title" class="corto">Queimadas</h1>
								<span id="portal-description">Instituto Nacional de Pesquisas Espaciais</span>
							</div>
						</a>
					</div>
				</div>
				<div class="container text-right" id="container-search" >
					<div id="portal-searchbox">
						<a name="asearch" id="asearch"></a>
						<form id="nolivesearchGadget_form" action="<?= $url_qmd ?>portal/@@busca">
							<fieldset class="LSBox">
								<input name="SearchableText" size="18" title="Buscar no portal" placeholder="Buscar no portal" class="searchField" id="nolivesearchGadget" type="text">
								<input class="searchButton" value="Buscar no portal" type="submit">
							</fieldset>
						</form>
					</div>
					<div id="social-icons">
						<ul>
							<li id="portalredes-flickr" class="portalredes-item">
								<a href="http://flickr.com/145994884@N08">Flickr</a>
							</li>
							<li id="portalredes-facebook" class="portalredes-item">
								<a href="http://facebook.com/queimadasinpe">Facebook</a>
							</li>				
<?php /* <!-- <li id="portalredes-twitter" class="portalredes-item">
								<a href="https://twitter.com/governodobrasil">Twitter</a>
							</li> --> */ ?>
							<li id="portalredes-youtube" class="portalredes-item">
								<a href="http://youtube.com/channel/UCkzF8NS-HadR2CHXw3msMmg">YouTube</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div id="sobre" >
				<ul>
					<li class="portalservicos-item">
						<a href="<?= $url_qmd ?>"><img src="<?= $url_qmd ?>portal/logotipoinpe-menor-azul.png" alt="INPE" title="INPE"></a>
					</li>
					<li id="portalservicos-perguntas-frequentes" class="portalservicos-item">
						<a href="<?= $url_qmd ?>portal/informacoes/perguntas-frequentes">Perguntas Frequentes</a>
					</li>
					<li id="portalservicos-noticias" class="portalservicos-item">
						<a href="<?= $url_qmd ?>noticias/">Notícias</a>
					</li>
					<li id="portalservicos-dados-abertos" class="portalservicos-item">
						<a href="<?= $url_qmd ?>dados-abertos/">Dados Abertos</a>
					</li>
					<li id="portalservicos-contato" class="portalservicos-item">
						<a href="mailto:queimadas@inpe.br">Contato</a>

					</li>
					<li class="portalservicos-item">
						<a href="<?= $url_qmd ?>diversidade/" title="Código de Conduta em Respeito à Diversidade" target="_blank">
							<img src="<?= $url_qmd ?>portal/diversidade.png" alt="Imagem do logo do Código de Conduta em Respeito à Diversidade">
						</a><a>
					</a></li>
				</ul>
			</div>
		</div>
<?php /*----------------------------------------------------------------------------------------------------------------------------------------------------
        CONTEÚDO DA PÁGINA 	
        ---------------------------------------------------------------------------------------------------------------------------------------------------- */?>
<div id="main">
<a name="anavigation" id="anavigation"></a>
<a name="acontent" id="acontent"></a>
<div class="content-mapa">
    <h1>Aviso de Mudança Temporária</h1>
    <p style="font-size:16px;">
        Devido a falta de dados do satélite de referência, que foi colocado em modo "Stand-by" pelo centro de rastreio e controle da NASA,
        temporáriamente a página de situação atual estará apresentando as estatísticas e comparações com dados obtidos pelo satélite 
         <span style="background-color: #F3774F">S-NPP-375</span> em suas
        passagens sobre a América do Sul no período da tarde após as 15hs GMT.
    </p>
  <h1>Situação Atual</h1>
  <span class="mensagem"><a href="<?= $url_qmd ?>portal-static/situacao-atual/situacao-atual-light.php">Ir para HTML básico (para casos de lentidão no carregamento)</a></span>
<!-- mapa -->
  <div class="mapa">
    <div id="dvTituloMapa">
        <span><span id="tituloMapa">Situação Atual &gt; Focos 2 dias</span><span id="subtituloMapa">Dados gerados em <?= $lbl_hoje ?>, entre 12 UTC e 18 UTC</span></span>
    </div>
    <div id="mapa" style=""></div>
    <img src="../assets/images/legenda_focos_de_queima.png" alt="Legenda de focos de queima" title="Legenda de focos de queima" id="legenda_mapa" />
    <div class="legenda">Atenção: os dados da camada de fumaça são apresentados com 2 dias de atraso (o mapa acima mostra a fumaça em <?= $lbl_anteontem ?>).</div>
    <div class="slider">
        <div id="slider"></div>
    </div>
</div>
  <h2>Dados: Apenas do satélite <span style="background-color: #F3774F; color: #fff;">S-NPP-375 TARDE TARDE</span></h2>
  <div class="estatisticas">
<!-- focos_pais -->
    <div id="div-focos-paises" class="estatistica">
        <div>
            <span class="titulo">Focos por país</span>
            <ul class="inline">
                <li title="Anual" onclick="changeTable('box-paises','ano_atual')"><i class="fas fa-calendar img_ano_atual"></i></li>
                <li title="Mensal" onclick="changeTable('box-paises','mes_atual')"><i class="fas fa-calendar-alt img_mes_atual"></i></li>
                <li title="Últimas 48 horas" onclick="changeTable('box-paises','48h')"><i class="fas fa-calendar-day img_48h"></i></li>
                <li></li>
                <li title="Exibir Tabela" onclick="changeTable('box-paises',null,'tabela')"><i class="fas fa-table img_tabela"></i></li>
                <li title="Exibir Gráfico em pizza" onclick="changeTable('box-paises',null,'pizza')"><i class="fas fa-chart-pie img_pizza"></i></li>
                <li title="Exibir Gráfico em barra" onclick="changeTable('box-paises',null,'barra')"><i class="fas fa-chart-bar img_barra"></i></li>
                <li title="Exibir Mapa" onclick="changeTable('box-paises',null,'mapa')"><i class="fas fa-map img_mapa"></i></li>
            </ul>
        </div>
        <span style="margin-left:11px;" class="descTempo">PERÍODO ANUAL: 2019</span>
        <iframe style="height:380px;" id="focos-paises"></iframe>
    </div>
    <div id="div-focos-estado" class="estatistica">
        <div>
<!-- focos_estado -->
            <span class="titulo">Focos por estado</span>
            <ul class="inline">
                <li title="Anual" onclick="changeTable('box-estado','ano_atual')"><i class="fas fa-calendar img_ano_atual"></i></li>
                <li title="Mensal" onclick="changeTable('box-estado','mes_atual')"><i class="fas fa-calendar-alt img_mes_atual"></i></li>
                <li title="Últimas 48 horas" onclick="changeTable('box-estado','48h')"><i class="fas fa-calendar-day img_48h"></i></li>
                <li></li>
                <li title="Exibir Tabela" onclick="changeTable('box-estado',null,'tabela')"><i class="fas fa-table img_tabela"></i></li>
                <li title="Exibir Gráfico em pizza" onclick="changeTable('box-estado',null,'pizza')"><i class="fas fa-chart-pie img_pizza"></i></li>
                <li title="Exibir Gráfico em barra" onclick="changeTable('box-estado',null,'barra')"><i class="fas fa-chart-bar img_barra"></i></li>
                <li title="Exibir Mapa" onclick="changeTable('box-estado',null,'mapa')"><i class="fas fa-map img_mapa"></i></li>
            </ul>
      </div>
      <span style="margin-left:11px;" class="descTempo">PERÍODO ANUAL: 2019</span>
      <iframe style="height:86%;" id="focos-estado"></iframe>
    </div>
    <div id="div-focos-municipio" class="estatistica">
        <div>
<!-- focos_municipio -->
            <span class="titulo">Focos por município</span>
            <ul class="inline">
                <li title="Anual" onclick="changeTable('box-municipio','ano_atual')"><i class="fas fa-calendar img_ano_atual"></i></li>
                <li title="Mensal" onclick="changeTable('box-municipio','mes_atual')"><i class="fas fa-calendar-alt img_mes_atual"></i></li>
                <li title="Últimas 48 horas" onclick="changeTable('box-municipio','48h')"><i class="fas fa-calendar-day img_48h"></i></li>
                <li></li>
                <li title="Exibir Tabela" onclick="changeTable('box-municipio',null,'tabela')"><i class="fas fa-table img_tabela"></i></li>
                <li title="Exibir Gráfico em pizza" onclick="changeTable('box-municipio',null,'pizza')"><i class="fas fa-chart-pie img_pizza"></i></li>
                <li title="Exibir Gráfico em barra" onclick="changeTable('box-municipio',null,'barra')"><i class="fas fa-chart-bar img_barra"></i></li>
<?php /*   <!-- <li title="Exibir Mapa" onclick="changeTable('box-municipio',null,'mapa')"><span class="img_mapa">Mapa</span></li> --> */ ?>
            </ul>
        </div>
        <span class="descTempo">PERÍODO ANUAL: 2019</span>
        <iframe id="focos-municipio"></iframe>
    </div>
    <div id="div-focos-bioma" class="estatistica">
        <div>
            <span class="titulo">Focos por bioma</span>
            <ul class="inline">
                <li title="Anual" onclick="changeTable('box-bioma','ano_atual')"><i class="fas fa-calendar img_ano_atual"></i></li>
                <li title="Mensal" onclick="changeTable('box-bioma','mes_atual')"><i class="fas fa-calendar-alt img_mes_atual"></i></li>
                <li title="Últimas 48 horas" onclick="changeTable('box-bioma','48h')"><i class="fas fa-calendar-day img_48h"></i></li>
                <li></li>
                <li title="Exibir Tabela" onclick="changeTable('box-bioma',null,'tabela')"><i class="fas fa-table img_tabela"></i></li>
                <li title="Exibir Gráfico em pizza" onclick="changeTable('box-bioma',null,'pizza')"><i class="fas fa-chart-pie img_pizza"></i></li>
                <li title="Exibir Gráfico em barra" onclick="changeTable('box-bioma',null,'barra')"><i class="fas fa-chart-bar img_barra"></i></li>
<?php /*   <!-- <li title="Exibir Mapa" onclick="changeTable('box-bioma',null,'mapa')"><span class="img_mapa">Mapa</span></li> --> */ ?>
            </ul>
        </div>
        <span class="descTempo">PERÍODO ANUAL: 2019</span>
        <iframe id="focos-bioma"></iframe>
    </div>
    <div class="comparativo">
  <!-- comparativo_pais -->
        <h2 id="titpaises">Tabela anual comparativa de países - últimos anos no intervalo de 01/Jan até <?= $lbl_ontem ?></h2> 
        <span>* Número de focos detectados pelo satélite <span style="background-color: #F3774F">S-NPP-375 TARDE</span> entre 01/01 a <?= $lbl_dia_mes ?> de cada ano.</span>
        <ul class="nav_graficos">
            <li title="Últimos 7 anos" onclick="changeTable('paises',null,'7anos');" class="active"><span>Últimos 7 anos</span></li>
            <li title="Todos os anos" onclick="changeTable('paises',null,'estendida');"><span>Todos os anos</span></li>
        </ul>
        <section>
            <iframe id="comparativa-paises" onload="resizeIframe(this)"></iframe>
            <a href="<?= $url_qmd ?>portal/estatistica_paises" target="_blank">Ir para estatísticas dos países <span class="fas fa-arrow-circle-right"></span></a>
        </section>
    </div>
  <!-- comparativo_estados -->
    <div class="comparativo">
        <h2 id="titestados">Tabela anual comparativa de estados do Brasil - últimos anos no intervalo de 01/Jan até <?= $lbl_ontem ?></h2>
        <span>* Número de focos detectados pelo satélite <span style="background-color: #F3774F">S-NPP-375 TARDE</span>.</span>
        <ul class="nav_graficos">
            <li title="Últimos 7 anos" onclick="changeTable('estados',null,'7anos');" class="active"><span>Últimos 7 anos</span></li>
            <li title="Todos os anos" onclick="changeTable('estados',null,'estendida');"><span>Todos os anos</span></li>
        </ul>
        <section>
            <iframe style="overflow-y:auto;" id="comparativa-estados" onload="resizeIframe(this)"></iframe>
            <a href="<?= $url_qmd ?>portal/estatistica_estados" target="_blank">Ir para estatísticas dos estados, regiões e biomas <span class="fas fa-arrow-circle-right"></span></a>
        </section>
    </div>
<!-- comparativo_regiao -->
<div class="comparativo">
        <h2 id="titregiao">Tabela anual comparativa de regiões do Brasil - últimos anos no intervalo de 01/Jan até <?= $lbl_ontem ?></h2>
        <span>* Número de focos detectados pelo satélite <span style="background-color: #F3774F">S-NPP-375 TARDE</span>.</span>
        <ul class="nav_graficos">
            <li title="Últimos 7 anos" onclick="changeTable('regiao_brasil',null,'7anos');" class="active"><span>Últimos 7 anos</span></li>
            <li title="Todos os anos" onclick="changeTable('regiao_brasil',null,'estendida');"><span>Todos os anos</span></li>
        </ul>
        <section>
            <iframe style="overflow-y:auto;" id="comparativa-regiao_brasil" onload="resizeIframe(this)"></iframe>
            <a href="<?= $url_qmd ?>portal/estatistica_estados" target="_blank">Ir para estatísticas dos estados, regiões e biomas <i class="fas fa-arrow-circle-right"></i></a>
        </section>
    </div>
<!-- comparativo_regiao_especial -->
    <div class="comparativo">
        <h2 id="titregiao">Tabela anual comparativa de regiões especiais do Brasil - últimos anos no intervalo de 01/Jan até <?= $lbl_ontem ?></h2>
        <span>* Número de focos detectados pelo satélite <span style="background-color: #F3774F">S-NPP-375 TARDE</span>.</span>
        <ul class="nav_graficos">
            <li title="Últimos 7 anos" onclick="changeTable('regiao',null,'7anos');" class="active"><span>Últimos 7 anos</span></li>
            <li title="Todos os anos" onclick="changeTable('regiao',null,'estendida');"><span>Todos os anos</span></li>
        </ul>
        <section>
            <iframe style="overflow-y:auto;" id="comparativa-regiao" onload="resizeIframe(this)"></iframe>
            <a href="<?= $url_qmd ?>portal/estatistica_estados" target="_blank">Ir para estatísticas dos estados, regiões e biomas <i class="fas fa-arrow-circle-right"></i></a>
        </section>
    </div>
<!-- comparativo_bioma -->
    <div class="comparativo">
        <h2 id="titbioma">Tabela anual comparativa de biomas do Brasil - últimos anos no intervalo de 01/Jan até <?= $lbl_ontem ?></h2>
        <span>* Número de focos detectados pelo satélite <span style="background-color: #F3774F">S-NPP-375 TARDE</span>.</span>
        <ul class="nav_graficos">
            <li title="Últimos 7 anos" onclick="changeTable('bioma',null,'7anos');" class="active"><span>Últimos 7 anos</span></li>
            <li title="Todos os anos" onclick="changeTable('bioma',null,'estendida');"><span>Todos os anos</span></li>
        </ul>
        <section>
            <iframe style="overflow-y:auto;" id="comparativa-bioma" onload="resizeIframe(this)"></iframe>
            <a href="<?= $url_qmd ?>portal/estatistica_estados" target="_blank">Ir para estatísticas dos estados, regiões e biomas <i class="fas fa-arrow-circle-right"></i></a>
        </section>
    </div>
</div>
</div>
</div>
<?php /*----------------------------------------------------------------------------------------------------------------------------------------------------
			  RODAPÉ DA PÁGINA 	
		    ---------------------------------------------------------------------------------------------------------------------------------------------------- */?>
        <div class="topo">
			<a accesskey="0" href="#wrapper">Ir para o topo</a>
		</div>
		<div id="loader" class="loader">
			<img src="../assets/images/spinner.gif" alt="Carregando...">
		</div>
    	<div id="footer" role="contentinfo" >
			<a name="afooter" id="afooter"></a>
			<div class="row sitemap">
				<dl class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
					<dt>Informações</dt>
					<dd>
						<a href="<?= $url_qmd ?>portal/informacoes/apresentacao" class="external-link" target="_blank">Apresentação</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/informacoes/perguntas-frequentes" class="external-link">Perguntas Frequentes</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/agradecimentos" class="external-link">Agradecimentos</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/equipe" class="external-link">Equipe</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/links-adicionais/links-e-material-de-30s" class="external-link">Links Externos</a>
					</dd>
				</dl>
				<dl class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
					<dt>Sistemas</dt>
					<dd>
						<a href="<?= $url_qmd ?>bdqueimadas" class="external-link">BDQueimadas</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>ciman" class="external-link">Ciman Virtual</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>cadastro/v1/relatorio-ucs/" class="external-link" target="_blank">Focos nas APs</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/risco-de-fogo-meteorologia" class="external-link" target="_blank">Risco de Fogo</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>sisam" class="external-link">Sisam</a>
					</dd>
				</dl>
				<dl class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
					<dt>Relatórios</dt>
					<dd>
						<a href="<?= $url_qmd ?>portal/outros-produtos/situacao-atual" class="external-link">Situação Atual</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>cadastro/v2/" class="external-link">Relatório Diário</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>ciman/briefings" class="external-link">Briefings meteorológicos</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/outros-produtos/infoqueimada" class="external-link">InfoQueima</a>
						
					</dd>
					<dd>
						<a href="http://www.ibama.gov.br/index.php?option=com_content&amp;view=article&amp;id=549&amp;Itemid=488" class="external-link" target="_blank">PrevFogo/IBAMA</a>
					</dd>
				</dl>
				<dl class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
					<dt>Publicações</dt>
					<dd>
						<a href="http://queimadas.cptec.inpe.br/~rqueimadas/documentos/pub_queimadas.pdf" class="external-link" target="_blank">Referenciadas</a>
					</dd>
					<dd>
						<a href="<?= $url_qmd ?>portal/links-adicionais/na-midia" class="external-link">Na Mídia</a>
					</dd>
					<dt>Contato</dt>
					<dd>
						<a href="mailto:queimadas@inpe.br" class="external-link">E-mail</a>
					</dd>
				</dl>
			</div>
			<div id="footer-brasil" class="footer-logos">
				<div id="wrapper-footer-brasil">
					<a href="http://www.acessoainformacao.gov.br/">
						<span class="logo-acesso-footer"></span>
					</a>
					<a href="http://www.brasil.gov.br/">
						<span class="logo-brasil-footer"></span>
					</a>
				</div>
			</div>
		</div>

        <script type="text/javascript">
            var last_dsa_image = "/oper/share/goes/goes16/goes16_web/ams_rgb_natcolor/<?= date("Y") ?>/<?= date("m") ?>/S11635399_<?= date("Ymd") ?>0010.jpg"
            var mapa,
                tipo = {
                    'regiao_brasil': '7anos',
                    'bioma': '7anos',
                    'regiao': '7anos',
                    'estados': '7anos',
                    'paises': '7anos',
                    'box-paises': 'tabela',
                    'box-estado': 'mapa',
                    'box-municipio': 'barra',
                    'box-bioma': 'pizza'
                },
                tempo = {
                    'regiao_brasil': 'anual',
                    'bioma': 'anual',
                    'regiao': 'anual',
                    'estados': 'anual',
                    'paises': 'anual',
                    'box-paises': 'ano_atual',
                    'box-estado': 'ano_atual',
                    'box-municipio': 'ano_atual',
                    'box-bioma': 'ano_atual'
                },
                d = new Date(),
                mapserver = "//sirc.dgi.inpe.br/cgi-bin/mapserv?map=",
                geo_terrama = "<?= $url_qmd ?>terrama2q/geoserver/ows"
                geo_meteorologia = "<?= $url_qmd ?>firerisk/geoserver/wms"

                map_bdq = "https://queimadas.dgi.inpe.br/queimadas/geoserver/wms?"
                map_dsa = "//maps.cptec.inpe.br/mapserv?map=/oper/share/webdsa/queimadas.map&",
                map_terreno = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                opcoes = {
                    attribution: '&copy; <a target="_blank" href="<?= $url_qmd ?>">INPE - Queimadas</a>'
                };
            var terreno = getTileLayer(map_terreno, opcoes),
                contornoPaises = getTileLayer(geo_meteorologia, {
                    layers: 'estados',
                    styles: 'thin_line',
                    cql_filter: 'id_0 in (12,28,33,48,53,68,75,80,98,177,178,219,245,249)'
                }),
                focos = getTileLayer(map_bdq, {
                    layers: 'bdqueimadas:focos',
                    cql_filter: 'data_hora_gmt >= <?= $dt_ontem ?> and continente_id = 8'
                }),
                nuvens = "",
                fumaca = getTileLayer(geo_terrama, {
                    layers: 'view72',
                    time: "<?= $dt_anteontem ?>"
                }),
                rfPrev1d = getTileLayer(geo_meteorologia, {
                    layers: 'FireRisk-Forecast_T0'
                }),
                nuvens_control = "",
                terreno_control = getTileLayer(map_terreno, opcoes);

            function get_last_dsa_image() {
                $.getJSON('//cws.cptec.inpe.br/ws_dsa/bdi/getListByOrbGeo/G/1/5399', function(data) {})
                    .done(function(data) {
                        last_dsa_image = data[0].filePath;
                    })
                    .fail(function() {
                        console.log("ERRO AO BUSCAR ÚLTIMA IMAGEM");
                    })
                    .always(function() {
                        update_dsa_image();
                    });
            }

            function update_dsa_image() {
                nuvens = getTileLayer(map_dsa, {
                    layers: 'img_5399',
                    img_5399: last_dsa_image
                });
                nuvens_control = getTileLayer(map_dsa, {
                    layers: 'img_5399',
                    img_5399: last_dsa_image
                });
                update_map();
            }

            function update_map() {
                mapa = L.map('mapa', {
                    zoom: 8,
                    minZoom: 0,
                    center: [-15.8, -47.9],
                    layers: [terreno, nuvens, contornoPaises, focos],
                    fullscreenControl: true
                }).fitBounds([
                    [-34, -75],
                    [5, -31]
                ]);

                mapa.on('resize', function(e) {
                    mapa.fitBounds([
                        [-34, -75.0],
                        [5, -31]
                    ]);
                });
                mapa.on('overlayadd', function(l) {
                    contornoPaises.bringToFront();
                    focos.bringToFront();
                });
                mapa.on('baselayerchange', function(l) {
                    contornoPaises.bringToFront();
                    focos.bringToFront();
                    $("#slider").slider('value', 100);
                });

                L.control.scale().addTo(mapa);

                new L.Control.GeoSearch({
                    provider: new L.GeoSearch.Provider.OpenStreetMap()
                }).addTo(mapa);
                ctrl = getLayersSituacaoAtual(mapa);
                activeLayer = ctrl[3];

                rfPrev1d.addTo(mapa);
                

                $(".leaflet-left").append($("#slider").addClass("leaflet-bar leaflet-control"));
                $("#mapa").append($("#legenda_mapa"));

                $("#slider").slider({
                    value: 100,
                    orientation: "vertical",
                    range: "min",
                    animate: true,
                    change: function(event, ui) {
                        for (var i = 0; i < activeLayer.length; i++) {
                            activeLayer[i].setOpacity(parseFloat(ui.value) / 100);
                        }
                    }
                });
            }

            function getTileLayer(wms, params) {
                var opt = {
                    format: 'image/png',
                    transparent: true,
                    crs: L.CRS.EPSG4326
                };
                if (typeof params !== "undefined") {
                    Object.assign(opt, params);
                }
                return L.tileLayer.wms(wms, opt);
            }

            function getLayersSituacaoAtual(mapa) {
                controlLayers = L.control.layers({
                    'Risco de Fogo': rfPrev1d,
                    'Nuvens': nuvens_control,
                    'Fumaça': fumaca,
                    'Terreno': terreno_control,
                }, {}, {
                    collapsed: false
                }).addTo(mapa);

                ctrl = [focos, contornoPaises, controlLayers, [rfPrev1d, nuvens, fumaca, terreno_control], terreno];

                return ctrl;
            }

            function changeTable(unidade, tmp, tp) {
                var id_obj, un, label_tp;

                if (tmp) {
                    tempo[unidade] = tmp;
                }
                if (tp) {
                    tipo[unidade] = tp;
                }
                tmp = tempo[unidade];
                tp = tipo[unidade];


                label_tp = tp == "tabela" ? "" : "_" + tp;
                if (unidade.indexOf("box-") < 0) {
                    un = unidade;
                    id_obj = "#comparativa-" + un;
                } else {
                    un = unidade.replace("box-", "");
                    $("#div-focos-" + un + " .inline li").removeClass("active");
                    $("#div-focos-" + un + " .img_" + tmp + ",#div-focos-" + un + " .img_" + tp).parent().addClass("active");
                    var lbl = 'PERÍODO';
                    switch (tmp) {
                        case 'mes_atual':
                            lbl += " MENSAL: ATÉ <?= $lbl_hoje ?>";
                            break;
                        case '48h':
                            lbl += ": ÚLTIMAS " + tmp.toUpperCase();
                            break;
                        case 'ano_atual':
                        default:
                            lbl += " ANUAL: " + d.getFullYear();
                    }
                    $("#div-focos-" + un).find(".descTempo").html(lbl);
                    id_obj = "#focos-" + un;
                }
                if ($(id_obj).length == 0) return;
                $(id_obj).attr("src", tp == "mapa" ? ("<?= $url_base?>mapas.php?un="+ un + "&temp=" + tmp) : ("<?= $url_base ?>media/focos_" + un + "_" + tmp + label_tp + ".html?_=<?= $date ?>"));
            }


            $(document).ready(function() {

                get_last_dsa_image();

                $.each(tipo, function(k, v) {
                    changeTable(k);
                })

                if ($(".topo ul").length == 0) {
                    $(".topo").append($("#accessibility").clone());
                }
                $('body').on('click', '.comparativo li', function() {
                    $(this).parent().children().removeClass("active");
                    $(this).addClass("active");
                })
            });
        </script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121460145-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-121460145-1');
        </script>
</body>

</html>
