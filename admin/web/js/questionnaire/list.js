/**
 */
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

    //初始化表单
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
                openid: $("#openid").val(),
                uid: $("#uid").val(),
                role: $("#role").val(),
                version: $("#version").val(),
                platform: $("#platform").val(),
                area_service: $("#area_service").val(),
                vip: $("#vip").val(),
                create_time_start: $("#create_time_start").val(),
                create_time_end: $("#create_time_end").val(),
                questionnaireConfId: $("#questionnaireConfId").val()
            }
        },
        rowCount:[50],
        url: "/questionnaire/list",
        formatters: {
            "commands": function(column, row) {
                return '<button type="button" class="btn btn-icon command-edit" data-row-id="' + row._id.$oid + '" ><span class=\"md md-edit\"></span></button> '
            }
        },
        templates: {
            search: ''
        },
        sorting:false
    }).on("loaded.rs.jquery.bootgrid", function(){
        grid.find(".command-edit").on("click", function(e)
        {
            var id = $(this).data("row-id");
            window.open('/questionnaire/view?id=' + id);
        })
    });



    //搜索
    $("#search_list").click(function(){
        var create_time_start = $("#create_time_start").val();
        create_time_start = getTimestamp(create_time_start);
        var create_time_end = $("#create_time_end").val();
        create_time_end = getTimestamp(create_time_end);
        if(create_time_start > create_time_end){
            alert('开始时间必须小于结束时间');
            return;
        }

        $(".btn-default").click();
    });

    //导出excel
    $("#export_button").click(function(){
        var query_params = $("form:eq(0)").serialize();
        window.location.href='/questionnaire/export?' + query_params
    });

    //隐藏问题列表
    $("#switchQuestionsList").on('click', function(){
        $("#questionsList").toggle(1000);
    });

    statisticalList();

    $("#statisticalVip").change(function(){
        $("#questionsList").empty();
        statisticalList();
    });

    //导出简答excel
    $("#export_answer").click(function(){
        var query_params = $("form:eq(0)").serialize();
        window.location.href='/questionnaire/export-answer?' + query_params
    });
});


//刷新问题统计
function statisticalList()
{
    $.ajax({
        url:'/questionnaire/statistical-list',
        data:{
            _csrf: $("meta[name='csrf-token']").attr("content"),
            vip : $("#statisticalVip").val(),
            questionnaireConfId: $("#questionnaireConfId").val()
        },
        dataType:"json",
        type:"get",
        success:function(data){
            if(data.flag == 1){
                $("#questionsList").html(data.info);
            }
        }
    })
}

function getTimestamp(date){
    var timestamp = Date.parse(new Date(date));
    timestamp /= 1000;

    return timestamp;
}

function getOtherAnswer(questionnaireConfId,checked,id) {
    
    var vip = $("#statisticalVip option:selected").val();
    if(questionnaireConfId && checked && id){
        window.open('/other/other-list?id=' + id + '&questionnaireConfId=' + questionnaireConfId + '&choose=' + checked+'&vip='+vip);
    }
}

function getFiveAnswer(id,questionnaireConfId) {
    var vip = $("#statisticalVip option:selected").val();
    if(id && questionnaireConfId){
        window.open('/free-imagination/list?id=' + id + '&questionnaireConfId=' + questionnaireConfId+'&vip='+vip);
    }
}