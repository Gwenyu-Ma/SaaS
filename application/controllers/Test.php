<?php
use ChromePhp as Console;
use \Lib\Model\AutoGroup;
use \Lib\Model\Test;
use \Lib\Store\Mysql;
use \Lib\Store\RedisCluster as rds;
use \Lib\Store\RedisCluster as Redis;
use \Lib\Store\MysqlCluster as MC;
use \Lib\Util\EmailQueue;
use \Lib\Util\Log;
use \Intervention\Image\ImageManagerStatic as Image;
use \Lib\Model\RedisDataManager;
use \Lib\Util\Crypt;
use \Lib\Util\Ioa;

class TestController extends MyController
{
    public function init()
    {
        //parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
        echo '<pre>';
    }


    public function optionDbAction(){
       $aEid = Mysql::getAll('select eid,orgid from eid_orgid where eid not in(select EID from esm_user)');
        foreach($aEid as $eid){
            $aAccount = Mysql::getRow('select account from user_link where org_id=?', array($eid['orgid']));
            if(empty($aAccount)){
                continue;
            }else{
                $account = $aAccount['account'];
            }
            if(filter_var($account, FILTER_VALIDATE_EMAIL)){
                $fiels = 'EMail';
            }else{
                $fiels = 'PhoneNO';
            }
            $str_create_time = date("Y-m-d H:i:s",time());
            Mysql::exec("insert into esm_user(Account,EID,UserName,$fiels,PWD,Salt,Status,UType,Level,LockState,CreateTime,LastLoginTime) values(?,?,?,?,?,?,?,?,?,?,?,?)", array('111',$eid['eid'], $account,$account,'md5::262144::205a9494842cd3e59697e5cec79760e1','30989e676370e0177935705a2e09c843',1,1,1,1,$str_create_time,$str_create_time));
        }
    }
    public function ioaAction(){
        $str_name = '17010076122';
        $objIoa = Ioa::createIoaOrg( $str_name);
        var_dump( $objIoa );

    }

    public function mysqlAction()
    {
        // create
        $ok = Mysql::exec('insert into esm_user(EID,Email) values(?,?)', array('testeid', 'test@email.com'));
        $id = Mysql::getInsertID();

        // update
        $ok = Mysql::exec('update esm_user set Email=? where EID=?', array('newtest@email.com', 'testeid'));

        // query
        $all = Mysql::getAll('select * from esm_user');
        $row = Mysql::getRow('select * from esm_user where EID=?', array('testeid'));

        // delete
        $ok = Mysql::exec('delete from esm_user where EID=?', array('testeid'));
    }

    public function redisAction()
    {
        var_dump(Redis::hGet('hep_E3AE744BFD0A72B1E509CA63C24C4D22','ep_info'));
        return;
        Redis::set('testkey', 'testvalue22'); // echo 'list methods: https://github.com/phpredis/phpredis/ https://secure.php.net/manual/zh/class.mongocollection.php';

        $v = Redis::get('testkey');
        Console::log($v);
        $v = Redis::del('testkey');
        Console::log($v);
        $v = Redis::get('testkey');
        Console::log($v);
    }

    public function mongoAction()
    {
        $clct = select_manage_collection('hrllo'); // list methods, https://secure.php.net/manual/zh/class.mongocollection.php
        //        $res=$clct->update( array('id'=>1457414833) ,array('$set'=>array('groupname'=>'修改') ) );
        //
        //        var_dump();

        // create
        $document = array('EID' => 'test', 'name' => 'test', 'age' => 9);
        $ok = $clct->insert($document);

        // update
        $ok = $clct->update(array('EID' => 'test'), array('$set' => array('age' => 10)));

        // query
        $data = $clct->find(array('EID' => 'test'));

        // delete
        $ok = $clct->remove(array('EID' => 'test'), array('justOne' => true));
    }

    public function emailAction()
    {
        $ok = EmailQueue::push(array(
            array('name' => '', 'email' => 'tmp@ym.txthinking.com'),
            array('name' => '', 'email' => '2310605007@qq.com'),
        ), 'Subject', 'Content');
        var_dump($ok);
    }

    public function logAction()
    {
        $ok = Log::add('hello');
        var_dump($ok);
    }

    public function autoAction()
    {
        $ag = new AutoGroup();
        $p = [
            array(
                'groupid' => 2,
                'rule' => [
                    [
                        'type' => 'ip',
                        'symbol' => 'equal',
                        'value' => '193.168.19.68',
                    ],
                    [
                        'type' => 'ip',
                        'symbol' => 'notequal',
                        'value' => '193.168.19.68',
                    ],
                    [
                        'type' => 'ip',
                        'symbol' => 'in',
                        'value' => '193.168.19.68-193.168.19.69',
                    ],
                    [
                        'type' => 'ip',
                        'symbol' => 'notin',
                        'value' => '193.168.19.68-193.168.19.69',
                    ],
                    [
                        'type' => 'os',
                        'symbol' => 'has',
                        'value' => 'osx',
                    ],
                    [
                        'type' => 'os',
                        'symbol' => 'nothas',
                        'value' => 'osx',
                    ],
                    [
                        'type' => 'computername',
                        'symbol' => 'has',
                        'value' => 'TEST',
                    ],
                    [
                        'type' => 'computername',
                        'symbol' => 'nothas',
                        'value' => 'TEST',
                    ],
                ],
            ),
            array(
                'groupid' => 1,
                'rule' => [
                    [
                        'type' => 'ip',
                        'symbol' => 'in',
                        'value' => '193.168.19.68-193.168.19.69',
                    ],
                    [
                        'type' => 'os',
                        'symbol' => 'has',
                        'value' => 'unix',
                    ],
                    [
                        'type' => 'computername',
                        'symbol' => 'has',
                        'value' => 'tx',
                    ],
                ],
            ),
            array(
                'groupid' => 1457414833,
                'rule' => [
                    [
                        'type' => 'ip',
                        'symbol' => 'in',
                        'value' => '193.168.19.68-193.168.19.69',
                    ],
                    [
                        'type' => 'os',
                        'symbol' => 'has',
                        'value' => 'windows',
                    ],
                    [
                        'type' => 'os',
                        'symbol' => 'nothas',
                        'value' => 'unix',
                    ],
                    [
                        'type' => 'computername',
                        'symbol' => 'nothas',
                        'value' => 'tx',
                    ],
                    [
                        'type' => 'computername',
                        'symbol' => 'has',
                        'value' => 'TEST',
                    ],
                ],
            ),
        ];
        //echo json_encode($p);exit;
        //$r = $ag->updateRules('BB8A7644925C2E62', $p);
        $r = $ag->autoInGroup('BB01821472536395', 'AE9B3BDC9E0BC5CCC1E3D4CEAB4B2740');
        d($r);
    }
    private function getv($s)
    {
        $arr = str_split($s, 1);
        $v = 0;
        for ($i = 0; $i < strlen($s); $i++) {
            $v = $v + ord($arr[$i]);
        }
        return $v;
    }
    //http://193.168.10.101:8002/index.php/test/getredis?groupid=1458032905&sguid=51BEFE80-3B57-014E-2583-56D7Ddfvdf
    public function getredisAction()
    {
        $eid = "667F329377901272";
        $sguid = "17B90AD0-2AD0-AF41-A819-B608C0725DB5";//$_GET['sguid']; //"51BEFE80-3B57-014E-2583-56D7DA734Bx";
        $groupid = "14741689434804";                        //$_GET['groupid']; //"1458475376";
        $systype = "windows";

        $cver = Redis::hGet(CACHE_REDIS_EP_PRE .$eid. $sguid, 'c_ver');
        echo '客户端g_info';
        var_dump(Redis::hGet(CACHE_REDIS_EP_PRE .$eid . $sguid, 'g_info'));
        echo '客户端p_info';
        var_dump(Redis::hGet(CACHE_REDIS_EP_PRE .$eid . $sguid, 'p_info'));
        echo '客户端p_ver';
        var_dump(Redis::hGet(CACHE_REDIS_EP_PRE .$eid . $sguid, 'p_ver'));
        echo '客户端c_ver';
        var_dump(Redis::hGet(CACHE_REDIS_EP_PRE  .$eid. $sguid, 'c_ver'));
        echo '客户端ep_info';
        var_dump(Redis::hGet(CACHE_REDIS_EP_PRE  .$eid. $sguid, 'ep_info'));
        echo Redis::hGet(CACHE_REDIS_EP_PRE  .$eid. $sguid, 'ep_info');
        echo '<pre>';
        echo '全网策略';
        var_dump(Redis::hGet(CACHE_REDIS_ORG_PRE . $eid, 'p_global'));
        echo '组侧策略';
        var_dump(Redis::hGet(CACHE_REDIS_ORG_PRE . $eid, 'p_group_' . $groupid));
        echo '命令';
        var_dump(Redis::LRange(CACHE_REDIS_EP_CMD_PRE  .$eid. $sguid,0,-1));
        $cmds = Redis::LRange(CACHE_REDIS_EP_CMD_PRE  .$eid. $sguid,0,-1);
        foreach($cmds as $cmd){
            echo Redis::get($cmd).'<br />';
        }
        echo 'eid缓存<br />';
        echo Redis::hGet(CACHE_REDIS_ONLINESTATE_PRE . $eid,$eid );

        print_r(Redis::hGetAll(CACHE_REDIS_ONLINESTATE_PRE . $eid ));

        echo '<br />';
        echo date('Y-m-d H:i:s', Redis::hGet(CACHE_REDIS_ONLINESTATE_PRE.$eid,$systype. ':' .$sguid)).'<br />';
        echo date('Y-m-d H:i:s', time()).'<br />';
    }

