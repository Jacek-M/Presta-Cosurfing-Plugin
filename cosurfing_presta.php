<?php

if (!defined('_PS_VERSION_'))
    exit;


class LangParser
{
	private static $currentLang;

	public static function loadLanguage()
	{
		$dirloc = substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['HOME'])).'/modules/cosurfing_presta';
		$xml = simplexml_load_file($dirloc.'/languages/current.xml');
		self::$currentLang = $xml->{'lang'};
	}

	public static function getCurrentLang()
	{
		return self::$currentLang;
	}

	public static function changeLang($param)
	{
		
		$dirloc = substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['HOME'])).'/modules/cosurfing_presta/languages/current.xml';
		$file = fopen($dirloc, 'w');

		if($param == 'pl')
		{
			$temp = '<?xml version="1.0" encoding="UTF-8"?><current><lang><![CDATA[pl]]></lang></current>';
			fwrite($file, $temp);
		}
			
		else if($param == 'en')
		{
			$temp = '<?xml version="1.0" encoding="UTF-8"?><current><lang><![CDATA[en]]></lang></current>';
			fwrite($file, $temp);
		}
		fclose($file);
	}

	public static function getTranslation($element)
	{
		$dirloc = substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['HOME'])).'/modules/cosurfing_presta';
		if(self::$currentLang == 'pl')
		{
			$xml = simplexml_load_file($dirloc.'/languages/pl.xml');
			return $xml->{$element};
		}
		else // eng
		{
			$xml = simplexml_load_file($dirloc.'/languages/en.xml');
			return $xml->{$element};
		}
	}
}


class cosurfing_presta extends Module
{
	public static $done = 0;
	private $_FORM = '';
	private $DATA_TOKEN = '';

	public function __construct() 
	{

		LangParser::loadLanguage();
		$this->name = 'cosurfing_presta';
		$this->tab = 'others';
		$this->version = '1.0.3';
		$this->author = 'Karol Miksztal & Jacek Moczydło';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
		$this->bootstrap = true;

		parent::__construct();
		$this->displayName = $this->l('cosurfing_presta');
		$this->description = $this->l(LangParser::getTranslation('description'));
		$this->confirmUninstall = $this->l(LangParser::getTranslation('confirmUninstall'));

	}

	public function getEmailAndPageUrl()
	{
		global $cookie;
		$email = $cookie->email;
		$site = _PS_BASE_URL_;
		return 'email='.$email.'&ampsite='.$site.'&ampfrom=prestashop';
	}

