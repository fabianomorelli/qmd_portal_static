import glob
import get_file_directory as get

arquivos_html = glob.glob(
    "%s/*.html"%(get.returnPath())
)

for arq in arquivos_html:
    with open(arq, "r") as f:
        conteudo = f.read()

    conteudo = conteudo.replace("<head>", '<head><meta charset="UTF-8">')

    if not "<head>" in conteudo:
        conteudo = conteudo.replace(
            "<body>", '<head><meta charset="UTF-8"><head><body>'
        )

    if not "<html>" in conteudo:
        if not "<body>" in conteudo:
            conteudo = "<body>" + conteudo + "</body>"
        conteudo = '<html><head><meta charset="UTF-8"><head>' + conteudo + "</html>"

    with open(arq, "w") as f:
        f.write(conteudo)
print("finalizou o arruma encode")
