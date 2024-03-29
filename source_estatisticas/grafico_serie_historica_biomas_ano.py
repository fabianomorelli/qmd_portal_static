#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os

import geopandas as gpd
import numpy as np
import pandas as pd
from sqlalchemy import create_engine, pool
import get_file_directory as get

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)

path_saida = "%s/bioma"%(get.returnPath())
ANO_ATUAL = dt.datetime.now().year


biomas_dict = {
    1: "caatinga",
    2: "cerrado",
    3: "pantanal",
    4: "pampa",
    5: "amazonia",
    6: "mata_atlantica",
}

DATA_ATUAL = dt.datetime.now()
ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = DIA_ATUAL - 1
MES_ONTEM = ONTEM.month
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


sql = f"""
    select 
        extract(year from "data")::int ano,
        id_bioma,
        sum(nfocos) qtd
    from 
        view_focos_bioma_ref
    where data <= '{ONTEM}'
    group by 1,2 order by 1,2
"""

engine.connect()
saida = pd.read_sql(sql, engine).sort_values("ano", ascending=True)
saida.set_index("ano", inplace=True)

data_graficos = pd.DataFrame()
#data_graficos["Média de Focos Anual"] = round(saida.groupby(saida.index).mean()["qtd"])
data_graficos["Amazônia"] = saida[saida.id_bioma == 5]["qtd"]
data_graficos["Caatinga"] = saida[saida.id_bioma == 1]["qtd"]
data_graficos["Cerrado"] = saida[saida.id_bioma == 2]["qtd"]
data_graficos["Mata Atlântica"] = saida[saida.id_bioma == 6]["qtd"]
data_graficos["Pampa"] = saida[saida.id_bioma == 4]["qtd"]
data_graficos["Pantanal"] = saida[saida.id_bioma == 3]["qtd"]

data_graficos.index = data_graficos.index.astype(str)

data_graficos.fillna(0, inplace=True)
data_graficos = str(data_graficos.reset_index().values.tolist())


html = """
        <html><head><meta charset="UTF-8"></head>
            <body>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart'], 'language': 'pt'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart(dados, tipo, container) {
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Ano');
              //data.addColumn('number', "Média de Focos Anual");
              data.addColumn('number', "Amazônia");
              data.addColumn('number', "Caatinga");
              data.addColumn('number', "Cerrado");
              data.addColumn('number', "Mata Atlântica");
              data.addColumn('number', "Pampa");
              data.addColumn('number', "Pantanal");

              data.addRows(%s);

              var options = {
                  title: 'Série histórica de focos por bioma e ano',
                  vAxis: { title: 'N° Focos AQUA_M-T' },
                  hAxis: {
                    title: 'Ano',
                    slantedText: true,
                    slantedTextAngle: 45 // here you can even use 180 
                    },
                    //seriesType: 'bars',
                    focusTarget: 'category',
                    bar: { groupWidth: 10},
                    pointSize: 3,
                    series: { 1: { type: 'line' }, 2: { type: 'line', visibleInLegend: true }, 3: { type: 'line', visibleInLegend: true }, 4: { type: 'line', visibleInLegend: true }, 5: { type: 'line', visibleInLegend: true }, 6: { type: 'line', visibleInLegend: true } },
                width: 900,
                height: 450
          };
      
              var chart = new google.visualization.ComboChart(document.getElementById('comparativo_historico_paises'));
              chart.draw(data, options);
              }
            </script>
            <div>
                <center>
                    <div style='width: 900px'>
                    <div id="comparativo_historico_paises"></div>
                </center>
            </div>
            </body>
            </html>
""" % (
    data_graficos
)

with open(
    os.path.join(path_saida, "grafico_serie_historica_estado_biomas_ano.html"), "w"
) as saida:
    saida.write(html)


print(f"Concluído: grafico_serie_historica_estado_biomas_ano.html")
