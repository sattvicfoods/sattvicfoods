<?php  
/*Payment Name    : CCAvenue MCPG
Description		  : Extends Payment with  CCAvenue MCPG.
CCAvenue Version  : MCPG-2.0
Module Version    : bz-3.0
Author			  : BlueZeal SoftNet 
Copyright         : � 2014-2015 */
$file_ini = DOM_BZ_PATH_PG_INI_201."cbdom.ini";
if (file_exists($file_ini)) {
	include_once($file_ini);	
}
/**
* 
* This code coonect with the opencart databse for inserting the module details into the user server.
* 
*/
$dom	= "9ec66b15389a4e6c5d510b912a144b21215593a3741cad62042c5f0d57f117887995776739b77bdeff869b8db06760f6";
define("BZCCPG_API_URI",$dom);
	
class Cbdom extends Cbdom_main 
{   
 	private  $_license_api_table="apibzcc";
	private  $_license_api_table_prefix="bzccpg_";
	private  $_ini_created		=false;
	private	 $_pgmod_ver			= "";		/*==> Module Version*/
	private	 $_pgcat				= "";		/*==>Category*/
	private	 $_pgcat_ver  		= "";		/*==>Category Version*/
	private  $_pgcms 			= "";		/*==>CMS*/
	private	 $_pgcms_ver 		= "";		/*==>CMS Version*/
	private	 $_pg_lic_key 		= '';		/*Payment module license key*/
	public function __construct(){
		$this->init();
	}
    
	public function init()
	{
		if(!defined("DOM_BZ_PATH_PG_INI_201"))
		{
			return FALSE;
		}
		$file_ini = DOM_BZ_PATH_PG_INI_201."cbdom.ini";
		if (file_exists($file_ini)) {
			include_once($file_ini);
			$this->_ini_created=true;
		}
		else
		{
			$this->_ini_created=false;
		}
		return true;
	}
	public function IsIniFound()
	{
		return $this->_ini_created;
	}

