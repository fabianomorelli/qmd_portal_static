#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os
import warnings

import geopandas as gpd
import numpy as np
import pandas as pd
from sqlalchemy import create_engine, pool

from matplotlib import pyplot as plt

import get_file_directory as get

warnings.simplefilter(action="ignore", category=FutureWarning)
warnings.simplefilter(action="ignore", category=UserWarning)

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)


ARQUIVO_SAIDA = (
    "%s/focos_municipio_48h.html"%(get.returnPath())
)


DATA_ATUAL = dt.datetime.now()
pd.options.display.float_format = "{:.0f}".format
INICIO = (DATA_ATUAL - dt.timedelta(days=2)).strftime("%Y-%m-%d")
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day - 2
ONTEM_ = (DATA_ATUAL - dt.timedelta(days=1)).strftime("%Y-%m-%d")


sql = f"""
select 
    e.name_2 || ' (' || e.name_1 || ')' municipio,
    sum(nfocos) qtd
from 
    view_focos_munic_ref v, dados_geo.municipios e
where 
    v.pais_id0=33
    and v.pais_id0=e.id_0
    and v.estado_id1=e.id_1
    and v.municipio_id2=e.id_2
    and data >= '{INICIO}' and data <='{ONTEM_}'
group by 1
order by 2 desc
limit 10
"""
engine.connect()
saida = pd.read_sql(sql, engine).sort_values("qtd", ascending=False)
saida.set_index("municipio", inplace=True)
saida


# grava saida em html
saida.to_html(ARQUIVO_SAIDA)
append = """
<style>
.dataframe,.main-table{
    width: 100%;
}
table {border: none;
}
.espaco_esquerda {
    padding-left: 60px;
}

table tr:nth-child(even) {
    background-color: #FDF2E3;
}

table thead{
    display: none;
}

table tr {
    background-color: #FADAAB;
}

table {
    text-align: left;
    border-collapse: collapse;
    border-spacing: 0px;
    margin-bottom: 1em;
    
    font-family: 'Source Sans Pro', sans-serif;
    font-size: 12px;
    border: 1px solid #666;
    border-radius: 6px;
    padding: 7px;
    margin: 0;
    overflow-y: auto;
}
body {
    margin: 0;
}

table th:first-child {  
  padding-left: 100px !important;
}

table th:first-child {  
    text-align: left;
    padding-left: 50px !important;
    width: 150px;
}
table tbody td{  
    padding-right: 50px;
}

table thead tr:last-child {  
  display:none;
}

table thead tr:last-child {  
  display:none;
}

table th{  
  padding-left: 60px;
  width: 100px;
}

*{
text-align: right;
border: 0px;
}
</style>
"""
comando = f'echo "{append}" >> {ARQUIVO_SAIDA}'
os.system(comando)
print(ARQUIVO_SAIDA)


data_graficos = str(saida.reset_index().values.tolist())
data_graficos


saida_pizza = ARQUIVO_SAIDA.replace(".html", "_pizza.html")
html_grafico = (
    """
<html>
<body>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart(dados, tipo, container) {
  
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Element');
  data.addColumn('number', 'Focos');
  data.addRows( %s);

  var options = {
    chart: {
      title: 'Focos de incêndio',
      subtitle: 'Focos detectados pelo Satélite de Referência',      
    }
  };

  var chart = new google.visualization.PieChart(document.getElementById("chart_paises"));
  chart.draw(data, options);
}
</script>
 <div id="chart_paises" style="width: 100%%; height: 100%%;"></div>
</body>
</html>
"""
    % data_graficos
)

with open(saida_pizza, "w") as f:
    f.write(html_grafico)
print(saida_pizza)


saida_barra = ARQUIVO_SAIDA.replace(".html", "_barra.html")
html_grafico = (
    """
<html>
<body>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart(dados, tipo, container) {
  
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Element');
  data.addColumn('number', 'Focos');
  data.addRows( %s);

  var options = {
    chart: {
      title: 'Focos de incêndio',
      subtitle: 'Focos detectados pelo Satélite de Referência',      
    }
  };

  var chart = new google.visualization.ColumnChart(document.getElementById("chart_paises"));
  chart.draw(data, options);
}
</script>
 <div id="chart_paises" style="width: 100%%; height: 100%%;"></div>
</body>
</html>
"""
    % data_graficos
)

with open(saida_barra, "w") as f:
    f.write(html_grafico)
print(saida_barra)
