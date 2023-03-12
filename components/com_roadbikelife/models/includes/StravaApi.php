<?php


/**
 * Simple PHP Library for the Strava v3 API
 *
 * @author Stuart Wilson <bonjour@iamstuartwilson.com>
 *
 * @link   https://github.com/iamstuartwilson/strava
 */

class StravaApi
{
	const BASE_URL = 'https://www.strava.com/';

	public $lastRequest;
	public $lastRequestData;
	public $lastRequestInfo;

	protected $apiUrl;
	protected $authUrl;
	protected $clientId;
	protected $clientSecret;

	private $accessToken;

	/**
	 * Sets up the class with the $clientId and $clientSecret
	 *
	 * @param   int     $clientId
	 * @param   string  $clientSecret
	 */
	public function __construct($clientId = 1, $clientSecret = '')
	{
		$this->clientId     = $clientId;
		$this->clientSecret = $clientSecret;
		$this->apiUrl       = self::BASE_URL . 'api/v3/';
		$this->authUrl      = self::BASE_URL . 'oauth/';
	}

	/**
	 * Appends query array onto URL
	 *
	 * @param   string  $url
	 * @param   array   $query
	 *
	 * @return string
	 */
	protected function parseGet($url, $query)
	{
		$append = strpos($url, '?') === false ? '?' : '&';

		return $url . $append . http_build_query($query);
	}

	/**
	 * Parses JSON as PHP object
	 *
	 * @param   string  $response
	 *
	 * @return object
	 */
	protected function parseResponse($response)
	{
		return json_decode($response);
	}

	/**
	 * Makes HTTP Request to the API
	 *
	 * @param   string  $url
	 * @param   array   $parameters
	 *
	 * @return mixed
	 */
	protected function request($url, $parameters = [], $request = false)
	{
		$this->lastRequest     = $url;
		$this->lastRequestData = $parameters;

		$curl = curl_init($url);


		if (isset($this->accessToken))
		{
			$curlOptions = [
				CURLOPT_SSL_VERIFYPEER => false,

				CURLOPT_REFERER        => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER     => [
					'Authorization: Bearer ' . $this->accessToken,
				]
			];
		}
		else
		{
			$curlOptions = [
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_REFERER        => $url,
				CURLOPT_RETURNTRANSFER => true,
			];

		}

		if (!empty($parameters) || !empty($request))
		{
			if (!empty($request))
			{
				$curlOptions[CURLOPT_CUSTOMREQUEST] = $request;
				$parameters                         = http_build_query($parameters);
			}
			else
			{
				$curlOptions[CURLOPT_POST] = true;
			}

			$curlOptions[CURLOPT_POSTFIELDS] = $parameters;
		}

		curl_setopt_array($curl, $curlOptions);

		$response = curl_exec($curl);
		$error    = curl_error($curl);

		$this->lastRequestInfo = curl_getinfo($curl);

		curl_close($curl);

		if (!$response)
		{
			return $error;
		}
		else
		{
			return $this->parseResponse($response);
		}
	}

	/**
	 * Creates authentication URL for your app
	 *
	 * @param   string  $redirect
	 * @param   string  $approvalPrompt
	 * @param   string  $scope
	 * @param   string  $state
	 *
	 * @return string
	 * @link http://strava.github.io/api/v3/oauth/#get-authorize
	 *
	 */
	public function authenticationUrl($redirect, $approvalPrompt = 'auto', $scope = null, $state = null)
	{
		$parameters = [
			'client_id'       => $this->clientId,
			'redirect_uri'    => $redirect,
			'response_type'   => 'code',
			'approval_prompt' => $approvalPrompt,
			'state'           => $state,
		];

		if (!is_null($scope))
		{
			$parameters['scope'] = $scope;
		}

		return $this->parseGet(
			$this->authUrl . 'authorize',
			$parameters
		);
	}

	/**
	 * Authenticates token returned from API
	 *
	 * @param   string  $code
	 *
	 * @return string
	 * @link http://strava.github.io/api/v3/oauth/#post-token
	 *
	 */
	public function tokenExchange($code)
	{
		$parameters = [
			'client_id'     => $this->clientId,
			'client_secret' => $this->clientSecret,
			'code'          => $code,
			''
		];

		return $this->request(
			$this->authUrl . 'token',
			$parameters
		);
	}

	public function refreshToken($token)
	{
		$parameters = [
			'client_id'     => $this->clientId,
			'client_secret' => $this->clientSecret,
			'refresh_token' => $token,
			'grant_type'    => 'refresh_token',

		];

		$response = $this->request(
			$this->authUrl . 'token',
			$parameters
		);

		$this->accessToken = $response->access_token;

		return $response;
	}


	/**
	 * Deauthorises application
	 *
	 * @link http://strava.github.io/api/v3/oauth/#deauthorize
	 *
	 * @return string
	 */
	public function deauthorize()
	{
		return $this->request(
			$this->authUrl . 'deauthorize', []
		);
	}

	/**
	 * Sets the access token used to authenticate API requests
	 *
	 * @param   string  $token
	 */
	public function setAccessToken($token)
	{
		return $this->accessToken = $token;
	}

	/**
	 * Sends GET request to specified API endpoint
	 *
	 * @param   string  $request
	 * @param   array   $parameters
	 *
	 * @return string
	 * @example http://strava.github.io/api/v3/athlete/#koms
	 *
	 */
	public function get($request, $parameters = [])
	{

		$requestUrl = $this->parseGet($this->apiUrl . $request, $parameters);

		return $this->request($requestUrl);
	}

	/**
	 * Sends PUT request to specified API endpoint
	 *
	 * @param   string  $request
	 * @param   array   $parameters
	 *
	 * @return string
	 * @example http://strava.github.io/api/v3/athlete/#update
	 *
	 */
	public function put($request, $parameters = [])
	{
		return $this->request(
			$this->apiUrl . $request, $parameters,
			'PUT'
		);
	}

	/**
	 * Sends POST request to specified API endpoint
	 *
	 * @param   string  $request
	 * @param   array   $parameters
	 *
	 * @return string
	 * @example http://strava.github.io/api/v3/activities/#create
	 *
	 */
	public function post($request, $parameters = [])
	{

		return $this->request(
			$this->apiUrl . $request, $parameters
		);
	}

	/**
	 * Sends DELETE request to specified API endpoint
	 *
	 * @param   string  $request
	 * @param   array   $parameters
	 *
	 * @return string
	 * @example http://strava.github.io/api/v3/activities/#delete
	 *
	 */
	public function delete($request, $parameters = [])
	{
		return $this->request(
			$this->apiUrl . $request, $parameters,
			'DELETE'
		);
	}


}
