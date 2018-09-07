$(document).ready(function() {
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
    var grid = $("#data-table-command").bootgrid({
        css: {
            icon: "md icon",
            iconColumns: "md-view-module",
            iconDown: "md-expand-more",
            iconRefresh: "md-refresh",
            iconUp: "md-expand-less"
        },
        labels: {
            noResults: "暂时没数据"
        },
        ajax: true,
        post: function() {
            return {
                _csrf: $("meta[name='csrf-token']").attr("content"),
                status: $("#status").val(),
                pid: $("#pid").val(),
                version: $("#version").val(),
                platform: $("#platform").val(),
                area_service: $("#area_service").val(),
                vip: $("#vip").val(),
                create_time_start: $("#create_time_start").val(),
                create_time_end: $("#create_time_end").val(),
                op_datetime_start: $("#op_datetime_start").val(),
                op_datetime_end: $("#op_datetime_end").val(),
                op_user: $("#op_user").val(),
                cat_first: $("#cat_first").val(),
                cat_second: $("#cat_second").val(),
                uid: $("#uid").val(),
            }
        },
        rowCount:[50],
        url: "/question/list",
        formatters: {
            "commands": function(column, row) {
                return '<button type=\"button\" class=\"btn btn-icon command-edit\" data-row-id=\"' + row._id + '\" ><span class=\"md md-edit\"></span></button> '
            },
            "cat" : function(colum, row){
                if(row.cat_first == 0 || row.cat_second == 0){
                    return '默认';
                }
                for(var i in category){
                    if(category[i]['cat_id'] == row.cat_first){
                        var firstName = category[i]['cat_name'];
                    }else if(category[i]['cat_id'] == row.cat_second){
                        var secondName = category[i]['cat_name'];
                    }
                }
                return firstName+'-'+secondName;
            }
        },
        templates: {
            search: ''
        }
    }).on("loaded.rs.jquery.bootgrid", function(){
        grid.find(".command-edit").on("click", function(e)
        {
            var id = $(this).data("row-id");
            //window.location.href = '/question/view?id=' + id;
            window.open('/question/view?id=' + id);
        })
    });

    $("#search_list").click(function(){
        $(".btn-default").click();
    });

    $("#repeat_submit").click(function(){
        //判断分类是否选择
        var catFirstVal = parseInt($("#cat_first").val());
        var catSecondVal = parseInt($("#cat_second").val());
        if(!(catFirstVal  && catSecondVal)){
            notify("必须选择分类", 'danger');
            return;
        }

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/question/view",
            data: {
                '_csrf': $("meta[name='csrf-token']").attr("content"),
                'op_content': $("#op_content").val(),
                'id': $("#m_id").val(),
                'cat_first':catFirstVal,
                'cat_second':catSecondVal
            },
            success: function(text){
                if (text.code == '100') {
                    notify(text.message, 'inverse');
                    return false;
                }
                notify(text.message, 'inverse');
                window.location.href='/question/index';
                return false;
            }
        });
    });

    $("#export_button").click(function(){
        var query_params = "?status=" + $("#status").val() + "&pid=" + $("#pid").val() + "&version=" + $("#version").val()
            + "&platform=" + $("#platform").val() + "&area_service=" + $("#area_service").val()
            + "&vip=" + $("#vip").val() + "&create_time_start=" + $("#create_time_start").val()
            + "&create_time_end=" + $("#create_time_end").val() + "&op_datetime_start=" + $("#op_datetime_start").val()
            + "&op_datetime_end=" + $("#op_datetime_end").val() + "&op_user=" + $("#op_user").val()
            + "&cat_first=" + $("#cat_first").val() + "&cat_second=" + $("#cat_second").val() + "&uid=" + $("#uid").val();
        window.location.href='/question/export' + query_params
    })

    //分类联动
    $("#cat_first").change(function(){
        var sel = $(this).val();
        var html = '<option value="0">请选择</option>';
        if(sel != 0){
            for(var key in category){
                if(category[key].pid == sel){
                    html += '<option value="'+category[key]['cat_id']+'">'+category[key]['cat_name']+'</option>';
                }
            }
        }
        $("#cat_second").html(html);

    })
    
    //根据平台获取区组
    $('#platform').change(function(){
    	var platform = $('#platform option:selected').attr('platform_id');
    	var group = "<option value=''>请选择区服</option>";
    	
    	if(!platform) {
            $('#area_service').html(group);
            return false;
        }

        $.ajax({  
            url : '/question/group',
            async : false,
            type : "POST",
            dataType : "json",
            data: {platform_id:platform,  '_csrf':$("meta[name='csrf-token']").attr("content")}, 
            success : function(data) {
                if(data.flag == 0) {
                    $('#area_service').html(group);
                    return false;
                }
                for(var i=0; i<data.data.length; i++) {
                   
                        group += "<option value='"+data.data[i]['prefix']+"'>"+data.data[i]['prefix']+"--"+data.data[i]['name']+"</option>";
                    //}
                }
                
                $('#area_service').html(group);
            }  
        });
    })
});
