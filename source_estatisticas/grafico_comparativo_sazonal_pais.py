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


DATA_ATUAL = dt.datetime.now()
ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = DIA_ATUAL - 1
MES_ONTEM = ONTEM.month
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


paises_dict = {
    12: "argentina",
    28: "bolivia",
    98: "guyana",
    33: "brasil",
    48: "chile",
    53: "colombia",
    249: "venezuela",
    219: "suriname",
    68: "equador",
    177: "paraguai",
    178: "peru",
    245: "uruguai",
    75: "ilhas_malvinas",
    80: "guyana_francesa",
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


for key, value in paises_dict.items():
    print("%s %s" % (key, value))

    sql = (
        f"""
    select 
        extract(year from "data")::int ano,
        extract(month from "data")::int mes,
        sum(nfocos) qtd
    from 
        view_focos_munic_ref
    where 
        pais_id0=%s
        and data <= '{ONTEM}'
    group by 1, 2
    """
        % key
    )

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
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo mensal do país: <b>%s</b><big></big></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                    </div>
                </center>
            </div>
            </body>
            </html>
    """ % (
        data_graficos,
        value.replace("_", " ").title(),
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
                    <div style="width: 900px">
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo 1º semestre do país: <b>%s</b><big></big></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                    </div>
                </center>
            </div>
            </body>
            </html>
    """ % (
        primeiro_semestre,
        value.replace("_", " ").title(),
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
                    <div style="width: 900px">
                        <center><div style='font-family: "open_sansregular", Arial, Helvetica, sans-serif;'><big>Comparativo 2º semestre do país: <b>%s</b><big></big></big></center>
                        <center><div id="comparativo_historico_paises"></div></center>
                    </div>
                </center>
            </div>
            </body>
            </html>
    """ % (
        segundo_semestre,
        value.replace("_", " ").title(),
    )

    with open(
        os.path.join(
            path_saida, "grafico_comparativo_sazonal_pais_{}.html".format(value)
        ),
        "w",
    ) as saida:
        saida.write(html_sazonal)

    with open(
        os.path.join(
            path_saida,
            "grafico_comparativo_primeiro_semestre_pais_{}.html".format(value),
        ),
        "w",
    ) as saida:
        saida.write(html_1_semestre)

    with open(
        os.path.join(
            path_saida,
            "grafico_comparativo_segundo_semestre_pais_{}.html".format(value),
        ),
        "w",
    ) as saida:
        saida.write(html_2_semestre)

print(saida)
