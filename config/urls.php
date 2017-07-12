<?php
switch(get_cfg_var('env')){
case 'develop':
    return array(
        'exe' => 'http://192.168.20.90/rsv16/sohover.xml',
        'dl' => 'http://192.168.20.171/dl',
        'dl_lan'=>'http://192.168.20.171/dl',
        'message' => 'http://192.168.20.171/message',
        'platform' => 'http://192.168.20.171',
        'sign' => 'http://192.168.21.26:8090/sign',
    );
case 'testing':
    return array(
        'exe' => 'http://192.168.20.90/rsv16/sohover.xml',
        'dl' => 'http://193.168.10.101:8888/dl',
        'dl_lan'=>'http://193.168.10.101:8888/dl',
        'message' => 'http://193.168.10.101:8888/message',
        'platform' => 'http://193.168.10.101',
        'sign' => 'http://193.168.10.117:8089/sign',
    );
case 'production':
    return array(
        'exe' => 'http://rsup16.rising.com.cn/rsv16/tsohover.xml',
        'dl' => 'http://dl.anquanyun.cc/dl',
        'dl_lan'=>'http://192.168.21.23/dl',
        'message' => 'http://192.168.21.21:8888/message',
        'platform' => 'https://www.anquanyun.cc',
        'sign' => 'http://192.168.21.27:8090/sign',
    );
}

