$(document).ready(function() {
    var ias = jQuery.ias({
        container: '#timeline .box-content',
        item: '.publication-item',
        pagination: '#timeline .pagination',
        next: '#timeline #next_link',
        triggerPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'Ver Mas',
        offset: 3,
    }));

    ias.extension(new IASSpinnerExtension({
        src: URL + '/../assets/images/ajax-loader.gif'
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'No hay mas publicaciones'
    }));

    ias.on('ready', function(event){
        buttons();
    });

    ias.on('rendered', function(event) {
        buttons();
    });

    function buttons() {
        $('.btn-img').unbind('click').click(function(){
            $(this).parent().find('.publication-image').fadeToggle();
        });

        $('.btn-remove').unbind('click').click(function(){
            $(this).parent().parent().addClass('hidden');
            $.ajax({
                url: URL+'/publication/delete/' + $(this).attr('data-id'),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                }
            });
        });
    }
});
