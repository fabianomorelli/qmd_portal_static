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

path_saida = get.returnPath()
path_saida = os.path.join(path_saida, 'fm_situacao')

ANO_ATUAL = dt.datetime.now().year


DATA_ATUAL = dt.datetime.now()
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIFERENCA = 0

ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


months = {
    1: "Jan",
    2: "Fev",
    3: "Mar",
    4: "Abr",
    5: "Mai",
    6: "Jun",
    7: "Jul",
    8: "Ago",
    9: "Set",
    10: "Out",
    11: "Nov",
    12: "Dez",
}


sql = f"""
    select 
        extract(year from "data")::int ano,
        extract(month from "data")::int mes,
        sum(nfocos) qtd
    from 
        view_focos_munic_ref
    where 
        pais_id0 in (12,28,33,48,53,68,98,80,177,178,219,245,249)
        and data <= '{ONTEM}'
    group by 1, 2
    """

engine.connect()
df = pd.read_sql(sql, engine).sort_values("mes", ascending=True)
df.rename(columns=months, inplace=True)
df.set_index("mes", inplace=True)

data_graficos = pd.DataFrame()
data_graficos["max"] = df[df.ano < ANO_ATUAL].groupby(["mes"]).max()["qtd"]
data_graficos["med"] = round(df[df.ano < ANO_ATUAL].groupby(["mes"]).mean()["qtd"])
data_graficos["min"] = df[df.ano < ANO_ATUAL].groupby(["mes"]).min()["qtd"]
data_graficos["2019"] = df[df.ano == ANO_ATUAL]["qtd"]

data_graficos.fillna(0, inplace=True)
data_graficos.rename(index=months, inplace=True)

cols = data_graficos.columns.tolist()
cols = cols[-1:] + cols[:-1]
data_graficos = data_graficos[cols]
copia = data_graficos
data_graficos = str(data_graficos.reset_index().values.tolist())

html_sazonal = (
    """
            <html><head><meta charset="UTF-8"></head>
            <body>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart'], 'language': 'pt'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart(dados, tipo, container) {
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Mês');
              data.addColumn('number', new Date().getFullYear().toString());
              data.addColumn('number', "Máx");
              data.addColumn('number', "Méd");
              data.addColumn('number', "Mín");

              data.addRows(%s);

              var options = {
                title: '',
              
                vAxis: { title: 'N° Focos AQUA_M-T' },
                hAxis: { title: 'Mês' },
                seriesType: 'bars',
                focusTarget: 'category',
                bar: { groupWidth: 10},
                pointSize: 3,
                series: { 1: { type: 'line' }, 2: { type: 'line', visibleInLegend: true }, 3: { type: 'line', visibleInLegend: true } },
                width: 900,
                height: 450
              };
              var chart = new google.visualization.ComboChart(document.getElementById('comparativo_historico_paises'));
              chart.draw(data, options);
              }
            </script>
            <div>
                <center>
                    <div style="width: 900px">
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo mensal da: <b>América do Sul</b><big></big></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                    </div>
                </center>
            </div>
            </body>
            </html>
    """
    % data_graficos
)

primeiro_semestre = copia[:6]
primeiro_semestre = str(primeiro_semestre.reset_index().values.tolist())

html_1_semestre = (
    """
            <html><head><meta charset="UTF-8"></head>
            <body>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart'], 'language': 'pt'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart(dados, tipo, container) {
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Mês');
              data.addColumn('number', new Date().getFullYear().toString());
              data.addColumn('number', "Máx");
              data.addColumn('number', "Méd");
              data.addColumn('number', "Mín");

              data.addRows(%s);

              var options = {
                title: '',
              
                vAxis: { title: 'N° Focos AQUA_M-T' },
                hAxis: { title: 'Mês' },
                seriesType: 'bars',
                focusTarget: 'category',
                bar: { groupWidth: 10},
                pointSize: 3,
                series: { 1: { type: 'line' }, 2: { type: 'line', visibleInLegend: true }, 3: { type: 'line', visibleInLegend: true } },
                width: 900,
                height: 450
              };
              var chart = new google.visualization.ComboChart(document.getElementById('comparativo_historico_paises'));
              chart.draw(data, options);
              }
            </script>
            <div>
                <center>
                    <div style="width: 900px">
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo 1º semestre do país: <b>América do Sul</b><big></big></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                    </div>
                </center>
            </div>
            </body>
            </html>
    """
    % primeiro_semestre
)

segundo_semestre = copia[6:]
segundo_semestre = str(segundo_semestre.reset_index().values.tolist())

html_2_semestre = (
    """
            <html><head><meta charset="UTF-8"></head>
            <body>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart'], 'language': 'pt'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart(dados, tipo, container) {
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Mês');
              data.addColumn('number', new Date().getFullYear().toString());
              data.addColumn('number', "Máx");
              data.addColumn('number', "Méd");
              data.addColumn('number', "Mín");

              data.addRows(%s);

              var options = {
                title: '',
              
                vAxis: { title: 'N° Focos AQUA_M-T' },
                hAxis: { title: 'Mês' },
                seriesType: 'bars',
                focusTarget: 'category',
                bar: { groupWidth: 10},
                pointSize: 3,
                series: { 1: { type: 'line' }, 2: { type: 'line', visibleInLegend: true }, 3: { type: 'line', visibleInLegend: true } },
                width: 900,
                height: 450
              };
              var chart = new google.visualization.ComboChart(document.getElementById('comparativo_historico_paises'));
              chart.draw(data, options);
              }
            </script>
            <div>
                <center>
                    <div style="width: 900px">
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo 2º semestre do país: <b>América do Sul</b><big></big></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                    </div>
                </center>
            </div>
            </body>
            </html>
    """
    % segundo_semestre
)

with open(
    os.path.join(path_saida, "grafico_comparativo_sazonal_pais_america_do_sul.html"),
    "w",
) as saida:
    saida.write(html_sazonal)

with open(
    os.path.join(
        path_saida, "grafico_comparativo_primeiro_semestre_pais_america_do_sul.html"
    ),
    "w",
) as saida:
    saida.write(html_1_semestre)

with open(
    os.path.join(
        path_saida, "grafico_comparativo_segundo_semestre_pais_america_do_sul.html"
    ),
    "w",
) as saida:
    saida.write(html_2_semestre)


saida
