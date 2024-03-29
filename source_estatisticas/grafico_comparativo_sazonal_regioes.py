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

path_saida = "%s/regiao"%(get.returnPath())
ANO_ATUAL = dt.datetime.now().year


regioes_dict = {
    "norte": [12, 16, 13, 15, 11, 14, 17],
    "nordeste": [27, 29, 23, 21, 25, 26, 22, 24, 28],
    "centro-oeste": [52, 51, 50, 53],
    "sul": [41, 43, 42],
    "sudeste": [32, 31, 33, 35],
    "amazônia_legal": [3],
    "vale_do_paraíba": [4],
    "map": [2],
}

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

DATA_ATUAL = dt.datetime.now()
ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = DIA_ATUAL - 1
MES_ONTEM = ONTEM.month
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


for key, value in regioes_dict.items():
    lista_regioes = ",".join(map(str, value))

    if key not in ("amazônia_legal", "vale_do_paraíba", "map"):
        sql = f""" 
                    select 
                    extract(year from "data")::int ano,
                    extract(month from "data")::int mes,
                    sum(nfocos) qtd
                    from 
                    view_focos_munic_ref
                    where estado_id1 in ({lista_regioes}) and pais_id0=33 and data <= '{ONTEM}' group by 1,2"""
    else:
        sql = f"""                    
                    select 
                    extract(year from "data")::int ano,
                    extract(month from "data")::int mes,
                    sum(nfocos) qtd from 
                    view_focos_regiao_especial
                    where id_regiao = ({lista_regioes}) and ref and data <= '{ONTEM}'
                    group by 1,2"""

    print(key)

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

    html_sazonal = """
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
                <div style='width: 900px'>
                    <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo mensal da região: <b>%s</b></big></center>
                    <center><div id="comparativo_historico_paises"></div></center>
                </div>
                </center>
            </div>
            </body>
            </html>
    """ % (
        data_graficos,
        key.replace("_", " ").title(),
    )

    primeiro_semestre = copia[:6]
    primeiro_semestre = str(primeiro_semestre.reset_index().values.tolist())

    html_1_semestre = """
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
                <div style='width: 900px'>
                    <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo 1º semestre da região: <b>%s</b></big></center>
                    <center><div id="comparativo_historico_paises"></div></center>
                </center>
            </div>
            </div>
            </body>
            </html>
    """ % (
        primeiro_semestre,
        key.replace("_", " ").title(),
    )

    segundo_semestre = copia[6:]
    segundo_semestre = str(segundo_semestre.reset_index().values.tolist())

    html_2_semestre = """
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
                    <div style='width: 900px'>
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo 2º semestre da região: <b>%s</b></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                </center>
            </div>
            </div>
            </body>
            </html>
    """ % (
        segundo_semestre,
        key.replace("_", " ").title(),
    )

    regiao = normalize("NFKD", key).encode("ASCII", "ignore").decode("ASCII")

    with open(
        os.path.join(
            path_saida, "grafico_comparativo_sazonal_estado_{}.html".format(regiao)
        ),
        "w",
    ) as saida:
        saida.write(html_sazonal)

    with open(
        os.path.join(
            path_saida,
            "grafico_comparativo_primeiro_semestre_estado_{}.html".format(regiao),
        ),
        "w",
    ) as saida:
        saida.write(html_1_semestre)

    with open(
        os.path.join(
            path_saida,
            "grafico_comparativo_segundo_semestre_estado_{}.html".format(regiao),
        ),
        "w",
    ) as saida:
        saida.write(html_2_semestre)

print(saida)
