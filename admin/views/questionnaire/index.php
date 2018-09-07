<?php
$this->title = "调查问卷";
?>
<style>
    .color-block {
        height: 100px;
        color: rgba(255,255,255,0.9);
        text-align: center;
        padding: 15px 0;
        border-radius: 2px;
        margin-bottom: 25px;
    }

    .color-block span {
        display: block;
    }

    .color-block .color {
        font-size: 14px;
        text-transform: uppercase;
    }

    .color-block .code {
        margin: 5px 0;
    }

    .color-block .less {
        font-size: 11px;
    }
</style>

<div class="card">
    <div class="card-header">
        <h2>问卷调查</small></h2>
    </div>

    <div class="card-body card-padding">
        <div class="row">
            <?php foreach($confList as $v): ?>
                <div class="col-sm-3 col-xs-6">
                    <a href="/questionnaire/list?questionnaireConfId=<?= $v['_id'] ?>">
                        <div class="color-block bgm-indigo">
                            <span class="color"><?= $v['title'] ?></span>
                            <span class="code"><?= $v['count'] ?>份</span>
                            <span class="less">发布时间:<?= $v['start_time'] ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>