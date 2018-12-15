<?php
namespace App\Support ;

include dirname( dirname( __FILE__) ) . '/aliyunmns/mns-autoloader.php';
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

class Sms {
	//帐号
	public $accessKeyId = '';
	//密码
	public $accessKeySecret  = "";
	//企业签名
	public $SignName = '';
	public $target =  "https://sms.aliyuncs.com/?";
	
	protected $client ;
	

	public function __construct( $accessKeyId , $accessKeySecret , $signName ) {
		$this->accessKeyId = $accessKeyId ;
		$this->accessKeySecret = $accessKeySecret ;
		$this->SignName = $signName ;
	}
	
	public function sdkSend( $mobile , $params , $tpl ) {

		/**
		 * Step 1.
		 * 初始化Client
		 */
		$endPoint = "http://1402890174050976.mns.cn-hangzhou.aliyuncs.com";
		
		$this->client = new Client ( $endPoint ,  $this->accessKeyId , $this->accessKeySecret );
		/**
		 * Step 2.
		 * 获取主题引用
		 */
		$topicName = "sms.topic-cn-hangzhou";
		$topic = $this->client->getTopicRef ( $topicName );
		/**
		 * Step 3.
		 * 生成SMS消息属性
		 */
		//$message = new SmsAttributes( $this->SignName , $tpl , $params , $mobile );
		// 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
		$batchSmsAttributes = new BatchSmsAttributes (  $this->SignName ,  $tpl );
		// 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
		$batchSmsAttributes->addReceiver (  $mobile , $params );
		$messageAttributes = new MessageAttributes ( array (
				$batchSmsAttributes 
		) );
		/**
		 * Step 4.
		 * 设置SMS消息体（必须）
		 *
		 * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
		 */
		$messageBody = "smsmessage";
		/**
		 * Step 5.
		 * 发布SMS消息
		 */
		
		$request = new PublishMessageRequest ( $messageBody, $messageAttributes );
		try {
			$res = $topic->publishMessage ( $request );
			return $res->isSucceed ();
		} catch ( MnsException $e ) {
			return false ;
		}
	
		
		
	}
	
	private function https_request($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
		curl_close($curl);
		return $data;
	}
	private function xml_to_array($xml){
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
				$subxml= $matches[2][$i];
				$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = $this->xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		return @$arr;
	}


	function percentEncode($str) {
		// 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范
		$res = urlencode($str);
		$res = preg_replace('/\+/', '%20', $res);
		$res = preg_replace('/\*/', '%2A', $res);
		$res = preg_replace('/%7E/', '~', $res);
		return $res;
	}

	private function computeSignature($parameters, $accessKeySecret)
	{
		// 将参数Key按字典顺序排序
		ksort($parameters);
		// 生成规范化请求字符串
		$canonicalizedQueryString = '';
		foreach($parameters as $key => $value)
		{
			$canonicalizedQueryString .= '&' .  $this->percentEncode($key)
				. '=' .  $this->percentEncode($value);
		}
		// 生成用于计算签名的字符串 stringToSign
		$stringToSign = 'GET&%2F&' .  $this->percentencode(substr($canonicalizedQueryString, 1));
		//echo "<br>".$stringToSign."<br>";
		// 计算签名，注意accessKeySecret后面要加上字符'&'
		$signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
		return $signature;
	}
	
	public function send( $mob , $params , $tpl ) {
		header("Content-type:text/html; charset=UTF-8");
		// 注意使用GMT时间
		date_default_timezone_set("GMT");
		$dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601规范
		$ParamString = "{";
		foreach( $params as $k => $val ) {
			$ParamString .= "\"{$k}\":\"{$val}\"";
		}
		$ParamString .="}";
		$data = array(
				// 公共参数
				'SignName'=>$this->SignName,
				'Format' => 'XML',
				'Version' => '2016-09-27',
				'AccessKeyId' => $this->accessKeyId,
				'SignatureVersion' => '1.0',
				'SignatureMethod' => 'HMAC-SHA1',
				'SignatureNonce'=> uniqid(),
				'Timestamp' => date($dateTimeFormat),
				// 接口参数
				'Action' => 'SingleSendSms',
				'TemplateCode' => $tpl,
				'RecNum' => $mob,
				'ParamString' => $ParamString
		);
		
		$data['Signature'] = $this->computeSignature($data, $this->accessKeySecret);
		$response = $this->https_request($this->target.http_build_query($data)) ;
		$result =  $this->xml_to_array( $response );
		return $result;
	}

}