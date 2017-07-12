<?php
namespace Plugs\IOA\Conf;

/**
 * 定义接口错误提示
 * Interface IErrCode
 * @package Api\Conf
 */
interface IErrMsg {
    const I_SUCCESS                     = "请求成功";

    const I_ERROR_METHOD_EXIST_NOT      = "该方法不存在";

    const I_ERROR_ARG_COUNT_WRONG       = "参数个数不一致";

    const I_ERROR_ARG_TYPE_WRONG        = "参数类型必须是";

    const I_ERROR_ARG_VALUE_EMPTY       = "参数值不能为空";

    const I_ERROR_ARG_FORMAT_ERROR      = "参数值格式错误";

    const I_ERROR_ARG_VALUE_ZERO        = "参数值必须是大于0的数字";

    const I_ERROR_RSA_PARAM_NULL        = "rsa签名的请求参数不能为空";

    const I_ERROR_RSA_VERIFY_FAIL       = "返回签名验签失败";

    const I_ERROR_IOA_RETURN_EMPTY      = "ioa返回的数据为空";

    const I_ERROR_IOA_RETURN_DATA_PARSE_WRONG = "IOA返回数据解析错误";

    const I_ERROR_RSA_PUBLIC_KEY_WRONG  = "您使用的公钥错误，请检查公钥文件格式是否正确";

    const I_ERROR_RSA_PRIVATE_KEY_WRONG = "您使用的私钥格式错误，请检查RSA私钥配置";
}

