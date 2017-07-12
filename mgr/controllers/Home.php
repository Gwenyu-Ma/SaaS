<?php
/**
 * Created by PhpStorm.
 * User: xujy
 * Date: 2017/1/5
 * Time: 9:21
 */
class HomeController extends MyController
{
    public function indexAction(){
        $this->disply('Home/index', '.php');
    }


    //获取当前eid总数,客户端总数
    public function eidClientTotalAction(){
        $aRes = array();
        $objHome = new HomeModel();
        $countEid = $objHome->countEid();
        $clientTotal = $objHome->clientTotal();
        $countEid >0?$countEid=$countEid:$countEid=0;
        $clientTotal >0?$clientTotal=$clientTotal:$clientTotal=0;
        $aRes['eidTotal'] = $countEid;
        $aRes['clientTotal'] = $clientTotal;
        $this->ok($aRes);
    }

    //最近24个小时，每个整点EID数
    public  function hoursEidAction(){
        $objHome = new HomeModel();
        $hoursEid = $objHome->hoursEid();
        $this->ok($hoursEid);
    }

    ////最近24个小时，每个整点客户端数
    public function hoursClientAction(){
        $objHome = new HomeModel();
        $hoursClient = $objHome->hoursClient();
        $this->ok($hoursClient);
    }

    //全部终端在线数，卸载数
    public  function allOnOffLineAction(){
        $aRes = array();
        $objHome = new HomeModel();
        $onLineNum = $objHome->clientOnline();
        $offLineNum = $objHome->clientOffline();
        $aRes['onLineNum'] = $onLineNum;
        $aRes['offLineNum'] = $offLineNum;
        $this->ok($aRes);
    }

    //windows平台终端在线卸载数
    public function windowsClientInfoAction(){
        $aRes = array();
        $objHome = new HomeModel();
        $windowsClientOnLineNum = $objHome->clientOnOffLine('windows',0);
        $windowsClientOffLineNum = $objHome->clientOnOffLine('windows',1);

        $aRes['windowsClientOnLineNum'] = $windowsClientOnLineNum;
        $aRes['windowsClientOffLineNum'] = $windowsClientOffLineNum;
        $this->ok($aRes);
    }

    //android平台终端在线卸载数
    public function androidsClientInfoAction(){
        $aRes = array();
        $objHome = new HomeModel();
        $androidClientOnLineNum = $objHome->clientOnOffLine('android',0);
        $androidClientOffLineNum = $objHome->clientOnOffLine('android',1);

        $aRes['androidClientOnLineNum'] = $androidClientOnLineNum;
        $aRes['androidClientOffLineNum'] = $androidClientOffLineNum;
        $this->ok($aRes);
    }

}