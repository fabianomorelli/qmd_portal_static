<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$ontem = time() - (24*3600);
$date = date("mdyh");
$lbl_ontem = date("d/m",$ontem);
$url_base = "/public/situacao_atual";
$url_media= "/media";
$url_qmd = "//queimadas.dgi.inpe.br/queimadas/portal-static/situacao-atual/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Situação atual - Programa Queimadas - INPE</title>
</head>
<body>
  <h5><a href="//gov.br">BRASIL - Ministério de Ciências, Tecnologias e Inovações</a></h5>
  <h4><a href="//www.inpe.br">INSTITUTO NACIONAL DE PESQUISAS ESPACIAIS</a></h4>
  <h3><a href="<?= $url_qmd ?>">Programa Queimadas</a></h3>
  <hr>
<h1>Situação Atual - versão light</h1>
<a href="<?="$url_base/" ?>">Alternar para visualização padrão</a>
    <ul>
      <li><h2>Focos por país</h2></li>
      <ul>
        <li><h3>Anual</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_ano_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_ano_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_ano_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_ano_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Mensal</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_mes_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_mes_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_mes_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_mes_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Últimas 48 horas</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_48h_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_48h_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_48h_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_48h_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
      </ul>
    </ul>
    <hr>
    <ul>
      <li><h2>Focos por estado</h2></li>
      <ul>
        <li><h3>Anual</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_ano_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_ano_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_ano_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_ano_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Mensal</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_mes_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_mes_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_mes_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_mes_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Últimas 48 horas</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_48h_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_48h_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_48h_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estado_48h_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
      </ul>
    </ul>
    <hr>
    <ul>
      <li><h2>Focos por município</h2></li>
      <ul>
        <li><h3>Anual</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_ano_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_ano_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_ano_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_ano_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Mensal</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_mes_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_mes_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_mes_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_mes_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Últimas 48 horas</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_48h_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_48h_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_48h_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_municipio_48h_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
      </ul>
    </ul>
    <hr>
    <ul>
      <li><h2>Focos por bioma</h2></li>
      <ul>
        <li><h3>Anual</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_ano_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_ano_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_ano_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_ano_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Mensal</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_mes_atual_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_mes_atual_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_mes_atual_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_mes_atual_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
        <li><h3>Últimas 48 horas</h3></li>
        <ul>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_48h_titulo.html?_=<?= $date; ?>">Tabela</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_48h_pizza_titulo.html?_=<?= $date; ?>">Gráfico em pizza</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_48h_barra_titulo.html?_=<?= $date; ?>">Gráfico em colunas</a></li>
            <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_48h_mapa_titulo.html?_=<?= $date; ?>">Mapa</a></li>
        </ul>
      </ul>
    </ul>
    <hr>
    <ul>
      <li><h2>Tabela anual comparativa de países - últimos anos no intervalo de 01/01 até <?= $lbl_ontem ?></h2></li>
      <ul>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_anual_7anos_titulo.html?_=<?= $date; ?>">Últimos anos</a></li>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_paises_anual_estendida_titulo.html?_=<?= $date; ?>">Todos os anos</a></li>
      </ul>
    </ul>
    <ul>
      <li><h2>Tabela anual comparativa de estados do Brasil - últimos anos no intervalo de 01/01 até <?= $lbl_ontem ?></h2></li>
      <ul>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estados_anual_7anos_titulo.html?_=<?= $date; ?>">Últimos anos</a></li>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_estados_anual_estendida_titulo.html?_=<?= $date; ?>">Todos os anos</a></li>
      </ul>
    </ul>
    <ul>
      <li><h2>Tabela anual comparativa de regiões do Brasil - últimos anos no intervalo de 01/01 até <?= $lbl_ontem ?></h2></li>
      <ul>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_regiao_brasil_anual_7anos_titulo.html?_=<?= $date; ?>">Últimos anos</a></li>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_regiao_brasil_anual_estendida_titulo.html?_=<?= $date; ?>">Todos os anos</a></li>
      </ul>
    </ul>
    <ul>
      <li><h2>Tabela anual comparativa de regiões especiais do Brasil - últimos anos no intervalo de 01/01 até <?= $lbl_ontem ?></h2></li>
      <ul>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_regiao_anual_7anos_titulo.html?_=<?= $date; ?>">Últimos anos</a></li>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_regiao_anual_estendida_titulo.html?_=<?= $date; ?>">Todos os anos</a></li>
      </ul>
    </ul>
    <ul>
      <li><h2>Tabela anual comparativa de biomas do Brasil - últimos anos no intervalo de 01/01 até <?= $lbl_ontem ?></h2></li>
      <ul>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_anual_7anos_titulo.html?_=<?= $date; ?>">Últimos anos</a></li>
        <li><a target="_blank" href="<?= $url_base.$url_media ?>/focos_bioma_anual_estendida_titulo.html?_=<?= $date; ?>">Todos os anos</a></li>
      </ul>
    </ul>
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
