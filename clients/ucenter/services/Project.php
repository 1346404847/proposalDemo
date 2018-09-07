<?php

namespace clients\ucenter\services;
use clients\ucenter\lib\Base;
use clients\ucenter\lib\Exception;

class Project extends Base
{
    const URL_PATH = 'project/rpc';
    
    public static function getFunctions()
    {
        $project = new self();
        return $project->getClient()->postByCurl(__FUNCTION__, [], self::URL_PATH);
    }
}
