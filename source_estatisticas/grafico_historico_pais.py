#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os
import geopandas as gpd
import numpy as np
import pandas as pd
from sqlalchemy import create_engine, pool
import get_file_directory as get

DATA_ATUAL = dt.datetime.now()
ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = DIA_ATUAL - 1
MES_ONTEM = ONTEM.month
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)

path_saida = get.returnPath()


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


for key, value in paises_dict.items():
    print("%s %s" % (key, value))

    sql = f"""
    select 
        extract(year from "data")::int ano,
        extract(month from "data")::int mes,
        sum(nfocos) qtd
    from 
        view_focos_munic_ref
    where 
        pais_id0={key}
        and data <= '{ONTEM}'
    group by 1,2
    """

    engine.connect()
    df = pd.read_sql(sql, engine).sort_values(["ano", "mes"])
    df.rename(columns={"ano": "Ano"}, inplace=True)

    months = {
        1: "Janeiro",
        2: "Fevereiro",
        3: "Março",
        4: "Abril",
        5: "Maio",
        6: "Junho",
        7: "Julho",
        8: "Agosto",
        9: "Setembro",
        10: "Outubro",
        11: "Novembro",
        12: "Dezembro",
    }

    pivot = pd.pivot_table(
        df,
        values="qtd",
        index=["Ano"],
        columns=["mes"],
        aggfunc=np.sum,
        margins=True,
        margins_name="Total",
    )
    max_series = pd.Series(pivot[: len(pivot) - 2].max(), name="Máximo*")
    mean_series = pd.Series(round(pivot[: len(pivot) - 2].mean()), name="Média*")
    min_series = pd.Series(pivot[: len(pivot) - 2].min(), name="Mínimo*")

    pivot = pivot.append(max_series)
    pivot = pivot.append(mean_series)
    pivot = pivot.append(min_series)

    pivot.rename(columns=months, inplace=True)
    pivot.drop(index="Total", axis=0, inplace=True)

    pivot_color = pivot.style.highlight_max(
        axis=0,
        color="#CC0000",
        subset=pd.IndexSlice[pivot.index[:-4].values, pivot.columns[:-1].values],
    ).highlight_min(
        axis=0,
        color="#FFCC00",
        subset=pd.IndexSlice[pivot.index[:-4].values, pivot.columns[:-1].values],
    )

    del pivot_color.index.name

    html = pivot_color.render().replace("nan", "-").replace("mes", "Ano")
    append = """
    <style>

    * {
        text-align: right;
        border: 0px;
    }

    body {
        margin: 0;
    }

    table {
        text-align: left;
        border-collapse: collapse;
        border-spacing: 0px;
        margin-bottom: 1em;
        font-family: "open_sansregular", Arial, Helvetica, sans-serif;
        font-size: 12px;
        border: 1px solid #666;
        border-radius: 6px;
        padding: 7px;
        margin: 0 auto;
        overflow-y: auto;
        border: none;
        font-weight: bold;
    }

    table th {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        background: #ddd none repeat scroll 0 0;
        border-color: #e7e7e7;
        border-image: none;
        border-style: solid solid none;
        border-width: 0.1em;
        color: #666;
        text-align: center;
    }

    table tr {
        background: #f5f5f5 none repeat scroll 0 0;
        border-bottom: 1px solid #d5d5d5;
        border-right: 1px solid #d5d5d5;
    }

    table td, table th {
        padding: 9px;
        vertical-align: top;
    }

    table thead tr{
        background-color: #FDF2E3;
    }

    table thead tr:nth-child(2) {
        display: none;
    }

    table tr {
        background: #f5f5f5 none repeat scroll 0 0;
    }

    table tr:last-child {
        background-color: #FFCC00 !important;
    }

    table tr:nth-last-child(2) {
        background-color: #FF6600 !important;
    }

    table tr:nth-last-child(3) {
        color: #ffffff!important;
        background-color: #CC0000 !important;
    }

    table th:first-child {  
      margin-left: 100px !important;
    }

    </style>
    """

    html = '<head><meta charset="UTF-8"></head>' + html + append
    with open(
        os.path.join(path_saida, "grafico_historico_pais_{}.html".format(value)), "w"
    ) as saida:
        saida.write(html)
    
    pivot.to_csv(os.path.join(path_saida, "csv_estatisticas/historico_pais_{}.csv".format(value)))

    print(f"Concluído: grafico_historico_pais_{value}.html")
    
