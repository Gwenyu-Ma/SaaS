<?php
namespace Lib\Model;

use \Lib\Store\MongoClient;

/**
 *
 */
class PlatformInitManager
{
    public static function initProductPolicyRelation()
    {
        MongoClient::getInstance()->selectDB(MONGO_MANAGE_DB)->dropCollection('productpolicy');

        //::selectDB(MONGO_MANAGE_DB)->dropCollection('2p');
        $pp = MongoClient::getInstance()->selectDB(MONGO_MANAGE_DB)->createCollection('productpolicy');
        $data = [
            [
                'guid' => 'D49170C0-B076-4795-B079-0F97560485AF',
                'type' => 1,
                'name' => 'Window防病毒',
                'kind' => 1,
            ],
            [
                'guid' => 'A40D11F7-63D2-469d-BC9C-E10EB5EF32DB',
                'type' => 1,
                'name' => 'Linux防病毒',
                'kind' => 1,
            ],
            [
                'guid' => '53246C2F-F2EA-4208-9C6C-8954ECF2FA27',
                'type' => 1,
                'name' => 'IP管理',
                'kind' => 1,
            ],
            [
                'guid' => '53246C2F-F2EA-4208-9C6C-8954ECF2FA27',
                'type' => 1,
                'name' => '审计管理',
                'kind' => 2,
            ],
            [
                'guid' => '74F2C5FD-2F95-46be-B67C-FFA200D69012',
                'type' => 1,
                'name' => '安卓管理',
                'kind' => 1,
            ],
            [
                'guid' => 'EB8AFFA5-0710-47e6-8F53-55CAE55E1915',
                'type' => 2,
                'name' => '自动入组',
                'kind' => 1,
            ],
            [
                'guid' => '50BAC747-7D02-4969-AF79-45EE47365C81',
                'type' => 1,
                'name' => '终端升级',
                'kind' => 1,
            ],
            [
                'guid'=>'DB8F440F-3717-47f6-8922-4D9D6A89B824',
                'type'=>2,
                'name'=>'网盘功能客户端开关',
                'kind'=>1
            ],
        ];
        $pp->batchInsert($data);
    }
}
