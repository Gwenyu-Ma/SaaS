<?php
namespace Plugs\IOA\Conf;

/**
 * 定义接口错误编码
 * Interface IErrCode
 * @package Api\Conf
 */
interface IErrCode {
    /*表示请求成功*/
    const I_SUCCESS = "0000";

    /*表示请求参数个数错误*/
    const I_ARGS_COUNT_WRONG = "2000";

    /*表示请求参数类型不正确*/
    const I_ARGS_TYPE_WRONG = "2001";

    /*表示参数值为空*/
    const I_ARGS_VALUE_EMPTY = "2002";

    /*表示参数值格式不正确*/
    const I_ARGS_FORMAT_ERROR = "2003";

    /*表示IOA返回数据为空*/
    const I_IOA_RETURN_DATA_EMPTY = "2004";

    /*表示IOA返回数据解析错误*/
    const I_IOA_RETURN_DATA_PARSE_WRONG = "2005";

    /*表示公钥错误*/
    const I_RSA_PUBLIC_KEY_WRONG = "2006";

    /*表示私钥错误*/
    const I_RSA_PRIVATE_KEY_WRONG = "2007";

    /*表示公钥加密错误*/
    const I_RSA_PUBLIC_ENCRYPT_WRONG = "2008";

    /*表示私钥加密错误*/
    const I_RSA_PRIVATE_ENCRYPT_WRONG = "2009";

    /*表示公钥解密错误*/
    const I_RSA_PUBLIC_DECRYPT_WRONG = "2010";

    /*表示私钥解密错误*/
    const I_RSA_PRIVATE_DECRYPT_WRONG = "2011";

    /*表示签名生成失败*/
    const I_RSA_SIGN_GENERATE_FAIL = "2012";

    /*表示验签失败*/
    const I_RSA_SIGN_VERIFY_FAIL = "2013";

    /*表示rsa需要加密的数据为空*/
    const I_RSA_DATA_RAW_EMPTY = "2014";

    /*表示网络通讯失败*/
    const I_NETWORK_FAIL = "2015";

    /*表示命名空间不存在*/
    const I_NAMESPACE_EXISTS_NOT = "2016";

    /*表示类文件不存在*/
    const I_CLASS_EXISTS_NOT = "2017";

    /*表示调用方法不存在*/
    const I_METHOD_EXIST_NOT = "2018";
}