var ias = jQuery.ias({
    container: '.box-content',
    item: '.user-item',
    pagination: '.pagination',
    next: '#next_link',
    triggerPageThreshold: 1
});

ias.extension(new IASTriggerExtension({
    text: 'Ver Mas',
    offset: 3,
}));

ias.extension(new IASSpinnerExtension({
    src: URL + '/../assets/images/ajax-loader.gif'
}));

ias.extension(new IASNoneLeftExtension({
    text: 'No hay mas personas'
}));

ias.on('ready', function(event){
    followButtons();
});

ias.on('rendered', function(event) {
    followButtons();
});

function followButtons() {
    $('#btn-follow').unbind('click').click(function () {
        $.ajax({
            url: URL+'/follow',
            type: 'POST',
            data: {followed: $(this).attr('data-followed')},
            success: function (response) {
                console.log(response);
            }
        });
    });

    $('#btn-unfollow').unbind('click').click(function () {
        $.ajax({
            url: URL+'/unfollow',
            type: 'POST',
            data: {followed: $(this).attr('data-followed')},
            success: function (response) {
                console.log(response);
            }
        });
    });
}