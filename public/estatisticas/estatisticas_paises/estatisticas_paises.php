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
$url_base = "../media/";
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
	<title>Monitoramento dos Focos Ativos por País - Programa Queimadas - INPE</title>
	<link rel="stylesheet" href="../../assets/css/page.min.css">
	<link rel="stylesheet" href="../../assets/css/base_plugin.css">
	<link rel="stylesheet" href="../../assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="../../assets/css/solid.min.css">
	<link rel="stylesheet" href="../../assets/jquery/jquery-ui.min.css">
   	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
    <style type="text/css">
	#main{font-size:70%}
	#main h5{display:block;width:100%;margin:1em 0;}
    iframe{border:0;margin:0;padding:0;width:100%;min-height:490px;}
    #featured-content div.width-16{padding-top:49px !important;}
    .ui-state-active{background:#F19B1F!important;border:#F8CD8F;}
    .ui-state-collapsed{background:#F6F6F6!important;border:#556B87;}
	.loader{position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,.7)}
	.loader img{position:absolute;left:calc(50% - 75px);top:calc(50% - 75px);width:150px;height:150px;}
	#main>div>a{display:block;text-align:right;font-size:1.8em;}
	#accordion{width:300px;margin-bottom:10px;}
	.mensagem{display:block;margin-bottom:2em;font-size:.9em;}
	#tab_grafico{display:block;width:100%;margin-bottom:2em;text-align:center;}
	#tabela_focos{display:block;width:100%;margin-bottom:2em;}
	.leg_figura{display:block;width:100%;margin:.5em;text-align:center;font-size:1em;cursor:default;}
	#tab_grafico_mes_atual>h5{display:block;width:100%;margin-top:3em;text-align:center;}
	#tab_grafico_mes_atual>iframe{display:block;width:100%;margin-bottom:.2em;}
	#ifr_grafico_serie_paises{display:block;margin-top:3em;}
	#csv_historico_paises{font-size: 13px;font-weight: bold;}
</style>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../../assets/jquery/jquery-ui.js" type="text/javascript"></script>
<script>
	loaded=0;
    function checkLoaded(){
		if(loaded>=$("iframe").length-1){
			$(".loader").hide();
		}
	}
	function resizeIframe(obj){try{obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';}catch(e){console.log("erro:",e)}finally{loaded++;checkLoaded();}}
</script>
</head>
<body>
<?php /*	<div id="barra-identidade" ><div id="barra-brasil"><div id="wrapper-barra-brasil"><div class="brasil-flag"><a href="//brasil.gov.br" class="link-barra">Brasil</a></div><span class="acesso-info"><a href="//www.servicos.gov.br/?pk_campaign=barrabrasil" class="link-barra" id="barra-brasil-orgao">Serviços</a></span><nav><ul id="lista-barra-brasil" class="list"><li><a href="#" id="menu-icon"></a></li><li class="list-item first"><a href="//www.simplifique.gov.br" class="link-barra">Simplifique!</a></li><li class="list-item"><a href="//brasil.gov.br/barra#participe" class="link-barra">Participe</a></li><li class="list-item"><a href="//brasil.gov.br/barra#acesso-informacao" class="link-barra">Acesso à informação</a></li><li class="list-item"><a href="//www.planalto.gov.br/legislacao" class="link-barra">Legislação</a></li><li class="list-item last last-item"><a href="//brasil.gov.br/barra#orgaos-atuacao-canais" class="link-barra">Canais</a></li></ul></nav><span id="brasil-vlibras"><a class="logo-vlibras" href="#"></a><span class="link-vlibras"><img src="//barra.brasil.gov.br/imagens/vlibras.gif">&nbsp;<span>O conteúdo desse portal pode ser acessível em Libras usando o <a href="//www.vlibras.gov.br">VLibras</a></span></span></span></div></div></div> */ ?>
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
					<ul id="portal-siteactions" class="text-right">
						<li id="siteaction-contraste"><a href="#" title="Alto Contraste" accesskey="6">Alto Contraste</a></li>
						<li id="siteaction-mapadosite" class="last-item"><a href="<?= $url_qmd ?>portal/mapadosite" title="Mapa do Site" accesskey="7">Mapa do Site</a></li>
					</ul>
				</div>
				<div class="container text-left align-top" id="container-logo">
					<div id="logo" class="text-left">
						<a title="" href="<?= $url_qmd ?>portal">
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
								<a href="//flickr.com/145994884@N08">Flickr</a>
							</li>
							<li id="portalredes-facebook" class="portalredes-item">
								<a href="//facebook.com/queimadasinpe">Facebook</a>
							</li>				
							<li id="portalredes-youtube" class="portalredes-item">
								<a href="//youtube.com/channel/UCkzF8NS-HadR2CHXw3msMmg">YouTube</a>
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
<?php /*
	<div>
    <a href="//queimadas.dgi.inpe.br/queimadas/portal/informacoes/manutencao/nao-programada/problemas-na-recepcao-de-focos-do-satelite-de-referencia" target="_blank">
        <img src="//queimadas.dgi.inpe.br/queimadas/portal/informacoes/manutencao/nao-programada/banner_normalizacao_sat_ref.gif" title="Normalização do funcionamento do satélite de referência" alt="Normalização do funcionamento do satélite de referência">
    </a>
	</div>
*/ ?>
	<a name="anavigation" id="anavigation"></a>
	<h1>Monitoramento dos Focos Ativos por Países</h1>
	<span class="mensagem"><a href="<?= $url_qmd ?>portal-static/estatisticas_paises/estatisticas-paises-light.php">Ir para HTML básico (para casos de lentidão no carregamento)</a></span>
	<div>
	<div id="accordion">
		<h3>&nbsp;&nbsp;&nbsp;&nbsp;Filtro por país</h3>
		<div>
    		<p>
			<label>Selecione o pais:</label>
			<select id="select_country">
				<option value="america_do_sul">América do Sul</option><option value="argentina">Argentina</option><option value="bolivia">Bolivia</option>
				<option value="brasil" selected="selected">Brasil</option><option value="chile">Chile</option><option value="colombia">Colombia</option>
				<option value="equador">Ecuador</option><option value="guyana_francesa">French Guiana</option>
				<option value="guyana">Guyana</option><option value="paraguai">Paraguay</option><option value="peru">Peru</option>
				<option value="suriname">Suriname</option><option value="uruguai">Uruguay</option><option value="venezuela">Venezuela</option>
			</select>
			</p>
		</div>
	</div>
		<a name="acontent" id="acontent"></a>
		<h5>Comparação do total de focos ativos detectados pelo satélite de referência em cada mês, no período de 1998 até <?= $lbl_ontem ?>.</h5>
		<section id="tabela_focos">
			<iframe src="" id="ifr_historico_paises" onload="resizeIframe(this);" style="min-height:490px;"></iframe>
			<div><a id="csv_historico_paises" href="">Download dos dados em CSV <span class="fa fa-download"></a></div>
			<div>* O cálculo de máxima, média e mínima não consideram os valores do ano corrente.</div>
			<div>** Os valores do mês mais recente do ano corrente são parciais porque compreendem as detecções do primeiro dia do mês até ontem, porém os demais valores compreendem o mês todo.</div>
		</section>
		<div id="tab_grafico_mes_atual">
			<h5>Comparação do total de focos ativos detectados dia a dia pelo satélite de referência para a data de <?= $label ?>.</h5>
			<iframe src="" id="ifr_grafico_mes_atual" onload="resizeIframe(this);" style="min-height:auto;"></iframe>
			<div>* Não é recomendada a comparação dos valores díarios, apenas dos totais mensais.</div>
		</div>
		<div id="tab_grafico">
			<iframe src="" id="ifr_grafico_serie_paises" onload="resizeIframe(this);" style="height:490px;"></iframe>
			<span>Figura 1 - Série histórica do total de focos ativos detectados pelo satélite de referência, no período de 1998 até <?= $lbl_ontem ?>.</span>
		</div>
		<section style="width:100%;">
			<ul id="nav_graficos" class="nav_graficos">
				<li id="tit_menu1" class="active">Comparativo mensal</li>
				<li id="tit_menu3">Comparativo 1° sem.</li>
				<li id="tit_menu4">Comparativo 2° sem.</li>
			</ul>
		</section>
		<div class="clear"></div>
		<section class="panel-content" style="width:100%;">
			<section class="map_menu" id="menu1">
				<iframe src="" id="ifr_comparativo_sazonal" onload="resizeIframe(this);"></iframe>
				<span class="leg_figura">Figura 2 - Comparativo dos dados do ano corrente com os valores máximos, médios e mínimos, no período de 1998 até <?= $lbl_ontem ?>.</span>
			</section>
			<section class="map_menu" id="menu3">
				<iframe src="" id="ifr_comparativo_sem1" onload="resizeIframe(this);"></iframe>
				<span class="leg_figura">Figura 3 - Comparativo dos dados do primeiro semestre do ano corrente com os valores médios, no período de 1998 até <?= $lbl_ontem ?>.</span>
			</section>
			<section class="map_menu" id="menu4">
				<iframe src="" id="ifr_comparativo_sem2" onload="resizeIframe(this);"></iframe>
				<span class="leg_figura">Figura 4 - Comparativo dos dados do segundo semestre do ano corrente com os valores médios, no período de 1998 até <?= $lbl_ontem ?>.</span>
			</section>
		</section>
		<a href="<?= $url_qmd ?>portal/situacao-atual" target="_blank">Ir para situação atual <span class="fas fa-arrow-circle-right"></span></a>
	</div>
</div>
		<div class="topo">
			<a accesskey="0" href="#wrapper">Ir para o topo</a>
		</div>
		<div id="loader" class="loader">
			<img src="../../assets/images/spinner.gif" alt="Carregando...">
		</div>
<?php /*----------------------------------------------------------------------------------------------------------------------------------------------------
		RODAPÉ DA PÁGINA 	
		---------------------------------------------------------------------------------------------------------------------------------------------------- */?>
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
						<a href="//www.ibama.gov.br/index.php?option=com_content&amp;view=article&amp;id=549&amp;Itemid=488" class="external-link" target="_blank">PrevFogo/IBAMA</a>
					</dd>
				</dl>
				<dl class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
					<dt>Publicações</dt>
					<dd>
						<a href="//queimadas.cptec.inpe.br/~rqueimadas/documentos/pub_queimadas.pdf" class="external-link" target="_blank">Referenciadas</a>
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
					<a href="//www.acessoainformacao.gov.br/">
						<span class="logo-acesso-footer"></span>
					</a>
					<a href="//www.brasil.gov.br/">
						<span class="logo-brasil-footer"></span>
					</a>
				</div>
			</div>
		</div>
<?php /*	<script defer="defer" src="//barra.brasil.gov.br/barra_2.0.js" type="text/javascript"></script> */ ?>

<script type="text/javascript">
    function dados_pais(){
	    pais = typeof $("#select_country").val() == "undefined"?"brasil":$("#select_country").val();
		$(".loader").show();
		loaded=0;
		$("#ifr_historico_paises").attr("src","<?= $url_base?>grafico_historico_pais_"+pais+".html?_=<?= $date ?>");
		$("#ifr_grafico_serie_paises").attr("src","<?= $url_base?>grafico_serie_historica_pais_"+pais+".html?_=<?= $date ?>");
		$("#ifr_comparativo_sazonal").attr("src","<?= $url_base?>grafico_comparativo_sazonal_pais_"+pais+".html?_=<?= $date ?>");
		$("#ifr_comparativo_sem1").attr("src","<?= $url_base?>grafico_comparativo_primeiro_semestre_pais_"+pais+".html?_=<?= $date ?>");
		$("#ifr_comparativo_sem2").attr("src","<?= $url_base?>grafico_comparativo_segundo_semestre_pais_"+pais+".html?_=<?= $date ?>");
		$("#ifr_grafico_mes_atual").attr("src","<?= $url_base?>grafico_historico_mes_atual_pais_"+pais+".html?_=<?= $date ?>");
		
		$("iframe").on('error', function () {loaded++;checkLoaded();});
		$("#csv_historico_paises").attr("href", "<?= $url_base?>csv_estatisticas/historico_pais_"+pais+".csv");
	    setMenu(1);
	}
    function setMenu(menu){
        $(".map_menu").hide();
        $("#nav_graficos li").removeClass("active");
        $("#menu"+menu).show();
        $("#tit_menu"+menu).addClass("active");
	}
    $("body").on("change","#select_country",function(e){dados_pais();});
    $("body").on("click","#nav_graficos li",function(e){setMenu(this.id.replace("tit_menu",""));});
	$(document).ready(function(){
		$(function(){$("#accordion").accordion();});
		$("select").selectmenu({width:'100%',change:function(e,d){dados_pais();}});
		dados_pais();if($(".topo ul").length == 0){$(".topo").append($("#accessibility").clone());}})
</script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121460145-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'UA-121460145-1');
	</script>
</body>
</html>
