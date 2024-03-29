#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os
import get_file_directory as get

DATA_ATUAL = dt.datetime.now()
ONTEM = (DATA_ATUAL - dt.timedelta(days=1)).strftime("%d/%m/%Y")


estados = [
    "rondonia",
    "acre",
    "amazonas",
    "roraima",
    "para",
    "amapa",
    "tocantins",
    "maranhao",
    "piaui",
    "ceara",
    "rio_grande_do_norte",
    "paraiba",
    "pernambuco",
    "alagoas",
    "sergipe",
    "bahia",
    "minas_gerais",
    "espirito_santo",
    "rio_de_janeiro",
    "sao_paulo",
    "parana",
    "santa_catarina",
    "rio_grande_do_sul",
    "mato_grosso_do_sul",
    "mato_grosso",
    "goias",
    "distrito_federal",
]

path_saida = "%s/estado/grafico_historico_estado_"%(get.returnPath())


for estado in estados:
    titulo = f"Comparação do total de focos ativos detectados pelo satélite de referência em cada mês, no período de 1998 até {ONTEM}"

    path = path_saida + estado + ".html"

    with open(path) as f:
        conteudo = f.read()

    h1 = f"""<body><h3 style="text-align:center;">{titulo}</h3>"""
    tmp = h1 + conteudo

    saida_nova = path.replace(".html", "_titulo.html")
    print(saida_nova)

    with open(saida_nova, "w") as f:
        f.write(tmp)
