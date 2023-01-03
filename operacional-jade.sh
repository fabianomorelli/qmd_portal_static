#!/usr/bin/env bash

cd /srv/www/queimadas.dgi.inpe.br/apps/portal-static-goes2
#(cd source_estatisticas/; ./busca_static.sh ../env/ .)
(cd source_situacao-atual/; ./busca_static.sh ../env/ .)
