<?php
\admin\assets\question\QuestionAsset::register($this);
?>
<div class="block-header">
    <h2>数据列表</h2>
    <ul class="actions"></ul>
</div>
<div class="card">
    <div class="card-header">
        <h2>反馈列表 <small>点击操作进行接取工单.</small></h2>
    </div>
    <div class="card-body card-padding">
        <form role="form" class="row" action="" method="get">
            <div class="col-sm-2">
                <div class="fg-line select">
                    <select class="form-control  input-sm" name="status" id="status">
                        <option value="-1">处理状态</option>
                        <option value="1">待处理</option>
                        <option value="2">已处理</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="exampleInputEmail2" class="sr-only">pid</label>
                    <input type="text" placeholder="pid" class="form-control input-sm" name="pid" id="pid">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="exampleInputPassword2" class="sr-only">版本</label>
                    <input type="text" placeholder="版本" id="version" class="form-control input-sm" name="version">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group fg-line">
                     <select name="platform" id="platform" class="form-control  input-sm">
                        <option value="0">请选择平台</option>
                        <?php foreach ($platform as $key => $value): ?>
                            <option value="<?= $value['prefix']?>" platform_id="<?= $value['id'];?>"><?= $value['prefix']?></option>
                        <?php endforeach;?>
                    </select>
                    
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <select class="form-control input-sm" name="area_service" id="area_service">
                        <option value="0">请选择区服</option>
                    </select>
                </div>
            </div>
           <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="area_service" class="sr-only">uid</label>
                    <input type="text" placeholder="uid" class="form-control input-sm" name="uid" id="uid">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group fg-line">
                    <label for="vip" class="sr-only">VIP</label>
                    <select class="form-control  input-sm" name="vip" id="vip">
                        <option value="-1">VIP</option>
                        <?php foreach (range(15, 1) as $value): ?>
                            <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group fg-line">
                    <label for="op_user" class="sr-only">操作人</label>
                    <input type="text" placeholder="操作人" class="form-control input-sm" name="op_user" id="op_user">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="md md-event"></i></span>
                    <div class="dtp-container dropdown fg-line">
                        <input type='text' class="form-control date-time-picker" data-date-format='YYYY-MM-DD H:m:s' data-toggle="dropdown" placeholder="提问开始时间" name="create_time_start" id="create_time_start">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="md md-event"></i></span>
                    <div class="dtp-container dropdown fg-line">
                        <input type='text' class="form-control date-time-picker" data-date-format='YYYY-MM-DD H:m:s' data-toggle="dropdown" placeholder="提问结束时间" name="create_time_end" id="create_time_end">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="md md-event"></i></span>
                    <div class="dtp-container dropdown fg-line">
                        <input type='text' class="form-control date-time-picker" data-date-format='YYYY-MM-DD H:m:s' data-toggle="dropdown" placeholder="处理开始时间" name="op_datetime_start" id="op_datetime_start">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="md md-event"></i></span>
                    <div class="dtp-container dropdown fg-line">
                        <input type='text' class="form-control date-time-picker" data-date-format='YYYY-MM-DD H:m:s' data-toggle="dropdown" placeholder="处理结束时间" name="op_datetime_end" id="op_datetime_end">
                    </div>
                </div>
            </div>

            <div class="">
                <label class="col-sm-1">一级分类</label>
                <div class="col-sm-1">
                    <select id="cat_first" class="form-control">
                        <option value="0">请选择</option>
                        <?php foreach ($catFirstList as $v): ?>
                            <option value="<?= $v['cat_id']?>"><?= $v['cat_name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <label class="col-sm-1">二级分类</label>
                <div class="col-sm-1">
                    <select id="cat_second" class="form-control">
                        <option value="0">请选择</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" type="button" id="search_list">搜索</button>
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" id="export_button" type="button">导出</button>
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" type="reset">重置</button>
            </div>
        </form>
    </div>
    <table id="data-table-command" class="table table-striped table-vmiddle">
        <thead>
            <tr>
                <th data-column-id="pid">pid</th>
                <th data-column-id="version" >版本</th>
                <th data-column-id="game">游戏</th>
                <th data-column-id="platform">平台</th>
                <th data-column-id="area_service">区服</th>
                <th data-column-id="vip" data-type="numeric">VIP</th>
                <th data-column-id="role">角色</th>
                <th data-column-id="title">标题</th>
                <th data-column-id="content">内容</th>
                <th data-column-id="status">状态</th>
                <th data-column-id="create_time">提问时间</th>
                <th data-column-id="op_datetime">处理时间</th>
                <th data-column-id="op_user">处理人</th>
                <th data-column-id="uid">uid</th>
                <th data-column-id="cat" data-formatter="cat" data-sortable="false">分类</th>
                <th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr></tr>
        </tbody>
    </table>
</div>
<script>
    var category = <?= $category; ?>
</script>
