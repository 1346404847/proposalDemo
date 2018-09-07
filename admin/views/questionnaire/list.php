<?php
use admin\assets\questionnaire\ListAsset;
ListAsset::register($this);
$this->title = "问卷列表";
?>
<div class="block-header">
    <h2>
        <?= $questionnaireConf->title ?><small><?= $questionnaireConf->subtitle?></small>
    </h2>
    <div><a href="/questionnaire/questionnaire-view?questionnaireConfId=<?= $questionnaireConfId ?>" target="_blank"><button class="btn btn-info waves-effect waves-button waves-float">查看问卷详情</button></a></div>
</div>
    <div class="form-group">
        <span class="fa-font">单选及多选数据分布</>
        <select style="width: 100px" id="statisticalVip">
            <option value="-1">全部</option>
            <?php for($i=0;$i<=15;$i++):?>
                <option value="<?= $i ?>">vip<?= $i ?></option>
            <?php endfor;?>
        </select>
        <button class="btn btn-primary pull-right" id="switchQuestionsList">
            折叠全部
        </button>
    </div>
<div id="questionsList"></div>
<div class="card">
    <div class="card-header">
        <h2>问卷列表</h2>
    </div>
    <div class="card-body card-padding">
        <form role="form" class="row" id="search">
            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="exampleInputEmail2" class="sr-only">openid</label>
                    <input type="text" placeholder="openid" class="form-control input-sm" name="openid" id="openid">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="exampleInputEmail2" class="sr-only">uid</label>
                    <input type="text" placeholder="uid" class="form-control input-sm" name="uid" id="uid">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="exampleInputEmail2" class="sr-only">角色名</label>
                    <input type="text" placeholder="角色名" class="form-control input-sm" name="role" id="role">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="exampleInputPassword2" class="sr-only">答题版本</label>
                    <input type="text" placeholder="答题版本" id="version" class="form-control input-sm" name="version">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="platform" class="sr-only">平台</label>
                    <input type="text" placeholder="平台" class="form-control input-sm" name="platform" id="platform">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="area_service" class="sr-only">区服</label>
                    <input type="text" placeholder="区服" class="form-control input-sm" name="area_service" id="area_service">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group fg-line">
                    <label for="vip" class="sr-only">VIP</label>
                    <select class="form-control" name="vip" id="vip">
                        <option value="-1">VIP</option>
                        <?php foreach (range(15, 0) as $value): ?>
                            <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="md md-event"></i></span>
                    <div class="dtp-container dropdown fg-line">
                        <input type='text' class="form-control date-time-picker" data-date-format='YYYY-MM-DD H:m:s' data-toggle="dropdown" placeholder="创建开始时间" name="create_time_start" id="create_time_start">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="md md-event"></i></span>
                    <div class="dtp-container dropdown fg-line">
                        <input type='text' class="form-control date-time-picker" data-date-format='YYYY-MM-DD H:m:s' data-toggle="dropdown" placeholder="创建结束时间" name="create_time_end" id="create_time_end">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" type="button" id="search_list">搜索</button>
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" id="export_button" type="button">导出</button>
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" id="export_answer" type="button">导出简答题</button>
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" type="reset">重置</button>
            </div>
            <input type="hidden" name="questionnaireConfId" id="questionnaireConfId" value="<?= $questionnaireConfId ?>">
        </form>
    </div>
    <table id="data-table-command" class="table table-striped table-vmiddle">
        <thead>
            <tr>
                <th data-column-id="openid">openid</th>
                <th data-column-id="uid">uid</th>
                <th data-column-id="vip" data-type="numeric">VIP</th>
                <th data-column-id="role">角色名</th>
                <th data-column-id="platform">平台</th>
                <th data-column-id="area_service">区服</th>
                <th data-column-id="level">等级</th>
                <th data-column-id="version" >答题版本</th>
                <th data-column-id="create_time">创建时间</th>
                <th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr></tr>
        </tbody>
    </table>
</div>