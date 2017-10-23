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