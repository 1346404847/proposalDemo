<?php foreach($questions as $v):?>
    <div class="card">
        <div class="row">
            <div class="listview col-sm-8">
                <div class="lv-header">
                    <?php if(isset($v['type']) && $v['type'] == '5'):?>
                        <span onclick='getFiveAnswer("<?=$v['questions'][0]['id'];?>","<?=$v['questions'][0]['questionnaireConfId'];?>")'><?= $v['content'];?></span>
                    <?php else:?>
                        <span><?= $v['content']; ?></span>
                    <?php endif;?>
                </div>
                <div class="lv-body">
                    <div class="p-15">
                        <?php if(isset($v['type']) && in_array($v['type'],array('3','4'))):?>
                            <?php foreach($v['questions'] as $k1=>$v1):?>
                                <div class="lv-item">
                                    <div class="media">
                                        <div class="">
                                            <?php if(isset($v1['questionnaireConfId']) && isset($v1['checked']) && isset($v1['id'])):?>
                                                <span onclick='getOtherAnswer("<?=$v1['questionnaireConfId'];?>","<?=$v1['checked'];?>","<?=$v1['id'];?>")' style="color: red;"><?= $v1['content'];?></span>
                                            <?php else:?>
                                                <?= $v1['content'];?>
                                            <?php endif;?>
                                        </div>
                                        <div class="media-body">
                                            <div class="progress">
                                                <div class="progress-bar <?= $class[$k1 % count($class)] ?>" style="width: <?= $v1['percentage'] ?>%">
                                                </div>
                                            </div>
                                            <div class="pull-right"><?= $v1['percentage'] ?>％</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php if(isset($v['type']) && in_array($v['type'],['3','4'])):?>
                <div class="col-sm-4">
                    <table class="table">
                        <tr>
                            <th>选项</th>
                            <th>数量</th>
                        </tr>
                        <?php foreach($v['questions'] as $v2):?>
                            <tr>
                                <td><?= $v2['content'] ?></td>
                                <td><?= $v2['count'] ?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            <?php endif;?>
        </div>
    </div>
<?php endforeach;?>