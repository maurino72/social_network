$(document).ready(function() {
    var ias = jQuery.ias({
        container: '.profile-box #user-publications',
        item: '.publication-item',
        pagination: '.profile-box .pagination',
        next: '.profile-box #next_link',
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
        $('[data-toggle="tooltip"]').tooltip();

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

        $('.btn-like').unbind('click').click(function() {
            $(this).addClass('hidden');
            $(this).parent().find('.btn-unlike').removeClass('hidden');

            $.ajax({
                url: URL+'/like/'+$(this).attr('data-id'),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                }
            });
        });

        $('.btn-unlike').unbind('click').click(function() {
            $(this).addClass('hidden');
            $(this).parent().find('.btn-like').removeClass('hidden');

            $.ajax({
                url: URL+'/unlike/'+$(this).attr('data-id'),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                }
            });
        });
    }
});
