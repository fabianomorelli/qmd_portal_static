import datetime as dt
import os
import get_file_directory as get


def myGetMonth(mes):
    if mes == 1:
        return "Jan"
    if mes == 2:
        return "Fev"
    if mes == 3:
        return "Mar"
    if mes == 4:
        return "Mai"
    if mes == 5:
        return "Abr"
    if mes == 6:
        return "Jun"
    if mes == 7:
        return "Jul"
    if mes == 8:
        return "Ago"
    if mes == 9:
        return "Set"
    if mes == 10:
        return "Out"
    if mes == 11:
        return "Nov"
    if mes == 12:
        return "Dez"


DATA_ATUAL = dt.datetime.now()

ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = ONTEM.day
MES_ONTEM = ONTEM.month
PATH = get.returnPath()

arquivos = [
    "%s/focos_municipio_48h_barra.html"%(PATH),
    "%s/focos_municipio_48h.html"%(PATH),
    "%s/focos_municipio_48h_pizza.html"%(PATH),
    "%s/focos_municipio_ano_atual_barra.html"%(PATH),
    "%s/focos_municipio_ano_atual.html"%(PATH),
    "%s/focos_municipio_ano_atual_pizza.html"%(PATH),
    "%s/focos_municipio_mes_atual_barra.html"%(PATH),
    "%s/focos_municipio_mes_atual.html"%(PATH),
    "%s/focos_municipio_mes_atual_pizza.html"%(PATH),
]

for arquivo in arquivos:
    if not os.path.exists(arquivo):
        print(f"nao existe arquivo {arquivo}. Ficou sem titulo!!!!!")
        continue

    if "48h" in arquivo:
        titulo = f"Quantidade de focos por municípios do Brasil nas últimas 48h"

    if "mes_atual" in arquivo:
        titulo = f"Quantidade de focos por municípios do Brasil no mês de 01/{myGetMonth(MES_ONTEM)} até {str(DIA_ONTEM).zfill(2)}/{myGetMonth(MES_ONTEM)}"

    if "ano_atual" in arquivo:
        titulo = f"Quantidade de focos por municípios do Brasil no ano atual de 01/Jan até {str(DIA_ONTEM).zfill(2)}/{myGetMonth(MES_ONTEM)}"

    with open(arquivo) as f:
        conteudo = f.read()

    h1 = f"""<body><h3 style="text-align:center;">{titulo}</h3>"""

    conteudo_new = conteudo.replace("<body>", h1)

    saida_nova = arquivo.replace(".html", "_titulo.html")
    with open(saida_nova, "w") as f:
        f.write(conteudo_new)

    print(f"sem titulo {arquivo}")
    print(f"com titulo {saida_nova}")
