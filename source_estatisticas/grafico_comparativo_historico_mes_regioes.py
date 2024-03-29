#!/usr/bin/env python
# coding: utf-8

# In[56]:


import pandas as pd
import geopandas as gpd
import os
from sqlalchemy import create_engine, pool
import datetime as dt
import numpy as np
import calendar
import get_file_directory as get


# In[57]:

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)

path_saida = '%s/regiao'%(get.returnPath())
DATA_ATUAL = dt.datetime.now()
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
MES_PASSADO = MES_ATUAL - 1
if MES_ATUAL == 1:
    MES_PASSADO = 12

ULTIMO_DIA_MES_ATUAL_ANO_ANTERIOR = calendar.monthrange(ANO_ATUAL - 1, MES_ATUAL)[1]
ULTIMO_DIA_MES_ATUAL_ANO_ATUAL = calendar.monthrange(ANO_ATUAL, MES_ATUAL)[1]
ULTIMO_DIA_MES_PASSADO_ANO_ANTERIOR = calendar.monthrange(ANO_ATUAL - 1, MES_PASSADO)[1]
ULTIMO_DIA_MES_PASSADO_ANO_ATUAL = calendar.monthrange(ANO_ATUAL, MES_PASSADO)[1]


# In[58]:


def getMonth(month):
    if month == 1: return "Jan"
    if month == 2: return "Fev"
    if month == 3: return "Mar"
    if month == 4: return "Abr"
    if month == 5: return "Mai"
    if month == 6: return "Jun"
    if month == 7: return "Jul"
    if month == 8: return "Ago"
    if month == 9: return "Set"
    if month == 10: return "Out"
    if month == 11: return "Nov"
    if month == 12: return "Dez"


# In[59]:


regioes_dict = {
    'norte': [12,16,13,15,11,14,17],
    'nordeste': [27,29,23,21,25,26,22,24,28],
    'centro-oeste': [52,51,50,53],
    'sul': [41,43,42],
    'sudeste': [32,31,33,35],
    'amazonia_legal': [3],
    'vale_do_paraiba': [4],
    'map': [2]
}

DATA_ATUAL = dt.datetime.now()
ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = DIA_ATUAL - 1
MES_ONTEM = ONTEM.month
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")


# In[60]:


for key, value in regioes_dict.items():
    lista_regioes = ','.join(map(str, value))
    
    if DIA_ATUAL > 1:
        if key not in ('amazonia_legal', 'vale_do_paraiba', 'map'):
            sql = f"""
                    select dias as dia, coalesce(mes, {MES_ATUAL}) mes, coalesce(ano, {ANO_ATUAL-1}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_ATUAL} as mes,
                        {ANO_ATUAL - 1} as ano,
                        sum(nfocos) total from view_focos_munic_ref
                        where estado_id1 in ({lista_regioes}) 
                        and pais_id0=33
                        and extract(year from "data") = {ANO_ATUAL - 1}
                        and extract(month from "data") = {MES_ATUAL}
                        group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_ATUAL_ANO_ANTERIOR}) dias on focos.dia=dias
                    union all 
                    select dias as dia, coalesce(mes, {MES_ATUAL}) mes, coalesce(ano, {ANO_ATUAL}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_ATUAL} as mes,
                        {ANO_ATUAL} as ano,
                        sum(nfocos) total
                        from view_focos_munic_ref
                        where estado_id1 in ({lista_regioes}) 
                        and pais_id0=33
                        and extract(year from "data") = {ANO_ATUAL}
                        and extract(month from "data") = {MES_ATUAL}
                        and data <= '{ONTEM}'
                    group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_ATUAL_ANO_ATUAL}) dias on focos.dia=dias
            """
        else:
            sql = f"""
                    select dias as dia, coalesce(mes, {MES_ATUAL}) mes, coalesce(ano, {ANO_ATUAL-1}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_ATUAL} as mes,
                        {ANO_ATUAL - 1} as ano,
                        sum(nfocos) total from view_focos_regiao_especial
                        where id_regiao = ({lista_regioes}) 
                        and ref
                        and extract(year from "data") = {ANO_ATUAL - 1}
                        and extract(month from "data") = {MES_ATUAL}
                        group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_ATUAL_ANO_ANTERIOR}) dias on focos.dia=dias
                    union all 
                    select dias as dia, coalesce(mes, {MES_ATUAL}) mes, coalesce(ano, {ANO_ATUAL}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_ATUAL} as mes,
                        {ANO_ATUAL} as ano,
                        sum(nfocos) total from view_focos_regiao_especial
                        where id_regiao = ({lista_regioes}) 
                        and ref 
                        and extract(year from "data") = {ANO_ATUAL}
                        and extract(month from "data") = {MES_ATUAL}
                        and data <= '{ONTEM}'
                    group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_ATUAL_ANO_ATUAL}) dias on focos.dia=dias
            """
    else:
        if key not in ('amazonia_legal', 'vale_do_paraiba', 'map'):
            sql = f"""
                    select dias as dia, coalesce(mes, {MES_PASSADO}) mes, coalesce(ano, {ANO_ATUAL-1}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_PASSADO} as mes,
                        {ANO_ATUAL - 1} as ano,
                        sum(nfocos) total from view_focos_munic_ref
                        where estado_id1 in ({lista_regioes}) 
                        and pais_id0=33
                        and extract(year from "data") = {ANO_ATUAL - 1}
                        and extract(month from "data") = {MES_PASSADO}
                        group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_PASSADO_ANO_ANTERIOR}) dias on focos.dia=dias
                    union all 
                    select dias as dia, coalesce(mes, {MES_PASSADO}) mes, coalesce(ano, {ANO_ATUAL}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_PASSADO} as mes,
                        {ANO_ATUAL} as ano,
                        sum(nfocos) total
                        from view_focos_munic_ref
                        where estado_id1 in ({lista_regioes}) 
                        and pais_id0=33
                        and extract(year from "data") = {ANO_ATUAL}
                        and extract(month from "data") = {MES_PASSADO}
                        and data <= '{ONTEM}'
                    group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_PASSADO_ANO_ATUAL}) dias on focos.dia=dias
            """
        else:
            sql = f"""
                    select dias as dia, coalesce(mes, {MES_PASSADO}) mes, coalesce(ano, {ANO_ATUAL-1}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_PASSADO} as mes,
                        {ANO_ATUAL - 1} as ano,
                        sum(nfocos) total from view_focos_regiao_especial
                        where id_regiao = ({lista_regioes}) 
                        and ref
                        and extract(year from "data") = {ANO_ATUAL - 1}
                        and extract(month from "data") = {MES_PASSADO}
                        group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_PASSADO_ANO_ANTERIOR}) dias on focos.dia=dias
                    union all 
                    select dias as dia, coalesce(mes, {MES_PASSADO}) mes, coalesce(ano, {ANO_ATUAL}) ano, coalesce(total, 0) qtd from (
                        select 
                        extract(day from data)::int dia,
                        {MES_PASSADO} as mes,
                        {ANO_ATUAL} as ano,
                        sum(nfocos) total from view_focos_regiao_especial
                        where id_regiao = ({lista_regioes}) 
                        and ref 
                        and extract(year from "data") = {ANO_ATUAL}
                        and extract(month from "data") = {MES_PASSADO}
                        and data <= '{ONTEM}'
                    group by 1,2,3 order by 1
                    ) focos 
                    right join generate_series(1, {ULTIMO_DIA_MES_PASSADO_ANO_ATUAL}) dias on focos.dia=dias
            """
        
    engine.connect()
    df = pd.read_sql(sql, engine).sort_values(['ano'])
    
    pivot = pd.pivot_table(df, values='qtd', index=['dia'],columns=['ano'], margins=True, margins_name='TOTAL', aggfunc='sum')
    del pivot['TOTAL']
        
    if len(pivot.columns[:-1]):
        saida = pd.DataFrame()
        for ano in pivot.columns[:-1]:
            ano_pos = ano+1
            saida[ano] = pivot[ano].apply(lambda x: int(x) if not np.isnan(x) else 0)
            saida[ano_pos] = pivot[ano_pos].apply(lambda x: int(x) if not np.isnan(x) else 0)
            
            saida[ano] = saida[ano].apply(lambda x: format(int(x), '8_d').replace('_', '.') if not np.isnan(x) else 0 )
            saida[ano_pos] = saida[ano_pos].apply(lambda x: format(int(x), '8_d').replace('_', '.') if not np.isnan(x) else 0 )
            
            if DIA_ATUAL > 1:
                saida[ano_pos][(saida.index.isin(range(DATA_ATUAL.day, ULTIMO_DIA_MES_ATUAL_ANO_ATUAL+1)))] = '-'
            


        if DIA_ATUAL > 1:
            saida = saida.add_prefix(getMonth(MES_ATUAL)+'/')
        else:
            saida = saida.add_prefix(getMonth(MES_PASSADO)+'/')    
        saida.index.name = 'Dia'
        result = saida.T
        html = result.style.render()
        
    else:
        html = '<div style="text-align: center">Não existem focos ativos registrados para o mês atual</div>'
        
        
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

    table thead tr:nth-child(2) {
            display: none;
        }

    table th:first-child {  
      margin-left: 100px !important;
    }

    </style>
    """
        
    html = '<head><meta charset="UTF-8"></head>' + html + append
    
    with open(os.path.join(path_saida, "grafico_historico_mes_atual_estado_{}.html".format(key)), "w") as saida:
        saida.write(html)


# In[61]:


print(saida)


# In[ ]:




