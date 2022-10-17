#!/usr/bin/env python
# coding: utf-8

# In[410]:


import pandas as pd
from sqlalchemy import create_engine, pool
import datetime as dt
import numpy as np
import os
import calendar
import get_file_directory as get


# In[411]:

database = get.getDatabase()

#engine = create_engine('postgresql://queimadas:Qmd@1998@manaus.dgi.inpe.br:5432/api', poolclass=pool.NullPool)
engine = create_engine('postgresql://%s:%s@%s:%s/api'%(database["user"], database["password"], database["host"], database["port"]), poolclass=pool.NullPool)


# In[412]:


path_saida = get.returnPath()
DATA_ATUAL = dt.datetime.now()
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
MES_PASSADO = MES_ATUAL - 1
DIA_ATUAL = DATA_ATUAL.day
DIFERENCA = 0

ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ONTEM = ONTEM.strftime("%Y-%m-%d 23:59:00")
if MES_ATUAL == 1:
    MES_PASSADO = 12

ULTIMO_DIA_MES_ATUAL_ANO_ANTERIOR = calendar.monthrange(ANO_ATUAL - 1, MES_ATUAL)[1]
ULTIMO_DIA_MES_ATUAL_ANO_ATUAL = calendar.monthrange(ANO_ATUAL, MES_ATUAL)[1]
ULTIMO_DIA_MES_PASSADO_ANO_ANTERIOR = calendar.monthrange(ANO_ATUAL - 1, MES_PASSADO)[1]
ULTIMO_DIA_MES_PASSADO_ANO_ATUAL = calendar.monthrange(ANO_ATUAL, MES_PASSADO)[1]


# In[413]:


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


# In[414]:


#sql = f"""
#    select 
#        extract(day from v."data")::int dia,
#       extract(year from v."data")::int ano,
#        extract(month from v."data")::int mes,
#        sum(nfocos) qtd
#    from 
#        view_focos_munic_ref v
#    where 
#        pais_id0 in (12,28,33,48,53,68,98,80,177,178,219,245,249)
#        and extract(year from v."data") >= {ANO_ATUAL-1}
#        and extract(month from v."data") = {MES_ATUAL}
#        and data <= '{ONTEM}'
#    group by 1, 2, 3
#"""

if DIA_ATUAL > 1:
    sql = f"""
            select dias as dia, coalesce(mes, {MES_ATUAL}) mes, coalesce(ano, {ANO_ATUAL-1}) ano, coalesce(total, 0) qtd from (
                select 
                extract(day from data)::int dia,
                {MES_ATUAL} as mes,
                {ANO_ATUAL - 1} as ano,
                sum(nfocos) total from view_focos_munic_ref
                where
                pais_id0 in (12,28,33,48,53,68,98,80,177,178,219,245,249)
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
                where pais_id0 in (12,28,33,48,53,68,98,80,177,178,219,245,249)
                and extract(year from "data") = {ANO_ATUAL}
                and extract(month from "data") = {MES_ATUAL}
                and data <= '{ONTEM}'
            group by 1,2,3 order by 1
            ) focos 
            right join generate_series(1, {ULTIMO_DIA_MES_ATUAL_ANO_ATUAL}) dias on focos.dia=dias
    """
else:
    sql = f"""
            select dias as dia, coalesce(mes, {MES_PASSADO}) mes, coalesce(ano, {ANO_ATUAL-1}) ano, coalesce(total, 0) qtd from (
                select 
                extract(day from data)::int dia,
                {MES_PASSADO} as mes,
                {ANO_ATUAL - 1} as ano,
                sum(nfocos) total from view_focos_munic_ref
                where
                pais_id0 in (12,28,33,48,53,68,98,80,177,178,219,245,249)
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
                where pais_id0 in (12,28,33,48,53,68,98,80,177,178,219,245,249)
                and extract(year from "data") = {ANO_ATUAL}
                and extract(month from "data") = {MES_PASSADO}
                and data <= '{ONTEM}'
            group by 1,2,3 order by 1
            ) focos 
            right join generate_series(1, {ULTIMO_DIA_MES_PASSADO_ANO_ATUAL}) dias on focos.dia=dias
    """


engine.connect()
df = pd.read_sql(sql, engine).sort_values(['ano'])


# In[415]:


pivot = pd.pivot_table(df, values='qtd', index=['dia'],columns=['ano'], margins=True, margins_name='TOTAL', aggfunc='sum')


# In[416]:


del pivot['TOTAL']
#pivot


# In[417]:


saida = pd.DataFrame()
for ano in pivot.columns[:-1]:
    ano_pos = ano+1
    
    saida[ano] = pivot[ano].apply(lambda x: int(x) if not np.isnan(x) else 0)
    saida[ano_pos] = pivot[ano_pos].apply(lambda x: int(x) if not np.isnan(x) else 0)
    
    saida[ano] = saida[ano].apply(lambda x: format(int(x), '8_d').replace('_', '.') if not np.isnan(x) else 0)
    saida[ano_pos] = saida[ano_pos].apply(lambda x: format(int(x), '8_d').replace('_', '.') if not np.isnan(x) else 0)
    
    if DIA_ATUAL > 1:
        saida[ano_pos][(saida.index.isin(range(DATA_ATUAL.day, ULTIMO_DIA_MES_ATUAL_ANO_ATUAL+1)))] = '-'
    
    if DIA_ATUAL > 1:
        saida = saida.add_prefix(getMonth(MES_ATUAL)+'/')
    else:
        saida = saida.add_prefix(getMonth(MES_PASSADO)+'/')
saida.index.name = 'Dia'


# In[418]:


saida.dtypes


# In[419]:


result = saida.T


# In[420]:


result


# In[421]:


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


# In[422]:


html = result.style.render()
html = '<head><meta charset="UTF-8"></head>' + html + append

with open(os.path.join(path_saida, "grafico_historico_mes_atual_pais_america_do_sul.html"), "w") as saida:
    saida.write(html)


# In[423]:


saida


# In[ ]:




