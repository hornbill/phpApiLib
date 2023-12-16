<?php
namespace Esp;

class Transport
{

	protected $_tServer;
	protected $_tXmlmc;
	protected $_tDav;
	protected $_tWs;

	//function designed to handle new API endpoint config
	private function setApiEndpoints($server)
	{

		$this->_tServer = $server;
		$this->_tXmlmc  = $server . "xmlmc";
		$this->_tDav    = $server . "dav";
		$this->_tWs		= $server . "ws";

		$splitServerURL = explode("/", $server);
		$index = count($splitServerURL) - 2; //Grabs the index of the servername
		$instanceName = $splitServerURL[$index]; 
		$url = "https://files.hornbill.com/instances/$instanceName/zoneinfo";

		$response = file_get_contents($url);

		if ($response !== false) 
		{
			$decodedResponse = json_decode($response);
			
			// Process the response
			if (isset($decodedResponse->zoneinfo->apiEndpoint))
			{
				$this->_tXmlmc  = $decodedResponse->zoneinfo->apiEndpoint;
				$this->_tDav  = $decodedResponse->zoneinfo->davEndpoint;
				$this->_tWs  = $decodedResponse->zoneinfo->wsEndpoint;
			}
		} 
	}

	public function __construct($server)
	{
		$this->setApiEndpoints($server);
	}

  public function getAPIPath()
	{
		return $this->_tXmlmc;
	}

	public function getDavPath()
	{
		return $this->_tDav;
	}

	public function getWsPath()
	{
		return $this->_tWs;
	}
}
