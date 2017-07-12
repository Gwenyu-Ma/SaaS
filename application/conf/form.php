<?php
/**
 * 配置项目中的表单信息
 * 以控制器作为第一索引，区分大小写
 * required 1|0 必须传|非必须
 * check_fun 校验方法，二维数组，key为校验方法名，value为参数数组信息，从第二个参数起的数组，可以为空
 * 应用call_user_fun_array或call_user_fun
 */
$form = array(
    'index'  => array(
        'register_form' => array(
            'name'  => array('desc' => '', 'value' => '', 'required' => 1, 'check_fun' => array('Common::checkUserName' => array(6, 25))),
            'pwd'   => array('desc' => '', 'value' => '', 'required' => 1, 'check_fun' => array('Common::checkPwd' => array())),
            'type'  => array('desc' => '', 'value' => '', 'required' => 1, 'check_fun' => array('Common::checkNum' => array())),
            'code'  => array('desc' => '', 'value' => '', 'required' => 1, 'check_fun' => array()),
        ),
        'login_form' => array(
            'name'  => array('desc' => '', 'value' => '', 'required' => 1, 'check_fun' => array('Common::checkUserName' => array(6, 25))),
            'pwd'   => array('desc' => '', 'value' => '', 'required' => 1, 'check_fun' => array('Common::checkPwd' => array())),
            'code'  => array('desc' => '', 'value' => '', 'required' => 0, 'check_fun' => array()),
        )
    ),
    'xavlog' => array(
        'SYSDEF_LOG_FORM' => array(
            'offset'    => array('value' => '', 'required' => 1, 'desc' => '', 'check_fun' => array('is_numeric' => array())),
            'limit'     => array('value' => '', 'required' => 1, 'desc' => '', 'check_fun' => array('is_numeric' => array(), 'Common::checkLimit' => array())),
            'time'      => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'sort'      => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'order'     => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'startime'  => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'endtime'   => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'deftype'   => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'result'    => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'client'    => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'group'     => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => '')
        ),
        'SYSAUDIT_LOG_FORM' => array(
            'offset'    => array('value' => '', 'required' => 1, 'desc' => '', 'check_fun' => array('is_numeric' => array())),
            'limit'     => array('value' => '', 'required' => 1, 'desc' => '', 'check_fun' => array('is_numeric' => array(), 'Common::checkLimit' => array())),
            'time'      => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'sort'      => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'order'     => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'startime'  => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'endtime'   => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'deftype'   => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'result'    => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'client'    => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'group'     => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => '')
        ),
        'VIRUSINFO_LOG_FORM' => array(
            'offset'     => array('value' => '', 'required' => 1, 'desc' => '', 'check_fun' => array('is_numeric' => array())),
            'limit'      => array('value' => '', 'required' => 1, 'desc' => '', 'check_fun' => array('is_numeric' => array(), 'Common::checkLimit' => array())),
            'time'       => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'sort'       => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'order'      => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'startime'   => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'endtime'    => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'virusclass' => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => ''),
            'virusname'   => array('value' => '', 'required' => 0, 'desc' => '', 'check_fun' => '')
        )
    )
);
