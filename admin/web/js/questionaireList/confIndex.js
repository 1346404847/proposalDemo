/**
 */
$(document).ready(function() {
    //初始化列表
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
        post: function () {
            return {
                _csrf: $("head meta[name=csrf-token]").attr('content')
            }
        },
        rowCount: [50],
        url: "/questionaire-list/conf-list",
        formatters: {
            "mongodId":function (column, row) {
                return '<span>'+row._id.$oid+'</span>';
            }
            // "commands": function (column, row) {
            //
            //     return '<a href="/questionnaire/conf-edit?_id='+row._id.$oid+
            //         '"><button type=\"button\" class=\"btn btn-icon command-edit\"><span class=\"md md-edit\"></span></button></a>';
            // }
        },
        templates: {
            search: ''
        }
    }).on("loaded.rs.jquery.bootgrid", function () {

    });
})