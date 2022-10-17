
$( document ).ready(function() {
    $('.page').prepend(
        `
        <div class="page_header">
            <p class="c12"><span class="c37 c91">OPERA&Ccedil;&Atilde;O AMAZ&Ocirc;NIA LEGAL - BOLETIM DI&Aacute;RIO - <span id="data_header"></span></span></p>
            <hr>
        </div>
        `
    );

    $('#data_main').html('27 de Agosto de 2019');
    $('#data_header').html('27 DE AGOSTO DE 2019');
    $('#data_hoje').html('27/08/2019');
    $('#data_ontem').html('26/08/2019');
    $('#dd_mm_ontem').html('26/Ago');
    $('#pos_1d').html('27/08/2019');
    $('#pos_2d').html('28/08/2019');
    $('#pos_3d').html('29/08/2019');
    $('#num_focos_aml').html('58614');
});