<?php
use admin\assets\questionairelist\ConfListAsset;

ConfListAsset::register($this);

$this->title = '问卷问卷配置列表';

?>

<div class="block-header">
    <h2>问卷配置列表</h2>

    <ul class="actions">
        <li class="dropdown">
            <a class="icon-pop" href="alerts.html" data-toggle="dropdown">
                <i class="md md-more-vert"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="/uploads/upload-execl">新增问卷配置</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<div class="card">
    <div class="card-header">
        <h2>问卷配置列表 <small></small></h2>
    </div>
    <table id="data-table-command" class="table table-striped table-vmiddle">
        <thead>
        <tr>
            <th data-column-id="title">问卷名称</th>
            <th data-column-id="subtitle">问卷副标题</th>
            <th data-column-id="mongodId" data-formatter="mongodId">问卷key</th>
            <th data-column-id="start_time">开始时间</th>
            <th data-column-id="finish_time">结束时间</th>
            <!--<th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>-->
        </tr>
        </thead>
        <tbody>
        <tr>
        </tr>
        </tbody>
    </table>
</div>
