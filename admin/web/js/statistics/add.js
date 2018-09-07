$(document).ready(function(){
    $("form").submit(function () {
        var formdata = new FormData(this);
        function notify(message, type){
            $.growl({
                message: message
            },{
                type: type,
                allow_dismiss: false,
                label: 'Cancel',
                className: 'btn-xs btn-inverse',
                placement: {
                    from: 'top',
                    align: 'right'
                },
                delay: 2500,
                animate: {
                    enter: 'animated bounceIn',
                    exit: 'animated bounceOut'
                },
                offset: {
                    x: 20,
                    y: 85
                }
            });
        };

        $.ajax({
            type: 'POST',
            url: '/statistics/count-key',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if(data.flag == 1){
                    notify(data.info, 'success');
                    window.location.href = '/statistics/count';
                }else{
                    notify(data.info, 'danger');
                    $("button").removeAttr("disabled", '');
                }
            },
            error:function(data){
                notify('失败', 'danger');
                $("button").removeAttr("disabled", '');
            },
        });
        
        $("button").attr("disabled", '');
        return false;
    })
})