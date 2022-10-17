#!/usr/bin/env python
# coding: utf-8


import datetime as dt
import os
import get_file_directory as get

paises = [
    "america_do_sul",
    "argentina",
    "bolivia",
    "guyana",
    "brasil",
    "chile",
    "colombia",
    "venezuela",
    "suriname",
    "equador",
    "paraguai",
    "peru",
    "uruguai",
    "ilhas_malvinas",
    "guyana_francesa",
]

path_saida = "%s/grafico_historico_mes_atual_pais_"%(get.returnPath())

for pais in paises:
    titulo = u"Comparação do total de focos ativos detectados dia a dia pelo satélite de referência para o mês atual."
    path = path_saida + pais + ".html"
    with open(path) as f:
        conteudo = f.read()

    h1 = u"""<body><h3 style="text-align:center;">{titulo}</h3>"""
    tmp = h1 + conteudo

    saida_nova = path.replace(".html", "_titulo.html")
    print(saida_nova)

    with open(saida_nova, "w") as f:
        f.write(tmp)
