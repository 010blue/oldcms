<?php
/**
 * User.class.php 用户操作类
 * ----------------------------------------------------------------
 * OldCMS,site:http://www.oldcms.com
 */
if(!defined('IN_OLDCMS')) die('Access Denied');

class User{
	public $userId=0,$adminLevel=0,$userName='',$avatarImg='',$signature='',$token;
	private $db,$tbUser,$tbField,$tbUserField,$tbSession,$expiryTime=3600,$ocKey;
	function __construct(){
		if(intval(EXPIRES)>0) $expiryTime=intval(EXPIRES);
		$this->db=DBConnect();
		$this->tbUser=$this->db->tbPrefix.'user';
		$this->tbSession=$this->db->tbPrefix.'session';
		$this->ocKey=Val('ocKey','COOKIE');
		if(!empty($this->ocKey)){
			$row=$this->db->FirstRow("SELECT userId,token,data FROM ".$this->tbSession." WHERE ocKey='".$this->ocKey."' AND expires>".time());
			$this->userId=intval($row['userId']);
			$this->token=$row['token'];
			$data=$row['data'];
			if(!empty($data)){
				$data=unserialize($data);
				$this->email=$data['email'];
				$this->adminLevel=intval($data['adminLevel']);
				$this->userName=$data['userName'];
				$avatarDir=URL_ROOT.'/upload/avatar/';
				$this->avatarImg=empty($data['avatarImg']) ? $avatarDir.'avatar_50_50.gif' : $avatarDir.$data['avatarImg'];
				$this->avatarImg_s=empty($data['avatarImg_s']) ? $avatarDir.'avatar_30_30.gif' : $avatarDir.$data['avatarImg_s'];
				$this->signature=$data['signature'];
				$this->db->Execute("UPDATE ".$this->tbSession." SET updateTime='".time()."',expires=expires+".$this->expiryTime." WHERE ocKey='".$this->ocKey."'");
			}
		}
	}
	/* 登录界面 */
	function ToLogin(){
		include_once(ROOT_PATH.'/source/login.php');
		exit;
	}
	/* 登录 */
	function Login($username='',$password='',$autoLogin=0){
		$loginSql="SELECT id,adminLevel,userName,email,userPwd,validated,avatarImg,avatarImg_s,signature FROM ".$this->tbUser." WHERE 1=1";
		$loginSql.=strpos($username,'@') ? " AND email='{$username}'" : " AND userName='{$username}'";
		$row=$this->db->FirstRow($loginSql);
		if($row && $row['userPwd']==OCEncrypt($password)){
			if(MAIL_AUTH && $row['validated']==0){
				$this->ToValidate($row['email']);
			}else{
				$this->db->Execute("DELETE FROM ".$this->tbSession." WHERE expires<".time());
				$ocKey=OCEncrypt($row['id'].'-'.$row['userName'].'-'.$row['userPwd']);
				if($autoLogin==1) $this->expiryTime=360*86400;
				OCSetCookie('ocKey',$ocKey,time()+$this->expiryTime);
				$token=OCEncrypt(substr($ocKey,0,5).time());
				$data=serialize(array('userId'=>$row['id'],'email'=>$row['email'],'adminLevel'=>$row['adminLevel'],'userName'=>$row['userName'],'avatarImg'=>$row['avatarImg'],'avatarImg_s'=>$row['avatarImg_s'],'signature'=>$row['signature']));
				$sqlValue=array(
					'userId'=>$row['id'],
					'ocKey'=>$ocKey,
					'token'=>$token,
					'ip'=>IP(),
					'data'=>$data,
					'expires'=>time()+$this->expiryTime,
					'updateTime'=>time(),
					'addTime'=>time()
				);
				$sessionExisted=$this->db->FirstValue("SELECT COUNT(*) FROM ".$this->tbSession." WHERE ocKey='{$ocKey}'");
				if($sessionExisted>0){
					$this->db->AutoExecute($this->tbSession,$sqlValue,'UPDATE'," ocKey='{$ocKey}'");
				}else{
					$this->db->AutoExecute($this->tbSession,$sqlValue);
				}
				$this->db->Execute("UPDATE ".$this->tbUser." SET loginTime='".time()."' WHERE id='{$row[id]}'");
			}
			return true;
		}else{
			return false;
		}
	}

	/* 验证提醒 */
	function ToValidate($email){
		global $show,$url;
		$smarty=InitSmarty();
		$smarty->assign('email',$email);
		$smarty->assign('show',$show);
		$smarty->assign('url',$url);
		$smarty->display('validate.html');
		exit;
	}
	/* 退出 */
	function Logout(){
		if($this->userId>0){
			//del cookie
			OCSetCookie('ocKey','',-3600);
		}
		return true;
	}
	/* 更新session数据 */
	function UpdateSession($newData=array()){
		$data=$this->db->FirstValue("SELECT data FROM ".$this->tbSession." WHERE ocKey='".$this->ocKey."'");
		if(count($data)>0){
			$data=unserialize($data);
			foreach($newData as $key=>$value){
				$data[$key]=$value;
			}
			$data=serialize($data);
			$this->db->Execute("UPDATE ".$this->tbSession." SET data='{$data}' WHERE ocKey='".$this->ocKey."'");
		}
	}
	/**
		CheckToken 验证操作token
	*/
	function CheckToken($token=''){
		return $token==$this->token;
	}
}
?>