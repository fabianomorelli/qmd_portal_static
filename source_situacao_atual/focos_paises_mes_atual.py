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


ARQUIVO_SAIDA = "%s/focos_paises_mes_atual.html"%(get.returnPath())


ONTEM = dt.datetime.now() - dt.timedelta(days=1)
pd.options.display.float_format = "{:.0f}".format
ANO_ATUAL = ONTEM.year
MES_ATUAL = ONTEM.month
ONTEM_ = ONTEM.strftime("%Y-%m-%d")


sql = f"""
select 
    e.nome pais,
    sum(nfocos) qtd
from 
    view_focos_munic_ref v, dados_geo.paises_ams e
where 
    e.id_0 in (12,28,33,48,53,59,68,98,80,177,178,219,245,249)
    and v.pais_id0=e.id_0
    and data >= '{ANO_ATUAL}{str(MES_ATUAL).zfill(2)}01'
    and data <= '{ONTEM_}'
group by 1
"""
engine.connect()
saida = pd.read_sql(sql, engine).sort_values("qtd", ascending=False)
saida.set_index("pais", inplace=True)


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
    fontSize: 12,
    legendFontSize:12,
    titleFontSize:14,   
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
    fontSize: 12,
    legendFontSize:12,
    titleFontSize:14,   
  };

  var chart = new google.visualization.BarChart(document.getElementById("chart_paises"));
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


engine.connect()
sql = f"""
select
    st_simplify(geom, 0.01) as geom,
    id_0,
    nome pais
from 
    dados_geo.paises_ams
where 
    id_0 in (12, 28, 33, 48, 53, 68, 75, 80, 98, 177, 178, 219, 245, 249)
"""
paises = gpd.read_postgis(sql, engine)


paises_stats = pd.merge(paises, saida.reset_index(), on="pais", how="left")
paises_stats.loc[paises_stats.qtd.isnull(), "qtd"] = 0
paises_stats["qtd"] = paises_stats["qtd"].astype(int)
paises_stats.sort_values("qtd", inplace=True, ascending=False)


base = paises_stats.plot(
    color="#fdebcb", figsize=(10, 12), edgecolor="black", linewidth=2
)
paises_stats[(paises_stats.qtd >= 20000)].plot(
    color='#541817', ax=base, edgecolor='black', linewidth=2
)
paises_stats[(paises_stats.qtd >= 10000) & (paises_stats.qtd < 20000)].plot(
    color='#a02d19', ax=base, edgecolor='black', linewidth=2
)
paises_stats[(paises_stats.qtd >= 5000) & (paises_stats.qtd < 10000)].plot(
    color='#c14336', ax=base, edgecolor='black', linewidth=2
)
paises_stats[(paises_stats.qtd >= 2000) & (paises_stats.qtd < 5000)].plot(
    color='#f05b31', ax=base, edgecolor='black', linewidth=2
)
paises_stats[(paises_stats.qtd >= 500) & (paises_stats.qtd < 2000)].plot(
    color='#f48639', ax=base, edgecolor='black', linewidth=2
)
paises_stats[(paises_stats.qtd >= 100) & (paises_stats.qtd < 500)].plot(
    color='#fbcfa7', ax=base, edgecolor='black', linewidth=2
)
paises_stats[(paises_stats.qtd >= 0) & (paises_stats.qtd < 100)].plot(
    color='#feebcf', ax=base, edgecolor='black', linewidth=2
)
minx, miny, maxx, maxy = -83.0, -58.0, -33.0, 13.0
base.set_xlim(minx, maxx)
base.set_ylim(miny, maxy)
saida_mapa = ARQUIVO_SAIDA.replace(".html", "_mapa.png")
base.get_figure().savefig(saida_mapa)


img = os.path.basename(saida_mapa)
img

print(saida_mapa)

html = f"""
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title></title>
  </head>
  <body style="text-align: center;">
    <div><img src="{img}" alt="{img}"></div>
    <div><img src="../../images/v_focos_paises_mes_atual.png" alt="legenda"></div>
</body>
  </html>
"""
saida_mapa_html = saida_mapa.replace(".png", ".html")
with open(saida_mapa_html, "w") as f:
    f.write(html)
print(saida_mapa_html)
