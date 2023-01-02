#!/usr/bin/env bash

ENV_DIR=$1
SCRIPTS_DIR=$2

source ${ENV_DIR}/bin/activate

#bash para executar os scrips que geram os arquivos estaticos das paginas de estatisticas_paises e estados
echo "Execucao: $(date)"
echo "Diretório de Scripts"
cd ${SCRIPTS_DIR}

echo 'Historico Pais'
python focos_paises_anual_7anos.py
python focos_paises_anual_estendida.py

echo 'Historico Estado'
python focos_estados_anual_7anos.py
python focos_estados_anual_estendida.py

echo 'Historico Regiao Especial'
python focos_regiao_anual_7anos.py
python focos_regiao_anual_estendida.py

echo 'Historico Regioes BR'
python focos_regiao_brasil_anual_7anos.py
python focos_regiao_brasil_anual_estendida.py

echo 'Historico Biomas'
python focos_bioma_anual_7anos.py
python focos_bioma_anual_estendida.py

echo 'Rodando 3 Script para Pais'
python focos_paises_ano_atual.py
python focos_paises_mes_atual.py
python focos_paises_48h.py

echo 'Rodando 3 Script para Estado'
python focos_estado_ano_atual.py
python focos_estado_mes_atual.py
python focos_estado_48h.py

echo 'Rodando 3 Script para Municipio'
python focos_municipio_ano_atual.py
python focos_municipio_mes_atual.py
python focos_municipio_48h.py

echo 'Rodando 3 Script para Bioma'
python focos_bioma_ano_atual.py
python focos_bioma_mes_atual.py
python focos_bioma_48h.py

echo 'Arrumando Encode'
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
