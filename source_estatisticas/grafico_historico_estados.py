#!/usr/bin/env python
# coding: utf-8


import os

import geopandas as gpd
import numpy as np
import pandas as pd
from sqlalchemy import create_engine, pool
import get_file_directory as get

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)

path_saida = "%s/estado"%(get.returnPath())


estados_dict = {
    11: "rondonia",
    12: "acre",
    13: "amazonas",
    14: "roraima",
    15: "para",
    16: "amapa",
    17: "tocantins",
    21: "maranhao",
    22: "piaui",
    23: "ceara",
    24: "rio_grande_do_norte",
    25: "paraiba",
    26: "pernambuco",
    27: "alagoas",
    28: "sergipe",
    29: "bahia",
    31: "minas_gerais",
    32: "espirito_santo",
    33: "rio_de_janeiro",
    35: "sao_paulo",
    41: "parana",
    42: "santa_catarina",
    43: "rio_grande_do_sul",
    50: "mato_grosso_do_sul",
    51: "mato_grosso",
    52: "goias",
    53: "distrito_federal",
}


for key, value in estados_dict.items():
    print("%s %s" % (key, value))

    sql = f"""
    select 
        extract(year from "data")::int ano,
        extract(month from "data")::int mes,
        sum(nfocos) qtd
    from 
        view_focos_munic_ref
    where 
        estado_id1={key} and pais_id0=33
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
        os.path.join(path_saida, "grafico_historico_estado_{}.html".format(value)), "w"
    ) as saida:
        saida.write(html)

    pivot.to_csv(os.path.join(path_saida, "csv_estatisticas/historico_estado_{}.csv".format(value)))

    print(f"Concluído: grafico_historico_estado_{value}.html")