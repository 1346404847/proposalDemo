<?php 
use yii\helpers\Url;
$this->title = "游戏列表";
?>
<div class="block-header">
    <h2></h2>
</div>
<div class="card">
    <div class="listview lv-bordered lv-lg">
        <div class="lv-header-alt">
            <h2 class="lvh-label hidden-xs">选择游戏</h2>
        </div>
        <div class="lv-body">
        <?php foreach($gameArr as $key =>$value) {?>
        <a href='<?php echo Url::toRoute(['game/choose-game','game_id'=>$value['id'],'game_prefix'=>$value['prefix']]);?>'>
            <div class="lv-item media">
                <div class="pull-left">
                    <i class="md md-layers"></i>
                </div>
                <div class="media-body">
                    <div class="lv-title"><?php echo $value['name'].'--'.$value['prefix'];?></div>
                </div>
            </div>
        </a>
        <?php }?>
        </div>
    </div>
</div>