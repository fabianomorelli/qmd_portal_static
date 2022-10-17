#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os
from unicodedata import normalize

import geopandas as gpd
import numpy as np
import pandas as pd
from sqlalchemy import create_engine, pool
import get_file_directory as get

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)

path_saida = "%s/bioma"%(get.returnPath())


biomas_dict = {
    1: "caatinga",
    2: "cerrado",
    3: "pantanal",
    4: "pampa",
    5: "amazônia",
    6: "mata_atlântica",
}

DATA_ATUAL = dt.datetime.now()
ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = DIA_ATUAL - 1
MES_ONTEM = ONTEM.month
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


for key, value in biomas_dict.items():
    print("%s %s" % (key, value))

    sql = f"""
    select 
        extract(year from "data")::int ano,
        sum(nfocos) qtd
    from 
        view_focos_bioma_ref
    where 
        id_bioma={key}
        and data <= '{ONTEM}'
    group by 1
    """
    engine.connect()
    saida = pd.read_sql(sql, engine).sort_values("ano", ascending=True)
    saida["ano"] = saida["ano"].astype(str)
    saida.set_index("ano", inplace=True)
    data_graficos = str(saida.reset_index().values.tolist())

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
              data.addColumn('number', 'Focos');
              data.addRows( %s);

              var options = {
                chart: {
                  title: '',
                },
                width: 900,
                height: 450,
                legend: { position: "none" },
                focusTarget: 'category',
                hAxis: {
                  ticks: 1,
                  slantedText: true,
                  slantedTextAngle: 45 // here you can even use 180 
                },
                colors: ['#CC0000', '#FF6600', '#FFCC00']
              };

              var chart = new google.visualization.ColumnChart(document.getElementById("serie_historica_estados"));
              chart.draw(data, options);
            }
            </script>
            <div>
                <center>
                <div style='width: 900px'>
                    <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Série histórica do bioma: <b>%s</b><big></big></big></center>
                    <center><div id="serie_historica_estados"></div></center>
                </center>
                </div>
            </div>
            </body>
            </html>
    """ % (
        data_graficos,
        value.replace("_", " ").title(),
    )
    with open(
        os.path.join(
            path_saida,
            "grafico_serie_historica_estado_{}.html".format(
                normalize("NFKD", value).encode("ASCII", "ignore").decode("ASCII")
            ),
        ),
        "w",
    ) as saida:
        saida.write(html)

print(saida)
