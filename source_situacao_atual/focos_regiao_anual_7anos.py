#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os

#import geopandas as gpd
import numpy as np
import pandas as pd
from sqlalchemy import create_engine, pool

import get_file_directory as get

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)


ARQUIVO_SAIDA = "%s/focos_regiao_anual_7anos.html"%(get.returnPath())


DATA_ATUAL = dt.datetime.now()
pd.options.display.float_format = "{:.0f}".format
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day - 1


sql = f"""
select 
   extract(year from vre."data")::int ano,
   re.nome nome,
   sum(nfocos) qtd 
from 
   view_focos_regiao_especial vre, dados_geo.regioes_especiais re
where 
    vre."ref"
    and re.gid=vre.id_regiao
    and (
        (extract(month from "data") = {MES_ATUAL} and extract(day from "data") <= {DIA_ATUAL})
        or 
        (extract(month from "data") < {MES_ATUAL})
    )
    and data >= '{ANO_ATUAL-6}0101'
group by 1,2
"""

engine.connect()
df = pd.read_sql(sql, engine).sort_values(["ano"])


pivot = pd.pivot_table(
    df,
    values="qtd",
    index=["nome"],
    columns=["ano"],
    margins=True,
    margins_name="TOTAL",
    aggfunc="sum",
)


del pivot["TOTAL"]


saida = pd.DataFrame()
for ano in pivot.columns[:-1]:
    ano_pos = ano + 1
    saida[ano] = pivot[ano].apply(lambda x: int(x) if not np.isnan(x) else 0)

    name_col = f"_{ano}"
    saida[name_col] = 0
    saida[ano_pos] = pivot[ano_pos].apply(lambda x: int(x) if not np.isnan(x) else 0)

    saida.loc[saida[ano] > 0, name_col] = round(
        ((saida[ano_pos] - saida[ano]) / saida[ano]) * 100, 1
    )
    saida.loc[saida[ano] == 0, name_col] = round(saida[ano_pos] * 100, 1)
    saida.loc[saida[ano_pos] == 0, name_col] = -100
    saida.loc[(saida[ano] == 0) & (saida[ano_pos] == 0), name_col] = 0

    saida[ano] = saida[ano].apply(
        lambda x: format(int(x), "8_d").replace("_", ".") if not np.isnan(x) else 0
    )
    saida[ano_pos] = saida[ano_pos].apply(
        lambda x: format(int(x), "8_d").replace("_", ".") if not np.isnan(x) else 0
    )
    saida[name_col] = saida[name_col].apply(
        lambda x: format(int(x), "8_d").replace("_", ".") + "%"
        if not np.isnan(x)
        else 0
    )
    saida.rename(columns={name_col: "Dif%"}, inplace=True)
del saida.index.name


# grava saida em html
saida.to_html(ARQUIVO_SAIDA)
append = """
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function() {
   jQuery('.main-table').clone(true).appendTo('#table-scroll').addClass('clone');   
 });
</script>
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

table thead tr{
    background-color: #FDF2E3;
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
table th{  
  width: 65px;
}

*{
text-align: right;
border: 0px;
}

.table-scroll {
    position:relative;
    margin:auto;
    overflow:hidden;
}
.table-wrap {
    width:100%;
    overflow:auto;
}
.table-scroll table {
    width:100%;
    margin:auto;
    border-spacing:0;
}
.table-scroll th, .table-scroll td {
    padding:5px 10px;
    white-space:nowrap;
    vertical-align:top;
}
.clone {
    position:absolute;
    top:0;
    left:0;
    pointer-events:none;
}
.clone th, .clone td {
    visibility:hidden
}
thead{
border: 1px solid black;
}
.clone tbody th {
    visibility:visible;
}
.clone .fixed-side {
    visibility:visible;
}

.esquerda{
    padding-right: 150px;
    border-right: 1px solid black;
}
</style>
"""
comando = f'echo "{append}" >> {ARQUIVO_SAIDA}'
os.system(comando)
with open(ARQUIVO_SAIDA) as f:
    new_text = f.read().replace("<th>", '<th class="esquerda">')
    new_text = new_text.replace(
        '<table border="1" class="dataframe">', '<table class="main-table">'
    )

inicio = """<div id="table-scroll" class="table-scroll"><div class="table-wrap">"""
fim = """</div></div>"""
new_text = inicio + new_text + fim
with open(ARQUIVO_SAIDA, "w") as f:
    f.write(new_text)


# grava saida em html
saida.to_html(ARQUIVO_SAIDA)
append = """
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function() {
   jQuery('.main-table').clone(true).appendTo('#table-scroll').addClass('clone');   
 });
</script>

<style>
.dataframe,.main-table{
    width: 100%;
}
table {
    border: none;
    }
.espaco_esquerda {
    padding-left: 60px;
}

table tr:nth-child(even) {
    background-color: #FDF2E3;
}

table thead tr{
    background-color: #FDF2E3;
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
table th{  
  padding-left: 20px;
  text-align: left;
}


table *{
text-align: right;
border: 0px;
}

.table-scroll {
    position:relative;
    margin:auto;
    overflow:hidden;
}
.table-wrap {
    width:100%;
    overflow:auto;
}
.table-scroll table {
    width:100%;
    margin:auto;
    border-spacing:0;
}
.table-scroll th, .table-scroll td {
    padding:5px 10px;
    white-space:nowrap;
    vertical-align:top;
}
.clone {
    position:absolute;
    top:0;
    left:0;
    pointer-events:none;
}
.clone th, .clone td {
    visibility:hidden
}
thead tr th{
    text-align: right;
}
thead{
border: 1px solid black;
}
.clone tbody th {
    visibility:visible;
}
.clone .fixed-side {
    visibility:visible;
}

.esquerda{
    padding-right: 120px;
    border-right: 1px solid black;
}
</style>
"""
comando = f'echo "{append}" >> {ARQUIVO_SAIDA}'
os.system(comando)
with open(ARQUIVO_SAIDA) as f:
    new_text = f.read().replace("<th>", '<th class="esquerda">')
    new_text = new_text.replace(
        '<table border="1" class="dataframe">', '<table class="main-table">'
    )

inicio = """<div id="table-scroll" class="table-scroll"><div class="table-wrap">"""
fim = """</div></div>"""
new_text = inicio + new_text + fim
with open(ARQUIVO_SAIDA, "w") as f:
    f.write(new_text)
print(f"{ARQUIVO_SAIDA}")
