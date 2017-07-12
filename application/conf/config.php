<?php
$config = array(
    'ACTION_SUFFIX'         =>  'Action',           // 操作方法后缀
    'LOAD_EXT_CONFIG'       =>  "constant",         //常量函数定义文件constant.php

    'URL_REDIRECT'          =>  array(
                                array('index',array('#^index_new.html$#',array('controller'=>"Index",'action'=>"index"))),
                                array('login',array('#^login.html$#',array('controller'=>"Index",'action'=>"login"))),
                                array('register',array('#^register.html$#',array('controller'=>"Index",'action'=>"register"))),
                                array('findPwdByEmail',array('#^findPwdByEmail.html$#',array('controller'=>"Index",'action'=>"findByEmail"))),
                                array('findPwdByPhone',array('#^findPwdByPhone.html$#',array('controller'=>"Index",'action'=>"findByphone"))),
    ),

    "MONGODB_HOST"             =>  "127.0.0.1:27017",
    "MONGODB_LIST"          =>  array("127.0.0.1"),

    "VERIFY_CONFIG"         =>   array(
                                'expire'      =>    300,
                                'fontSize'    =>    25,
                                'imageW'      =>    220,
                                'imageH'      =>    60,
                                'useImgBg'    =>    false,
                                'useCurve'    =>    false,
                                'length'      =>    5,
                                'useNoise'    =>    false,
    ),
    //病毒状态
    "XAV_VIRUS_STATE"             =>  array(
                                '0' => '未处理',
                                '1' => '成功',
                                '2' => '处理失败',
                                '3' => '备份失败',
                                '4' => '处理中'
    ),
    //病毒扫描事件状态(1对应0~4,2对应5~7)
    "XAV_VIRUSEVENT_STATE"        =>  array(
                                '1' => '扫描中',
                                '2' => '扫描完成'
    ),
    //病毒分类
    "XAV_VIRUS_CLASS"          =>  array(
                                '0' => '可疑',
                                '1' => '病毒',
                                '2' => '蠕虫',
                                '3' => 'rookit',
                                '4' => '广告',
                                '5' => '木马',
                                '6' => '后门',
                                '7' => '壳'
    ),
    //处理方式
    "XAV_VIRFUS_TREATMETHOD"   =>  array(
                                '0' => '暂未处理',
                                '1' => '忽略',
                                '2' => '删除',
                                '3' => '清除',
                                '4' => '信任',
                                '5' => '上报'
    ),
    //病毒来源
    "XAV_VIRUS_SOURCE"          =>  array(
                                'quickscan' => '快速查杀',
                                'allscan'   => '全盘查杀',
                                'customscan'=> '自定义查杀',
                                'filemon'   => '文件监控',
                                'mailmon'   => '邮件监控'
    ),
    //病毒扫描事件来源
    "XAV_VIRUSEVENT_SOURCE"     =>  array(
                                'quickscan' => '快速查杀',
                                'allscan'   => '全盘查杀',
                                'customscan'=> '自定义查杀'
    ),

    //系统加固-防护类型
    "XAV_SYS_DEFTYPE"          =>  array(
                                '1' => '文件防护',
                                '2' => '注册表防护',
                                '3' => '进程防护',
                                '4' => '系统防护'
    ),

    //系统加固-处理结果
    "XAV_SYS_RESULT"           =>  array(
                                '1' => '允许',
                                '2' => '阻止',
                                '3' => '永久允许',
                                '4' => '永久阻止',
                                '5' => '允许一次',
                                '6' => '阻止一次'
    ),

    //应用加固-应用来源类型
    "XAV_REIN_TYPE"            =>  array(
                                '0' => '来源于IE浏览器',
                                '1' => '来源于办公软件',
    ),

    //应用加固-应用行为操作类型
    "XAV_REIN_OPERATION"       =>  array(
                                '0' => '运行自释放文件',
                                '1' => '运行受限程序',
                                '2' => '篡改他进程的内存',
                                '3' => '在其他进程中启动线程',
                                '4' => '改写自启动项目',
                                '5' => '以写方式打开系统可执行文件',
                                '6' => '释放驱动程序',
                                '7' => '注册系统服务，误报太多去掉',
                                '8' => '加载自释放动态库'
    ),
    //user 配置信息
    "USER_TYPE"               =>array(
                                '1'=>"企业用户",
                                '2'=>"家庭用户"
    ),
    "USER_STATUS"             =>array(
                                '0'=>"停用",
                                '1'=>"未激活",
                                '2'=>"已经删除"

    ),
    "LEVEL"                   =>array(
                                '1'=>"主帐号",
                                '0'=>"普通帐号"

    ),
    "LOCKSTATE"             =>array(
                                '0'=>"锁定",
                                '1'=>"未锁定",
    ),
    "USER_ACTIVATION_CONTENT_TYPE"  =>array(
                                '1'=>'注册新用户',
                                '2'=>'找回密码',
    ),
    "USER_ACTIVATION_CONTACT_TYPE"  =>array(
                                '1'=>'邮件',
                                '2'=>'手机',
    ),
    "USER_ACTIVATION_SEND_STATE"  =>array(
                                '1'=>'未发送',
                                '2'=>'已发送',
    ),

    //产品配置信息
    "PRODUCT_GUID_INFO" => array(
        "D49170C0-B076-4795-B079-0F97560485AF_1"=>array(
            "NAME"     => "Window防病毒",
            "CONDNAME" => "XAV",
            "MEMO"     => "Window防病毒"
        ),
        "A40D11F7-63D2-469d-BC9C-E10EB5EF32DB_1"=>array(
            "NAME"     => "Linux防病毒",
            "CONDNAME" => "L27I",
            "MEMO"     => "Linux防病毒"
        ),
        "53246C2F-F2EA-4208-9C6C-8954ECF2FA27_1"=>array(
            "NAME"     => "IP管理",
            "CONDNAME" => "MANAGER",
            "MEMO"     => "IP管理"
        ),
        "53246C2F-F2EA-4208-9C6C-8954ECF2FA27_2"=>array(
            "NAME"     => "审计管理",
            "CONDNAME" => "RBA",
            "MEMO"     => "审计管理"
        ),
        "74F2C5FD-2F95-46be-B67C-FFA200D69012_1"=>array(
            "NAME"     => "安卓管理",
            "CONDNAME" => "MANAGER",
            "MEMO"     => "安卓管理"
        ),        
        "autoGroup_1"=>array(
            "NAME"     => "自动入组",
            "CONDNAME" => "MANAGER",
            "MEMO"     => "自动入组"
        ),        
        "50BAC747-7D02-4969-AF79-45EE47365C81_1"=>array(
            "NAME"     => "终端升级",
            "CONDNAME" => "RUC",
            "MEMO"     => "终端升级"
        ),
        "EB8AFFA5-0710-47E6-8F53-55CAE55E1915_1"=>array(
            "NAME"     => "终端设置",
            "CONDNAME" => "RUC",
            "MEMO"     => "终端设置"
        )

        /*
        "0254B85C-A35E-4D5B-9457-8F2A49D70D17"=>array(
            "NAME"     => "管理中心",
            "CONDNAME" => "MANAGER",
            "MEMO"     => "管理中心"
        ),
        "1716A1B0-48FC-4EC8-B2BA-F1D934D6A32C"=>array(
            "NAME"     => "核心组件",
            "CONDNAME" => "COMMON",
            "MEMO"     => "核心组件"
        ),
        "245DBAD2-4376-4619-B758-2A69A0024936"=>array(
            "NAME"     => "数据防泄漏",
            "CONDNAME" => "DLP",
            "MEMO"     => "数据防泄漏组件"
        ),
        "268DF90E-8192-4b2d-8FFF-CD67357C8522"=>array(
            "NAME"     => "反病毒引擎",
            "CONDNAME" => "RSV",
            "MEMO"     => "反病毒引擎"
        ),
        "337A1520-2DB0-4717-837B-705663627C88"=>array(
            "NAME"     => "设备管理基础",
            "CONDNAME" => "RDF",
            "MEMO"     => "设备管理基础组件"
        ),
        "3C9C71C1-C820-47d6-8828-6CBD9217A1E3"=>array(
            "NAME"     => "监控组建",
            "CONDNAME" => "CLIENTCOMM",
            "MEMO"     => "监控组件"
        ),
        "40BAC747-7D02-4969-AF79-45EE47365C81"=>array(
            "NAME"     => "升级中心",
            "CONDNAME" => "RUC",
            "MEMO"     => "升级中心，负责实现企业内部升级环境。"
        ),
        "50BAC747-7D02-4969-AF79-45EE47365C81"=>array(
            "NAME"     => "软件部署组件",
            "CONDNAME" => "RUA",
            "MEMO"     => "瑞星客户端软件部署，基础子产品，负责更新客户上的其它子产品。"
        ),
        "5B90F684-3BBE-4539-90C7-C0767FCC0E04"=>array(
            "NAME"     => "文件管理服务",
            "CONDNAME" => "RFM",
            "MEMO"     => "文件管理服务组件"
        ),
        "5F5D299F-1EF6-4573-9268-ED956B8EE2A1"=>array(
            "NAME"     => "行为审计",
            "CONDNAME" => "RBA",
            "MEMO"     => "瑞星客户端行为审计子产品。"
        ),
        "64499871-9797-479f-A27A-4AC0A5C7EF26"=>array(
            "NAME"     => "漏洞扫描",
            "CONDNAME" => "RLS",
            "MEMO"     => "瑞星漏洞扫描子产品，负责扫描并修补客户端上的系统漏洞与应用程序漏洞。"
        ),
        "66BD0D73-19B6-44b8-8F29-1E69D456DAC2"=>array(
            "NAME"     => "XP盾",
            "CONDNAME" => "RXP",
            "MEMO"     => "XP盾组件"
        ),
        "6E2209A3-63F5-4e8e-BF22-F5C1AB40E589"=>array(
            "NAME"     => "网络安全管理",
            "CONDNAME" => "RSM",
            "MEMO"     => "网络安全管理子产品。"
        ),
        "725567FA-26F8-476c-8B27-A9415C6661FA"=>array(
            "NAME"     => "防火墙",
            "CONDNAME" => "FWBASE",
            "MEMO"     => "防火墙"
        ),
        "97BC9248-32D4-428f-8CB5-5450E5489025"=>array(
            "NAME"     => "U盘管理",
            "CONDNAME" => "RUT",
            "MEMO"     => "U盘管理"
        ),
        "A149E537-6B3D-4420-B126-E5DEE6567EF7"=>array(
            "NAME"     => "恶意网址库",
            "CONDNAME" => "FWLIB",
            "MEMO"     => "恶意网址拦截组件"
        ),
        "A88BB807-C585-4914-9A7B-BD06C4A48217"=>array(
            "NAME"     => "安全助手",
            "CONDNAME" => "RSA",
            "MEMO"     => "安全助手组件"
        ),
        "C926CC23-B3CA-4eda-A67C-9379A5501DDB"=>array(
            "NAME"     => "漏洞补丁中心",
            "CONDNAME" => "RDC",
            "MEMO"     => "用于搭建企业内网的补丁下载中心"
        ),
        "D49170C0-B076-4795-B079-0F97560485AF"=>array(
            "NAME"     => "防病毒",
            "CONDNAME" => "XAV",
            "MEMO"     => "瑞星防病毒组件"
        ),
        "EB8AFFA5-0710-47e6-8F53-55CAE55E1915"=>array(
            "NAME"     => "客户端代理",
            "CONDNAME" => "EP",
            "MEMO"     => "客户端代理，基础子产品，其它各子产品插件的基础。"
        ),
        "ECCB3C75-32B8-4aa4-9A16-6CDE3D0694B2"=>array(
            "NAME"     => "IT资产管理",
            "CONDNAME" => "RAM",
            "MEMO"     => "IT资产管理子产品。"
        ),
        "F1A05321-8959-48C7-8182-04A8DB6EEBF0"=>array(
            "NAME"     => "BUS",
            "CONDNAME" => "MANAGER",
            "MEMO"     => "业务中心，以负载均衡方式管理所有客户端，实现基础业务逻辑。"
        ),
        "F43F9CC6-18F5-453a-BDA9-B3F2F1F95CB2"=>array(
            "NAME"     => "信息内容审计",
            "CONDNAME" => "RIM",
            "MEMO"     => "瑞星客户端即时通讯审计子产品"
        )
        */

    )
);


