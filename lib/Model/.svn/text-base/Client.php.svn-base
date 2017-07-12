<?php
namespace Lib\Model;

class Client
{
        /**
     * 获取客户端在线状态
     * @param  [string] $eid     [企业id]
     * @param  [string] $sguid   [客户端id]
     * @param  [string] $systype [客户端系统类型，可为空，为空时则安装sguid查询]
     * @return [int]          [0：离线；1：在线]
     */
    public static function getClientOnlineState($eid, $sguid, $systype)
    {
        $lastTime = RedisDataManager::getEpLastlogintime($eid, $systype, $sguid);

        return (!empty($lastTime) && $lastTime >= time() ? 1 : 0);
    }
}