	public function createTokenForm()
	{
		if (isset($_GET['cslan']) && ($_GET['cslan'] == 'pl')) 
    		LangParser::changeLang('pl');
  		if (isset($_GET['cslan']) && ($_GET['cslan'] == 'en'))  
    		LangParser::changeLang('en'); 

		LangParser::loadLanguage();
		$this->_FORM .= '
						<div id="container">
							<div id="logo">
								<a href="http://cosurfing.net/"> <img src="https://cosurfing.net/assets/img/logoCosurfingBlackBg.png" width="250" height="70" /> </a>
							</div>
							<div id="haaa">
            					'.LangParser::getTranslation('title').'
          					</div>
          					<div id="datatoken">
								<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
								'.$this->l(LangParser::getTranslation('label1')).'
								<div id="textField"><input type="text" name="token_input" value="" /> </div>
								<br>
								<input type="submit" name="submit_token"
								value="'.$this->l(LangParser::getTranslation('button')).'" class="" />
								<br>
								</form>
							</div>
							<div id="link">
								<br>
								<br>
								<a class="one" href="https://cosurfing.net/cpanel/index.html?'.$this->getEmailAndPageUrl().'">'.LangParser::getTranslation('link').'</a>
							</div>
							<div id="monitor">
								<div id="monitorH">
									'.LangParser::getTranslation('monitor').'
								</div>
								<div id="monitorB">
									<div class="subDoodleContainer">
										<img src="http://cosurfing.net/assets/img/subpages/screens.png" alt=""  id="screens" >
										<div id="subVscreen1" class="subVscreen">
											<img src="http://cosurfing.net/assets/img/cursor_ico.png" id="cursor1" class="vcursor" />
										</div>
										<div id="subVscreen2"   class="subVscreen">
											<img src="http://cosurfing.net/assets/img/cursor_ico.png" id="cursor2"  class="vcursor"/>
										</div>
										<div id="subVlinker" class="subVlinker">
										</div>
									</div>
									<script src="http://cosurfing.net/js/agency.js"></script>
									<script src="https://cosurfing.net/assets/js/jquery-1.10.2.min.js"></script>
									<script src="https://cosurfing.net/assets/js/bootstrap.js"></script>
									<link href="https://cosurfing.net/assets/css/magic-bootstrap-cosurfing.min.css" rel="stylesheet">
									<link rel="https://cosurfing.net/assets/css/magic-bootstrap-cosurfing.less" type="text/css" href="style.less">
									<script src="https://cosurfing.net/assets/js/script.js" type="text/javascript"></script>
							</div>
							<div id="footerr">
	            				'.LangParser::getTranslation('footer').'
	          				</div>
	          				</div>
				          	<div id="footerAll">
				            	<div id="footerLeft">
				              	<span id="span1">WebLive Systems</span><br><br>  

				              	Phone: +48 602 885 891<br> 
				              	Fax: +48 022 247-82-57<br> 
				              	info@weblive.com.pl<br>   
				              	www: weblive.com.pl<br><br>   

				             	NIP : 664-201-48-34<br> 
				              	REGON : 260618454<br> 
				            </div>
				            <div id="footerRight">
				              <span id="span1">'.LangParser::getTranslation('language').'</span><br><br>
				              <table id="foot">
				                <tr>
				                  <td>
				                    <img src="https://cosurfing.net/assets/img/flagaPolski.gif" width="22" height="13">
				                  </td>
				                  <td>
				                    <a class="one" href="'.$_SERVER["REQUEST_URI"].'&cslan=pl">Polski</a>
				                  </td>
				                </tr>
				                <tr>
				                  <td>
				                    <img src="https://cosurfing.net/assets/img/flagaBrytania.gif" width="22" height="13">
				                  </td>
				                  <td>
				                    <a class="one" href="'.$_SERVER["REQUEST_URI"].'&cslan=en">English</a>
				                  </td>
				                </tr>
				              </table>
				            </div>
				            <br class="clear" />
				          </div>
	          			</div>
	          			<style>
	          				a.one:link {color: #50C246;}
							a.one:visited {color: #50C246;}
							a.one:hover {color: green;}
	          				#container{width: 800px; margin-left: auto; margin-right: auto;}
				          	#logo{background-color: #393E42; text-align: center;}
				          	#haaa{margin-top: 30px; margin-bottom: 30px; height: 28px; font-size: 28px; color: black; text-align: center; font-weight: bold; border-bottom: 2px dotted #D8E0E6;}
							#datatoken{text-align : center; font-size: 20px; margin-top: 30px; margin-bottom: 60px; resize: horizontal; width: 200px; height: 50px; margin-left: auto; margin-right: auto;}
				          	#subButton{text-align : center; font-size: 16px;}
				          	#link{font-size: 16px; padding: 1px; text-align: center; margin-bottom: 10px; border-bottom: 2px dotted #D8E0E6;}
				          	#monitor{text-align: center;background-color: #D8E0E6;min-height: 100px;}
				          	#monitorH{color: black;font-size: 25px;font-weight: bold;padding: 5px;margin-top: 5px;margin-bottom: 10px;}
				          	#monitorB{text-align: center;}
				          	#footerr{background-color: #393E42; clear: both; color: white; text-align: center; padding: 10px; font-size: 12px;}
	          				#lang1{font-size: 12px; float: left; font-weight: bold;}
	          				#lang2{font-size: 12px; float: left; font-weight: bold;}
	          				#textField{margin-top: 20px; margin-bottom: 10px;}
	          				#footerAll
							{
								border-top: 3px solid #1CAD32;
								padding: 15px;
								background-color: #393E42;
								color: white;
							}
							#footerLeft
							{
								color: #ABABAB;
								margin-left: 15%;
								float: left;
								width: 30%;
								text-align: center;
							}
							#footerRight
							{
								margin-right: 15%;
								float: right;
								width: 30%;
								text-align: center;
							}
							.clear 
							{
						   		clear: both;
							}
							#span1
							{
								color: white;
								font-size: 15px;
							}
							table#foot
							{
								text-align: center;
								margin-left: 70px;
							}
							#foot td
							{
								padding: 5px;
								text-align: left;
							}
						</style>
						';

	}

	public function getContent() // dzieki tej funkcji pojawia sie "KONFIGURUJ" w panelu admina dla danego plugina
	{
		LangParser::loadLanguage();
		if(Tools::isSubmit('submit_token'))
		{
			$this->DATA_TOKEN = Configuration::get('DATA_TOKEN');
			$this->deleteJava($this->DATA_TOKEN); // usuwam stary jakby byl
			Configuration::updateValue('DATA_TOKEN', Tools::getValue('token_input'));
			$this->DATA_TOKEN = Configuration::get('DATA_TOKEN');
			$this->addJavaScriptCode($this->DATA_TOKEN); // dodaje nowy do footer.tpl
			$this->_FORM .= $this->displayConfirmation($this->l(LangParser::getTranslation('newtoken')));
		}
		if(Tools::isSubmit('PL'))
		{
			LangParser::changeLang('pl');
		}
		if(Tools::isSubmit('ENG'))
		{
			LangParser::changeLang('en');
		}
		$this->createTokenForm();
		return $this->_FORM;
	}

	

	public function install()
	{
		$iso_code = $this->context->language->iso_code;
		if(strpos($iso_code, 'pl') !== false)
			LangParser::changeLang('pl');
		else if(strpos($iso_code, 'en') !== false)
			LangParser::changeLang('en');
		LangParser::loadLanguage();
		$this->getContent();


		if (parent::install() == false)
    		return false;
 		return true;
	}

	public function uninstall()
	{
		$this->DATA_TOKEN = Configuration::get('DATA_TOKEN');
		$this->deleteJava($this->DATA_TOKEN);
  		if (!parent::uninstall() || !Configuration::deleteByName('cosurfing_presta') || !Configuration::deleteByName('token_input'))
    		return false;
  		return true;
	}

	public function GoToLine($handle,$line)
	{
	  fseek($handle,0); 
	  $i = 0;
	  $bufcarac = 0;                    

	  for($i = 1;$i <$line; $i++)
	  {
	    $ligne = fgets($handle);
	    $bufcarac += strlen($ligne); 
	  }  

	  fseek($handle,$bufcarac);
	}

	public function deleteJava($token)
	{
		$themeDir = _THEME_DIR_;
		$DELETE = '<script src="https://cosurfing.s3.amazonaws.com/cosurfing.nocache.js" data-token="'.$token.'" id="cosurfing_net_id"></script><!--DODANE-->';
		$dirloc = substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['HOME'])).$themeDir.'/footer.tpl' ;
		$data = file($dirloc);

		$out = array();

		 foreach($data as $line) 
		 {
		     if(trim($line) != $DELETE) 
		     {
		         $out[] = $line;
		     }
		 }

		 $fp = fopen($dirloc, "w+");
		 flock($fp, LOCK_EX);
		 foreach($out as $line) 
		 {
		     fwrite($fp, $line);
		 }
		 flock($fp, LOCK_UN);
		 fclose($fp);
	}

	public function addJavaScriptCode($token)
	{
		$themeDir = _THEME_DIR_;
		$dirloc = substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['HOME'])).$themeDir."/footer.tpl";
		$addJs = '<script src="https://cosurfing.s3.amazonaws.com/cosurfing.nocache.js" data-token="'.$token.'" id="cosurfing_net_id"></script>';
		$currLine = 0;
		$file = file($dirloc);
		foreach ($file as $line) 
		{
			if(strpos($line, '<!--DODANE-->') !== false)
				$done = 1;
			else if(strpos($line, '</body>') !== false)
				break;
			else $currLine++;
		}
		if($currLine < 1) 
			exit;
		
		if($done < 1)
		{
			
			$openFile = fopen($dirloc, "r+");
			$this->GoToLine($openFile, $currLine+1);
			fwrite($openFile, PHP_EOL);
			fwrite($openFile, $addJs);
			fwrite($openFile, '<!--DODANE-->');
			fwrite($openFile, PHP_EOL);
			fwrite($openFile, '</body>');
			fwrite($openFile, '</html>');
			fclose($openFile);
		}
	}
}

?>