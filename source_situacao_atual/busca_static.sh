#!/usr/bin/env bash

ENV_DIR=$1
SCRIPTS_DIR=$2

source ${ENV_DIR}/bin/activate

#bash para executar os scrips que geram os arquivos estaticos das paginas de estatisticas_paises e estados
echo "Execucao: $(date)"
echo "vai para o diretorio dos scripts"
cd ${SCRIPTS_DIR}

echo 'historico pais'
python focos_paises_anual_7anos.py
python focos_paises_anual_estendida.py

echo 'historico estado'
python focos_estados_anual_7anos.py
python focos_estados_anual_estendida.py

echo 'historico regiao especial'
python focos_regiao_anual_7anos.py
python focos_regiao_anual_estendida.py

echo 'historico regios BR'
python focos_regiao_brasil_anual_7anos.py
python focos_regiao_brasil_anual_estendida.py

echo 'historico biomas'
python focos_bioma_anual_7anos.py
python focos_bioma_anual_estendida.py

echo 'rodando 3 script para pais'
python focos_paises_ano_atual.py
python focos_paises_mes_atual.py
python focos_paises_48h.py

echo 'rodando 3 script para estado'
python focos_estado_ano_atual.py
python focos_estado_mes_atual.py
python focos_estado_48h.py

echo 'rodando 3 script para municipio'
python focos_municipio_ano_atual.py
python focos_municipio_mes_atual.py
python focos_municipio_48h.py

echo 'rodando 3 script para bioma'
python focos_bioma_ano_atual.py
python focos_bioma_mes_atual.py
python focos_bioma_48h.py

echo 'arrumando encode'
python arruma_encode.py

echo 'Adiciona titulo nos arquivos da versão light'
python adiciona_titulo_comparativo_paises.py
python adiciona_titulo_comparativo_estados.py
python adiciona_titulo_comparativo_regioes_brasil.py
python adiciona_titulo_comparativo_regioes_especiais.py
python adiciona_titulo_paises.py
python adiciona_titulo_estados.py
python adiciona_titulo_municipios.py
python adiciona_titulo_biomas.py

# echo 'Copia arquivos para path do situacao-atual(prod)'
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/situacao_atual/static/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/

echo "FIM: $(date)."