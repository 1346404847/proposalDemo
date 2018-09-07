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
                titleId            : titleId,
                otherChoose        : otherChoose
            }
        },
        rowCount  : [10],
        url       : "/other/other-list",
        formatters: {
            "content": function (column, row) {
                return '<span>'+row.choose+'</span>';
            }
        },
        templates : {
            search: ''
        }
    }).on("loaded.rs.jquery.bootgrid", function () {

    });
});
