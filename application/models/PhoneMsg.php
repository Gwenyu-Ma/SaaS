<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21
 * Time: 14:50
 */
class PhoneMsgModel
{
	protected $db_obj;

	public function __construct()
	{
		$this->db_obj = new DbProcess();
	}

	//邮件操作信息记录到tuseractivate,temailasync表中
	public function addPhoneMsg($data)
	{
		$insert_data = array(
			'CheckCode'  => $data['CheckCode'],
			'Message'  => $data['Message'],
			'MsgType'  => $data['MsgType'],
			'PhoneNO'  => $data['PhoneNO'],
			'SendTime' => date("Y-m-d H:i:s")
		);
		return $this->db_obj->insertTab("esm_phonemsg", $insert_data);
	}
	
	/**
	 * 获取手机验证码
	 * @param string $userId
	 * @param int $msgType
	 * @return phoneMsg
	 */
	public function getPhoneMsg($phone,$msgType)
	{
		return $this->db_obj->getListOne("esm_phonemsg",array('CheckCode',"SendTime"),array("locate_PhoneNO"=>$phone,"locate_MsgType"=>$msgType),"SendTime","DESC");
	}
}