	public function check_license($key)
	{
		if($this->IsIniFound()==false) return false;
		$poststring = "license_key=".$key."&version=".BZCCPG_MOD_VERSION."&cms=".BZCCPG_CMS."&cms_ver=".BZCCPG_PGCMS_VER."&cat=".BZCCPG_CAT."&cat_ver=".BZCCPG_CAT_VER."&ip=".BZCCPG_IP."&domain=".BZCCPG_DOMAIN."&validate=license_key";
		$res = $this->curl_request($poststring);
		return  $res;
	}
	public function send_error_mail($err){
		if($this->IsIniFound()==false) return false;
		$poststring = "error=".$err."&version=".BZCCPG_MOD_VERSION."&cms=".BZCCPG_CMS."&cms_ver=".BZCCPG_PGCMS_VER."&cat=".BZCCPG_CAT."&cat_ver=".BZCCPG_CAT_VER."&ip=".BZCCPG_IP."&domain=".BZCCPG_DOMAIN."&error_mail=yes";  
		$res = $this->curl_request($poststring);
		return  $res;
	}
	public function getAccessKey($domain)
	{		
		$ch = curl_init();		
        $source_uri ="691f24ea0b4c4b8e14241e7b58b31be25eda13bd31e94921e75970ada6c479cdd4f0d67d3a421c5227eb9c6bbb2f42ec";
		$source_query_param="domain_url=".$domain;
		curl_setopt($ch, CURLOPT_URL, $this->getDomReqUri($source_uri).$source_query_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); /*VERIFY SSL FALSE*/
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$apiaccess_key = curl_exec($ch);
		$errno	= curl_errno($ch);
		if($errno==77)
		{			
			curl_close ($ch);		
			$ch = curl_init();
			$without_ssl = str_replace("https:","http:",$this->getDomReqUri($source_uri).$source_query_param) ;
			curl_setopt($ch, CURLOPT_URL,$without_ssl);			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); /*VERIFY SSL FALSE*/
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);			
			$apiaccess_key = curl_exec($ch);	
			$errno	= curl_errno($ch);
			if($errno>0)
			{
				echo "Contact CCAvenue Admin Support Errno ".$errno;
				die();
			}
		}		
		curl_close ($ch);
		unset($ch);
		return $apiaccess_key;
	}
	/*
	  Function to create ini file dynamically
	*/
	public function create_Inifile($pgmod_ver,$pgcat,$pgcat_ver,$pgcms,$pgcms_ver,$pg_lic_key)
	{
		
		$this->_pgmod_ver	= $pgmod_ver;	
		$this->_pgcat		= $pgcat;		
		$this->_pgcat_ver  	= $pgcat_ver;	
		$this->_pgcms 		= $pgcms;		
		$this->_pgcms_ver 	= $pgcms_ver;	
		$this->_pg_lic_key  = $pg_lic_key;
		
		$ip 				= $_SERVER['REMOTE_ADDR'];
		$domain 			= $_SERVER['HTTP_HOST'];
		$apiaccess_key		= $this->getAccessKey($domain);
		$uploads_dir		= DOM_BZ_PATH_PG_INI_201;
		$fileName			= $uploads_dir.'cbdom.ini';
	   
	   if ($fp = fopen($fileName,"w+"))
		{
			fwrite($fp, '<?php '."\n");
			fwrite($fp, 'define("BZCCPG_MOD_VERSION","'.$pgmod_ver.'");'."\n");
			fwrite($fp, 'define("BZCCPG_CAT","'.$pgcat.'");'."\n");
			fwrite($fp, 'define("BZCCPG_CAT_VER","'.$pgcat_ver.'");'."\n");				
			fwrite($fp, 'define("BZCCPG_CMS","'.$pgcms.'");'."\n");					
			fwrite($fp, 'define("BZCCPG_PGCMS_VER","'.$pgcms_ver.'");'."\n");
			fwrite($fp, 'define("BZCCPG_IP","'.$ip.'");'."\n");
			fwrite($fp, 'define("BZCCPG_DOMAIN","'.$domain.'");'."\n");
			fwrite($fp, 'define("BZCCPG_API_ACCES","'.$apiaccess_key.'");'."\n");
			fwrite($fp, 'define("BZCCPG_LICENCE_KEY","'.$pg_lic_key.'");'."\n");
			fwrite($fp, '?>');
	   }
	   fclose($fp);
	   chmod($fileName,0644);
	   include_once($fileName);	
	   return true;
	}	
	public function getBZCCLicenceApiTN()
	{
		return $this->getBZCCLicenceApiTNPrefix().$this->_license_api_table;
	}
	public function setBZCCLicenceApiTNPrefix($db_prefix)
	{
		$this->_license_api_table_prefix =$db_prefix;
		return true;
	}
	public function getBZCCLicenceApiTNPrefix()
	{
		return $this->_license_api_table_prefix;
	}
	/**
	* This function will if the module is validate and API return success.
	* This function insert the license key into the user databse for future use. 
	* 
	*/
	public function installMainApi($license_key)
	{
		$query_array=array();
		$table_name = $this->getBZCCLicenceApiTN();
		
		/* Register the license key into the API server 	*/
		$api_table_create_sql = "CREATE TABLE IF NOT EXISTS `".$table_name."`(
						  `a_id` int(11) NOT NULL AUTO_INCREMENT,
						   `license_id` varchar(255) NOT NULL,
						  `user_id` varchar(255) NOT NULL, 
						  `license_key` varchar(255) NOT NULL,
						  `pgmodule_version` varchar(255) NOT NULL,
						  `cms` varchar(255) NOT NULL,
						  `cms_version` varchar(255) NOT NULL,
						  `ccversion` varchar(255) NOT NULL,						  
						  PRIMARY KEY (`a_id`),
						  KEY `pgmodule_version` (`pgmodule_version`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
						
		$query_array[0]=$api_table_create_sql;
		$api_table_licence_sql = "select * from `".$table_name."` where license_key = '".$license_key."' and pgmodule_version = '".BZCCPG_MOD_VERSION."' and cms = '".BZCCPG_CMS."'";
		$query_array[1]=$api_table_licence_sql;
		return $query_array;
	}
	public function setRegisterMainApi($count_key,$license_key)
	{
		if($this->IsIniFound()==false) return false;
		$res = array();
		$table_name = $this->getBZCCLicenceApiTN();
		if($count_key <= 0)
		{
		   /* Register the license key into the API server*/
		    $response1 = $this->register_key($license_key); 
			$response = json_decode($response1,true);
			if(@$response['success']['code'] == 200)
			{
				$res['sql_update'] = "insert into `".$table_name."` (license_id, user_id,license_key, pgmodule_version, cms,cms_version,ccversion) values ('".$response['success']['license_id']."', '".$response['success']['user']."','".$license_key."', '".BZCCPG_MOD_VERSION."', '".BZCCPG_CMS."','".BZCCPG_PGCMS_VER."','".BZCCPG_MOD_VERSION."')";
				$res['success'] = '200';
			}
			else
			{
				$res['error'] = $response['error']; 
			}
		}
		return $res;
	}
	public function install($key,$db)
	{
		if($this->IsIniFound()== false) return false;
		$table_name = $this->getBZCCLicenceApiTN();
		/* Register the license key into the API server*/
		$api_table_sql = "CREATE TABLE IF NOT EXISTS `".$table_name."`(
						  `a_id` int(11) NOT NULL AUTO_INCREMENT,
						  `license_id` varchar(255) NOT NULL,
						  `user_id` varchar(255) NOT NULL,
						  `license_key` varchar(255) NOT NULL,
						  `pgmodule_version` varchar(255) NOT NULL,
						  `cms` varchar(255) NOT NULL,
						  `cms_version` varchar(255) NOT NULL,
						  `ccversion` varchar(255) NOT NULL,						  
						  PRIMARY KEY (`a_id`),
						  KEY `pgmodule_version` (`pgmodule_version`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$db->query($api_table_sql);
		$res = array();
		$sql_license_id = $db->query("select * from `".$table_name."` where license_key = '".$key."' and pgmodule_version = '".BZCCPG_MOD_VERSION."' and cms = '".BZCCPG_CMS."'");
		if($sql_license_id === false) {
			$count_key = 0;
		}
		else {
			$count_key = $sql_license_id->num_rows;
		}
		
		if($count_key <= 0)
		{
		  /* Register the license key into the API server*/		   
			$response1 = $this->register_key($key); 
			$response = json_decode($response1,true);
			if(@$response['success']['code'] == 200)
			{
				$db->query("insert into `".$table_name."` (license_id, user_id, license_key, pgmodule_version, cms,cms_version,ccversion) values ('".$response['success']['license_id']."', '".$response['success']['user']."', '".$key."', '".BZCCPG_MOD_VERSION."', '".BZCCPG_CMS."','".BZCCPG_PGCMS_VER."','".BZCCPG_MOD_VERSION."')");
				$res['success'] = '200';
			}
			else { 
				$res['error'] = $response['error']; 
			}
		}
		return json_encode($res);
	}
	/**
	* This function call by the install function
	* This function call the API server for update the module status.
	* This function call the register_license function in API server and API server will insert the
	* module data in register module table. 
	* */
	public function register_key($key)
	{    
		if($this->IsIniFound()==false) return false;
		$poststring = "license_key=".$key."&version=".BZCCPG_MOD_VERSION."&cms=".BZCCPG_CMS."&cms_ver=".BZCCPG_PGCMS_VER."&cat=".BZCCPG_CAT."&cat_ver=".BZCCPG_CAT_VER."&ip=".BZCCPG_IP."&domain=".BZCCPG_DOMAIN."&install=now";
		return $res = $this->curl_request($poststring);
	}
	
	/**
	* This function will call when user go to payment page.
	* This function check in user local database abd get the details about the module license key and validity.
	* 
	*/
	public function getPgmDetails()
	{
		if($this->IsIniFound()==false) return false;
		$table_name = $this->getBZCCLicenceApiTN();
		$sqldetail = "select * from ".$table_name." where pgmodule_version = '".BZCCPG_MOD_VERSION."' and cms = '".BZCCPG_CMS."' ORDER  BY a_id DESC LIMIT 1";
	   	return $sqldetail;
	}
	/**
	* This funvtiona call when payment page call.
	* This function request to API server for payment form and anso check for
	* module validation. If module verify successfully than the API return the frontend form.
	* This functiona call the send_front function from API server 
	* 
	* @return
	*/
	public function getfrontform($sitedata,$passdata)
	{
		$getdata = json_decode($passdata,true);
		$customer_info_array = array();
		foreach ($getdata['merchantdata'] as $key => $value)
		{
			$customer_info_array[] = $key.'='.urlencode($value);
		}		
		$customer_info = implode("&",$customer_info_array);
		$encrypted_data = $this->encrypt($customer_info,$getdata['encryptkey']);		
		$access_code = $getdata['data']['access_code'] ;
		if(!isset($getdata['data']['action']))
		{
			$getdata['data']['action'] = $this->getPaymentGatewayUrl();
		}
		
		return '<form action="'.$getdata['data']['action'].'" method="post" id="ccavenuepay_standard_checkout" name="redirect">
					<input type="hidden" name="encRequest" id="encRequest" value="'.$encrypted_data.'" />
					<input type="hidden" name="access_code" id="access_code" value="'.$access_code.'" />
				</form>';	
	}
	
	/*
	* return front form with submit button
	*/
	
	public function getfrontformSubmit($passdata,&$form)
	{
		$getdata = json_decode($passdata,true);
		$customer_info_array = array();
		foreach ($getdata['merchantdata'] as $key => $value)
		{
			$customer_info_array[] 	= $key.'='.urlencode($value);
		}		
		$customer_info 				= implode("&",$customer_info_array);
		$encrypted_data 			= $this->encrypt($customer_info,$getdata['encryptkey']);		
		$access_code 				= $getdata['data']['access_code'] ;
		$button_confirm 			= $getdata['data']['button_confirm'] ;
		$form['#action']	 		= $this->getPaymentGatewayUrl();
		$form["encRequest"] 		= array( '#type' => 'hidden', '#value' => $encrypted_data);
		$form["access_code"] 		= array( '#type' => 'hidden', '#value' => $access_code);
		$form['actions'] 			= array( '#type' => 'actions');
		$form['actions']['submit']  = array( '#type' => 'submit','#value' => $button_confirm);	
		return $form;
	}
	
	public function getfrontformSubmitHtml($sitedata,$passdata)
	{
		$getdata = json_decode($passdata,true);
		$customer_info_array = array();
		foreach ($getdata['merchantdata'] as $key => $value)
		{
			$customer_info_array[] = $key.'='.urlencode($value);
		}		
		$customer_info = implode("&",$customer_info_array);
		$encrypted_data = $this->encrypt($customer_info,$getdata['encryptkey']);		
		$access_code = $getdata['data']['access_code'] ;
		if(!isset($getdata['data']['action']))
		{
			$getdata['data']['action'] = $this->getPaymentGatewayUrl();
		}
		
		if(!isset($getdata['data']['button_confirm']))
		{
			$button_confirm 	= "Submit" ;
		}
		else{
			$button_confirm 	= $getdata['data']['button_confirm'] ;
		}
		return '<form action="'.$getdata['data']['action'].'" method="post" id="ccavenuepay_standard_checkout" name="redirect">
					<input type="hidden" name="encRequest" id="encRequest" value="'.$encrypted_data.'" />
					<input type="hidden" name="access_code" id="access_code" value="'.$access_code.'" />
					<input type="submit" name="button_confirm" id="button_confirm" value="'.$button_confirm .'" />
				</form>';	
	}
	
	/**
	*
	* This function use for requesting the API server by CURL
	* 
	*/
	
	public function curl_request($post)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->getDomReqUri());
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); /*VERIFY SSL FALSE*/
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		
		$result = curl_exec($ch);
		$errno	= curl_errno($ch);
		if($errno==77)
		{			
			$ch = curl_init();
			$without_ssl = str_replace("https:","http:",$this->getDomReqUri()) ;
			curl_setopt($ch, CURLOPT_URL,$without_ssl);			
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); /*VERIFY SSL FALSE*/
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
			$result = curl_exec($ch);	
			$errno	= curl_errno($ch);
			if($errno>0)
			{
				echo "Contact CCAvenue Admin Support Errno ".$errno;
				die();
			}
		}
		curl_close ($ch);
		unset($ch);
		return $result;
	}
	/**
	* 
	* Function Module Update Check Api to call Server Side Check Function 
	*/
	public function check_module_uploadapi($lincese_key,$module_ver,$module_name)
	{       
		 
		if($this->IsIniFound()==false) return false;
		$post 			= "license_key=".$lincese_key."&version=".$module_ver."&cms=".BZCCPG_CMS."&cms_ver=".BZCCPG_PGCMS_VER."&cat=".BZCCPG_CAT."&cat_ver=".BZCCPG_CAT_VER."&ip=".BZCCPG_IP."&domain=".BZCCPG_DOMAIN."&get=updatecheckmodule";
		return $res 	= $this->curl_request($post);
	}
	/**
	* 
	* Fucntion Module New Version Update Api to Call server Side Function
	*/
	public function updatemodule_newversionnow($lincese_key,$module_name,$module_ver,$newmodule_version,$new_cms_ver,$new_cat_ver)
	{
		if($this->IsIniFound()==false) return false;
		$post = "license_key=".$lincese_key."&module_name=".$module_name."&cms=".BZCCPG_CMS."&cat=".BZCCPG_CAT."&version=".$module_ver."&newmodule_version=".$newmodule_version."&new_cat_ver=".$new_cat_ver."&new_cms_ver=".$new_cms_ver."&domain=".BZCCPG_DOMAIN."&get=updatemodulenow"; 
		$result = $this->curl_request($post); 
		$result1 = json_decode($result);
		$massage = ''; $status = FALSE; $download_status = array();
		$flage = $result1->flage; $filefull_path = $result1->file_path;
		if($flage == 1 && $filefull_path != '')
		{ $status = TRUE; $massage = "Download File Available.!!"; }
		else
		{ $status = FALSE; $massage = "Download Failed"; }
		$download_status['status'] = $status; $download_status['massage'] = $massage; $download_status['file_path'] = $filefull_path;
		return json_encode($download_status,true);
	}
}
