$(document).ready(function(){
    $('.js-nickname-input').blur(function () {
        var nickname = this.value;

        $.ajax({
            url: URL + '/nickname-check',
            data: {nickname: nickname},
            type: 'POST',
            success: function (response) {
                console.log(response);
                if(response === 'Used') {
                    $(".js-nickname-input").css('border', '1px solid red');
                } else {
                    $(".js-nickname-input").css('border', '1px solid green')
                }
            }
        })
    })
});