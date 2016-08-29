<?php

/**
 * ReportingAPI
 * 
 * @author Marcel Bobak <marcel.bobak@gmail.com>
 */

namespace App\Models;

use Exception;

final class ReportingAPI
{
	/**
	 * Class instance
	 * @var ReportingAPI 
	 */
	private static $_instance;
	
	/**
	 * API token
	 * @var string
	 */
	private $_token;
	
	/**
	 * Token create time
	 * @var int
	 */
	private $_tokenTiemstamp;
	
	/**
	 * API login email
	 * @var string
	 */
	private $_email;
	
	/**
	 * API login password
	 * @var string
	 */
	private $_passwd;
	
	/**
	 * API base URL
	 * @var string
	 */
	private $_baseUrl;
	
	/**
	 * API login URL
	 * @var string
	 */
	private $_loginUrl;
	
	
	/**
	 * API reponse
	 * @var stdClass 
	 */
	private $_reponse;

	private function __construct()
	{
		// Do nothing
	}
	
	public function __clone()
	{
		throw new Exception("Copy of class ".__CLASS__." is not allowed !");
	}
	
	/**
	 * Get class instances
	 * 
	 * @return ReportingAPI
	 */
	public static function getInstance()
	{
		if ( !isset(self::$_instance) )
		{
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	/**
	 * Set-up basic variables
	 * 
	 * @param string $baseUrl
	 * @param string $email
	 * @param string $passwd
	 * @param string $loginUrl
	 * 
	 * @return \App\Models\ReportingAPI
	 */
	public function setUp($baseUrl, $email, $passwd, $loginUrl)
	{
		$this->setBaseUrl($baseUrl);
		$this->setEmail($email);
		$this->setPasswd($passwd);
		$this->setLoginUrl($loginUrl);
		
		return $this;
	}
	
	/**
	 * Set email
	 * 
	 * @param string $email
	 * @return \App\Models\ReportingAPI
	 */
	public function setEmail($email)
	{
		$this->_email = $email;
		return $this;
	}
	
	/**
	 * Get email
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return $this->_email;
	}
	
	/**
	 * Set password
	 * 
	 * @param type $passwd
	 * @return \App\Models\ReportingAPI
	 */
	public function setPasswd($passwd)
	{
		$this->_passwd = $passwd;
		return $this;
	}
	
	/**
	 * Get password
	 * 
	 * @return string
	 */
	public function getPasswd()
	{
		return $this->_passwd;
	}

	/**
	 * Set API base URL
	 * 
	 * @param string $url
	 * @return \App\Models\ReportingAPI
	 */
	public function setBaseUrl($url)
	{
		$this->_baseUrl = $url;
		return $this;
	}
	
	/**
	 * Set API login URL
	 * 
	 * @param string $url
	 * @return \App\Models\ReportingAPI
	 */
	public function setLoginUrl($url)
	{
		$this->_loginUrl = $url;
		return $this;
	}
	
	/**
	 * Get API login URL
	 * 
	 * @return string
	 */
	public function getLoginUrl()
	{
		return $this->_loginUrl;
	}
	
	/**
	 * Set API Token
	 * 
	 * @param string $token
	 * @return \App\Models\ReportingAPI
	 */
	public function setToken($token)
	{
		$this->_token = $token;
		$this->_tokenTiemstamp = time();
		return $this;
	}

	/**
	 * Get token
	 * 
	 * @return string
	 */
	public function getToken()
	{
		return $this->_token;
	}
	
	/**
	 * Get API reponse
	 * 
	 * @return type\stdClass
	 */
	public function getResponse()
	{
		return $this->_reponse;
	}
	
	/**
	 * Get reponse body<br>
	 * Throws an exception if reponse body is not set or empty
	 * 
	 * @param boolean $assoc [optional] Default: FALSE<br>
	 * When TRUE returned object will be converted into associative array
	 * 
	 * @return array|object JSON
	 * @throws Exception
	 */
	public function getResponseBody($assoc = false)
	{
		$return_json = array();
		
		if ( null === $this->_reponse->response->body )
		{
			throw new \Exception("Could not resolve response object");
		}
		
		if ( isset($this->_reponse->response->body) )
		{
			$return_json = json_decode($this->_reponse->response->body, $assoc);
		}
		
		return $return_json;
	}
	
	/**
	 * Login to API<br>
	 * Throws an exception if returned HTTP status code not equals to 200
	 * 
	 * @return \App\Models\ReportingAPI 
	 * @throws Exception 
	 */
	public function login()
	{
		$response = $this->post($this->_loginUrl)->getResponse();
		$http_status = $response->response->http_status;

		if ( 'HTTP/1.1 200 OK' == $http_status )
		{
			$token = json_decode($response->response->body, true)['token'];
			$this->setToken($token);
		}
		else
		{
			throw new \Exception("[Error] Could not get token as HTTP Status Code is : {$http_status}");
		}
		
		return $this;
	}
	
	/**
	 * Relogin to API and get token if token already exists more than 10 minutes
	 * 
	 * @return string|\App\Models\ReportingAPI 
	 */
	public function keepAlive()
	{
		$tDiff = time() - $this->_tokenTiemstamp;
		
		$t = date('i', $tDiff);
		//var_dump('keepAlive -> '.$tDiff);
		
		// relogin each 10 minutes
		$return = $this;
		if ( 10 < $t )
		{
			$return = $this->login();
		}
		
		return $return;
	}
	
	/**
	 * Execute POST query on API
	 * 
	 * @param string $api_interface_url API URL
	 * @param array $params [optional] parameters
	 * @param array $headers [optional] headers
	 * 
	 * @return \App\Models\ReportingAPI
	 */
	public function post($api_interface_url, $params = [], $headers = [])
	{
		$this->_reponse = $this->_execute('POST', $api_interface_url, $params, $headers);
		return $this;
	}
	
	/**
	 * Execute GET query on API
	 * 
	 * @param string $api_interface_url API URL
	 * @param array $params [optional] parameters
	 * @param array $headers [optional] headers
	 * 
	 * @return arrayApp\Models\ReportingAPI
	 */
	public function get($api_interface_url, $params = [], $headers = [])
	{
		$this->_reponse = $this->_execute('GET', $api_interface_url, $params, $headers);
		return $this;
	}
	
	/**
	 * Execute query on API
	 * 
	 * @param string $method POST|GET
	 * @param string $api_interface_url API URL
	 * @param array $parameters [optional] parameters
	 * @param array $headers [optional] headers
	 * 
	 * @return \stdClass API response
	 */
	protected function _execute($method, $api_interface_url, $parameters = [], $headers = [])
	{
		$method = strtolower($method);
		
		$s = '';
		// make sure there is '/' between both URLs
		if ( '/' !== substr($this->_baseUrl, -1) && 0 === strpos($api_interface_url, '/') )
		{
			$s = '/';
		}
		
		$api_url = $this->_baseUrl . $s . $api_interface_url;
		
        $curlopt = [
            CURLOPT_HEADER => true, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_USERAGENT => 'Reporting API',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
        ];
		
		$parameters_string = '';
		if( is_array($parameters) )
		{
            $parameters_string = $this->_formatQuery($parameters);
        }
        else
		{
            $parameters_string = (string) $parameters;
		}
		
		if ( 'post' === $method )
		{
			$curl_post_data = array(
				"email" => $this->_email,
				"password" => $this->_passwd,
			);

			$curlopt[CURLOPT_POST] = true;
			$curlopt[CURLOPT_POSTFIELDS] = $this->_formatQuery($curl_post_data);
		}
		
		if ( !empty($parameters_string) )
		{
			$api_url .= ( false !== strpos($api_url, '?') ) ? '&' : '?';
			$api_url .= $parameters_string;
		}

		if( true === is_array($headers) && 0 < count($headers) )
		{
            $curlopt[CURLOPT_HTTPHEADER] = [];
            foreach($headers as $key => $value)
			{
				$curlopt[CURLOPT_HTTPHEADER][] = sprintf("%s:%s", $key, $value);
            }
        }
		
		$curlopt[CURLOPT_URL] = $api_url;
		
		$curl = curl_init();
		curl_setopt_array($curl, $curlopt);
		
		$responseObj = new \stdClass();
		$responseObj->response = $this->_parseResponse(curl_exec($curl));
        $responseObj->info = (object) curl_getinfo($curl);
        $responseObj->error = curl_error($curl);

		curl_close($curl);
		
		return $responseObj;
	}
	
	/**
	 * Format query
	 * 
	 * @param array $parameters array of parameters
	 * @param string $primary [optional] key value delimiter
	 * @param string $secondary [optional] concatenated by
	 * 
	 * @return string formatted string
	 */
	private function _formatQuery(array $parameters, $primary='=', $secondary='&')
	{
        $query = "";
		
        foreach($parameters as $key => $value)
		{
			$pair = [urlencode($key), urlencode($value)];
			$query .= implode($primary, $pair) . $secondary;
        }
		
        return rtrim($query, $secondary);
    }
	
	/**
	 * Parse response
	 * 
	 * @param string $response
	 * @return \stdClass parsed response
	 */
	private function _parseResponse($response)
	{
		$responseObj = new \stdClass();
		$responseObj->http_status = null;
		$responseObj->headers = array();
		$responseObj->body = null;
		
        $row = strtok($response, "\n");
		$headers_arr = [];

		$i=0;
        do {
            $row = trim($row);
			$aux_header = explode(':', $row, 2);
			
			if ( empty($row) )
			{
				continue;
			}
			
			if ( false !== strpos($row, 'HTTP') )
			{
				$responseObj->http_status = $row;
			}
			elseif ( false === strpos($row, '{') && !empty($aux_header) && 2 == count($aux_header) )
			{
				$key = trim($aux_header[0]);
				$key = str_replace('-', '_', $key);
				$value = trim($aux_header[1]);
				
				$headers_arr[$key] = $value;
			}
			elseif ( 0 === strpos($row, '{') )
			{
				$responseObj->body = $row;
			}
			
        } while($row = strtok("\n"));
		
		if ( !empty($headers_arr) )
		{
			$responseObj->headers = (object)$headers_arr;
		}
		
		return $responseObj;
    }
	
}