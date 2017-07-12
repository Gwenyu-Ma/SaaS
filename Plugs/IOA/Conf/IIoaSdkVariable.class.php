<?php
namespace Plugs\IOA\Conf;

/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-12-01
 * Time: 13:52
 */

/**
 * sdk配置参数
 * Interface IIoaSdk
 */
class IIoaSdkVariable {
    /*定义IOA网关地址*/
    //const gateway = "https://i.testioa.cn/public/publicRequest"; //测试环境用
    const gateway = "https://i.ioa.cn/public/publicRequest";

    /*平台标识*/
    const platform = "rising";

    /*默认团队名称*/
    const defaultOrgName = "瑞信安全云";
}