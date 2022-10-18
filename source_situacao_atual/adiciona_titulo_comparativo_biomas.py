import datetime as dt
import os
import get_file_directory as get

DATA_ATUAL = dt.datetime.now()

ONTEM = DATA_ATUAL - dt.timedelta(days=1)
ANO_ATUAL = DATA_ATUAL.year
MES_ATUAL = DATA_ATUAL.month
DIA_ATUAL = DATA_ATUAL.day
DIA_ONTEM = ONTEM.day
MES_ONTEM = ONTEM.month
PATH = get.returnPath()


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


arquivos = [
    "%s/focos_bioma_anual_7anos.html"%(PATH),
    "%s/focos_bioma_anual_estendida.html"%(PATH),
]

for arquivo in arquivos:
    if not os.path.exists(arquivo):
        print(f"nao existe arquivo {arquivo}. Ficou sem titulo!!!!!")
        continue

    MES_NAME = myGetMonth(MES_ONTEM)
    titulo = f"Tabela anual comparativa de biomas do Brasil - últimos anos no intervalo de 01/Jan até {DIA_ONTEM}/{MES_NAME}"

    with open(arquivo) as f:
        conteudo = f.read()

    h1 = f"""<body><h3 style="text-align:center;">{titulo}</h3>"""

    conteudo_new = conteudo.replace("<body>", h1)

    saida_nova = arquivo.replace(".html", "_titulo.html")
    with open(saida_nova, "w") as f:
        f.write(conteudo_new)

    print(f"sem titulo {arquivo}")
    print(f"com titulo {saida_nova}")
