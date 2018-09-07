$(document).ready(function () {
    //初始化列表
    var grid = $("#data-table-command").bootgrid({
        css       : {
            icon       : "md icon",
            iconColumns: "md-view-module",
            iconDown   : "md-expand-more",
            iconRefresh: "md-refresh",
            iconUp     : "md-expand-less"
        },
        labels    : {
            noResults: "暂时没数据"
        },
        ajax      : true,
        post      : function () {
            return {
                _csrf              : $("head meta[name=csrf-token]").attr('content'),
                questionnaireConfId: questionnaireConfId,
                vip                : vip,
                titleId            : titleId
            }
        },
        rowCount  : [10],
        url       : "/free-imagination/list",
        formatters: {
            "content": function (column, row) {
                var question = row.questions;
                if (question) {
                    for (var i = 0; i < question.length; i++) {
                        if (question[i]['id'] == titleId) {
                            
                            if (question[i]['val'] == undefined) {
                                return '';
                            }
                            return '<span>' + question[i]['val'] + '</span>';
                        }
                    }
                }
            },
            "commands": function(column, row) {
                return '<button type="button" class="btn btn-icon command-edit" data-row-id="' + row._id.$oid + '" ><span class=\"md md-edit\"></span></button> '
            }
        },
        templates : {
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
    
    
    
    //导出简答excel
    $("#export_answer").click(function(){
        var query_params = $("form:eq(0)").serialize();
        window.location.href='/free-imagination/export-answer?' + query_params+'&questionnaireConfId='+questionnaireConfId+"&vip="+vip+'&titleId='+titleId
    });
});
