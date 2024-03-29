#!/usr/bin/env bash

ENV_DIR=$1
SCRIPTS_DIR=$2

source ${ENV_DIR}/bin/activate

#bash para executar os scrips que geram os arquivos estaticos das paginas de estatisticas_paises e estados
echo "Execucao: $(date)"
echo "vai para o diretorio dos scripts"
cd ${SCRIPTS_DIR}

#gera tabelas histórico
echo "Gera tabelas historico"
python grafico_historico_america_do_sul.py
python grafico_historico_biomas.py
python grafico_historico_estados.py
python grafico_historico_pais.py
python grafico_historico_regioes.py


#gera tabelas diárias
echo "Gera tabelas histórico mês atual por dia"
python grafico_comparativo_historico_mes_america_do_sul.py
python grafico_comparativo_historico_mes_pais.py
python grafico_comparativo_historico_mes_biomas.py
python grafico_comparativo_historico_mes_estados.py
python grafico_comparativo_historico_mes_regioes.py


#gera tabelas série histórica
echo "Gera series historicas"
python grafico_serie_historica_america_do_sul.py
python grafico_serie_historica_biomas_ano.py
python grafico_serie_historica_biomas_mes.py
python grafico_serie_historica_biomas.py
python grafico_serie_historica_estados.py
python grafico_serie_historica_pais.py
python grafico_serie_historica_regioes.py

#gera gráficos sazonal primeiro segundo semestre
echo "Gera grafico sazonal"
python grafico_comparativo_sazonal_america_do_sul.py
python grafico_comparativo_sazonal_biomas.py
python grafico_comparativo_sazonal_estados.py
python grafico_comparativo_sazonal_pais.py
python grafico_comparativo_sazonal_regioes.py
python grafico_comparativo_sazonal_pais_por_data.py


#copia pra pasta da versao completa
echo "Inicia copia pra pasta completa"
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_paises/static/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/bioma /srv/www/queimadas.dgi.inpe.br/apps/portal-static/
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/estado /srv/www/queimadas.dgi.inpe.br/apps/portal-static/
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/regiao /srv/www/queimadas.dgi.inpe.br/apps/portal-static/

#copia pra pasta da versao light
echo "Inicia copia pra pasta da versao light"
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_paises/static/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/estatisticas_paises_light
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/bioma/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/estatisticas_estados_light
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/estado/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/estatisticas_estados_light
#rsync -del -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/regiao/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/estatisticas_estados_light

#titulos versao light
echo 'Adiciona titulo nos arquivos da versão light'
python adiciona_titulo_grafico_historico_regioes.py
python adiciona_titulo_grafico_historico_pais.py
python adiciona_titulo_grafico_historico_biomas.py
python adiciona_titulo_grafico_comparativo_historico_mes_pais.py

echo 'Copia csvs para download'
#rsync -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_paises/static/csv_estatisticas/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/csv_estatisticas/
#rsync -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/bioma/csv_estatisticas/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/csv_estatisticas/
#rsync -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/estado/csv_estatisticas/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/csv_estatisticas/
#rsync -ravz /mnt/vol_queimadas_2/produtos/focos/estatistica_estados/static/regiao/csv_estatisticas/ /srv/www/queimadas.dgi.inpe.br/apps/portal-static/csv_estatisticas/

echo "terminou"
