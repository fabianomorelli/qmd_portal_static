<html lang="en"><head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="pragma" content="no-cache">
        <title>Estatística países light</title>
    <script>
            function getPais(id) {
                if(id === "68") return "equador";
                if(id === "245") return "uruguai";
                if(id === "12") return "argentina";
                if(id === "28") return "bolivia";
                if(id === "75") return "ilhas_malvinas";
                if(id === "80") return "guyana_francesa";
                if(id === "98") return "guyana";
                if(id === "33") return "brasil";
                if(id === "48") return "chile";
                if(id === "53") return "colombia";
                if(id === "249") return "venezuela";
                if(id === "177") return "paraguay";
                if(id === "178") return "perú";
                if(id === "219") return "suriname";
                if(id === "999") return "america_do_sul";
            }
            function changed(val) {
                var pais = getPais(val.value);
                document.getElementById('link_tabela_hist').href = 'portal-static/grafico_historico_pais_'+pais+'.html';
                document.getElementById('link_serie_hist').href = 'portal-static/grafico_serie_historica_pais_'+pais+'.html';
                document.getElementById('link_comparativo_saz').href = 'portal-static/grafico_comparativo_sazonal_pais_'+pais+'.html';
                document.getElementById('link_comparativo_prim').href = 'portal-static/grafico_comparativo_sazonal_pais_'+pais+'.html';
                document.getElementById('link_comparativo_seg').href = 'portal-static/grafico_comparativo_segundo_semestre_pais_'+pais+'.html';
            }
    </script>
</head>
<body>
    <h1><a href="http://queimadas.dgi.inpe.br/queimadas/portal/">Programa Queimadas</a></h1>
    <h4><a href="http://www.inpe.br">INSTITUTO NACIONAL DE PESQUISAS ESPACIAIS</a></h4>
    <hr>
    <h1>Monitoramento dos Focos Ativos por Países - versão light</h1>
    <a href="http://queimadas.dgi.inpe.br/queimadas/portal-static/estatisticas_paises/">Alternar para visualização padrão</a>
    <p>
        <label>Selecione o país:</label>
        <select onchange="changed(this)">
            <option label="Ecuador" value="68">Ecuador</option>
            <option label="Uruguay" value="245">Uruguay</option>
            <option label="Argentina" value="12">Argentina</option>
            <option label="Bolivia" value="28">Bolivia</option>
            <option label="Falkland Islands" value="75">Falkland Islands</option>
            <option label="French Guiana" value="80">French Guiana</option>
            <option label="Guyana" value="98">Guyana</option>
            <option label="Brasil" value="33" selected="">Brasil</option>
            <option label="Chile" value="48">Chile</option>
            <option label="Colombia" value="53">Colombia</option>
            <option label="Venezuela" value="249">Venezuela</option>
            <option label="Paraguay" value="177">Paraguay</option>
            <option label="Peru" value="178">Peru</option>
            <option label="Suriname" value="219">Suriname</option>
            <option label="América do Sul" value="999">América do Sul</option>
        </select>
    </p>
    <ul style="display: inline">
        <li><a id="link_tabela_hist" href="portal-static/grafico_historico_pais_brasil.html" target="_blank">Tabela de histórico de focos ativos</a></li>
        <li><a id="link_serie_hist" href="portal-static/grafico_serie_historica_pais_brasil.html" target="_blank">Gráfico da série Histórica</a></li>
        <li><a id="link_comparativo_saz" href="portal-static/grafico_comparativo_sazonal_pais_brasil.html" target="_blank">Gráfico de comparativo sazonal</a></li>
        <li><a id="link_comparativo_prim" href="portal-static/grafico_comparativo_primeiro_semestre_pais_brasil.html" target="_blank">Gráfico de comparativo 1º semestre</a></li>
        <li><a id="link_comparativo_seg" href="portal-static/grafico_comparativo_segundo_semestre_pais_brasil.html" target="_blank">Gráfico de comparativo 2º semestre</a></li>
    </ul>
</body>
</html>