    public function txAction()
    {
        $a = new GroupModel();
        $eids = \Lib\Store\Mysql::getCol('select EID as eid from esm_user');
        foreach ($eids as $eid) {
            $r = $a->AddGroup('默认分组', '默认分组', $eid, 0);
        }
    }

    public function rcAction()
    {
        $eid = '';
        $sguid = '';
        Test::testHep($sguid);

        echo '--------------------------------------------------<br /><br /><br />';

        Test::testHeid($eid, 1459253479);

        echo '--------------------------------------------------<br /><br /><br />';

        Test::testLcmd($sguid);
    }

    public function inblackmenuAction()
    {
        $sguid = $_GET['sguid'];
        echo Redis::hGet(CACHE_REDIS_EP_PRE . $sguid, 'inblackmenu');
    }

    public function isBlackGroupAction()
    {
        Test::isBlackGroup('E9F156E87B2E5822', 14624380405864);
    }

    public function osAction()
    {
        echo \Lib\Util\Common::os('microsoft Windows 7 Ultimate Edition Service Pack 1 (build 7601), 64-bit');
    }

    public function logdataAction()
    {
        $data = (new LogDataModel("5A1C0E3166461707"))
            ->select(["virusname", "virusclass", "count(sguid) as sc"])
            ->from("XAV_Virus")
            ->where(["time>'2015-05-26 17:31:19'", "time<'2017-05-26 17:31:19'", "virusname like '%Sality%'"])
            ->groupBy("virusname")
            ->orderBy('virusname')
            ->desc()
            ->offset(0)
            ->limit(10)
            ->data();
        d($data);
    }

    public function imageAction()
    {
        $a = Image::make(__DIR__."/../../public/img/banner_1.png");
        var_dump($a);
    }

    public function subAction()
    {
        $msg = new MessageModel();
        $ok = $msg->addSubscriber("b:".$this->_eid.":admins:", json_encode(["rs:", "pf:ep:"]));
        d($ok);
    }

    public function isOnlineAction()
    {
        $eid = $_GET['eid'];
        echo $eid.'<br />';
        $mm = RedisDataManager::getEpOnline($eid);
        print_r($mm);

        echo '<br />';
        $tmpEID = Redis::hGet(CACHE_REDIS_ONLINESTATE_PRE . $eid,$eid );

        echo $tmpEID;
        echo '<br />';
        if($tmpEID===$eid)
        {
            echo '000000<br />';
        }
        else
        {
            echo '1111111<br />';
        }
        return;
    }

    public function getepqueueAction()
    {
        $list = Redis::lRange(REDIS_EPINFO_QUEUE ,0,-1);
        print_r($list);
    }

    public function smsAction()
    {
        $msg = "天涯何处觅知音。 【瑞星云安全中心】";
        $ok = Common::esmSend(18301085173, $msg);
        d($ok);
    }

    public function msgAction()
    {
        $eid = $this->_eid;
        $uid = $_SESSION['UserInfo']['UserID'];
        $m = new MessageModel();
        $ok = $m->makeMsg(json_encode(["rs:welcome:$eid:admins:$uid"]), '欢迎登录瑞星云', '欢迎登录瑞星云1');
        $ok = $m->makeMsg(json_encode(["rs:welcome:$eid:admins:$uid"]), '欢迎登录瑞星云', '欢迎登录瑞星云2');
        d($ok);
    }

    public function ipAction()
    {
        d(explode(':', 'a')[0]);
        d($_SERVER['HTTP_HOST']);
    }

    public function timeAction()
    {

        date_default_timezone_set('Asia/Hong_Kong');
        d(
            date_default_timezone_get(),
            date('Y-m-d H:i:s', 1467341297)
        );
    }

    public function tAction(){

        echo 'list len : '.rds::lLen(CACHE_REDIS_EP_CMD_PRE . '667F329377901272' . '17B90AD0-2AD0-AF41-A819-B608C0725DB5').'<br />';

    }

    public function aigAction()
    {
        $ag = new AutoGroup();
        $r = $ag->autoInGroup($_GET['eid'], $_GET['sguid']);
        d($r);
    }

    public function testAction()
    {
        $ep = new EpModel();
        $ep->getUnsetEPs();
        //print_r(Redis::lRange(REDIS_EPUNSET_QUEUE,0,-1));
        //Test::testTimeout();
    }

    public function initEidAction()
    {
        echo "初始化eid...";
        RedisDataManager::initEid();
        echo '初始化完成';
    }

    public function initRedisAction()
    {
        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys("strcmd_*");
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys("lcmd_*");
        print_r(count($lcmdKeys));
        echo "\n";

        echo '清空'."\n";
        Redis::del($strcmdKeys);
        Redis::del($lcmdKeys);
        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys("strcmd_*");
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys("lcmd_*");
        print_r(count($lcmdKeys));
        echo "\n";

        echo "初始化redis....\n";
        RedisDataManager::init();
        echo '初始化完成';

        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys("strcmd_*");
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys("lcmd_*");
        print_r(count($lcmdKeys));
    }

    public function initCMDByEIDAction()
    {
        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys("strcmd_*");
        print_r($strcmdKeys);
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys("lcmd_*");
        print_r($lcmdKeys);
        print_r(count($lcmdKeys));
        echo "\n";
        Redis::get($strcmdKeys);
        echo '清空'."\n";
       // Redis::del($strcmdKeys);
       // Redis::del($lcmdKeys);
        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys("strcmd_*");
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys("lcmd_*");
        print_r(count($lcmdKeys));
        echo "\n";

        echo "初始化redis....\n";
        //RedisDataManager::initCMD('F049DDF749926930');
        echo '初始化完成';

        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys("strcmd_*");
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys("lcmd_*");
        print_r(count($lcmdKeys));
    }

    public function  initCMDBySguidAction()
    {
        $eid='D05D6DE488005623';
        $groupId=14712457822358;
        $sguid='3C2072C53F14ABADCF80823A2C893BF9';
        $strKey='strcmd_'.$eid.'*';
        $lcmdKey='lcmd_'.$eid.$sguid.'*';
        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys($strKey);
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys($lcmdKey);
        print_r(count($lcmdKeys));
        echo "\n";

        echo '清空'."\n";
        Redis::del($strcmdKeys);
        Redis::del($lcmdKeys);
        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys($strKey);
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys($lcmdKey);
        print_r(count($lcmdKeys));
        echo "\n";

        echo "初始化redis....\n";
        RedisDataManager::initByClientID($eid,$groupId,$sguid);
        echo '初始化完成';

        echo 'strcmd_*共:';
        $strcmdKeys=Redis::keys($strKey);
        print_r(count($strcmdKeys));
        echo '  lcmd_*共:';
        $lcmdKeys=Redis::keys($lcmdKey);
        print_r(count($lcmdKeys));
    }
    public function oplogAction()
    {
        add_oplog('a','a','a','a','a','a');
    }
    public function helloAction()
    {
        $this->_response->setBody(json_encode(['hello','world']));
    }

    public function vieweidredisAction()
    {
        $eid = "F049DDF749926930";

        $keys = Redis::keys("*$eid*");
        foreach($keys as $key){
            echo $key.'<br />';
        }

        echo '<br />-----------------------------<br />';
        $eid = "1234567890123456";

        $keys = Redis::keys("*$eid*");
        foreach($keys as $key){
            echo $key.'<br />';
        }
    }

    public function studentAction(){
        echo 'abc';
    }

}
