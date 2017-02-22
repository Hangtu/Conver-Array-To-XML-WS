<?php
#Solo se hace un set de tipomovimiento, Farmacia y Fecha. Los demas valores son calculados en un metodo
ini_set("soap.wsdl_cache_enabled", 0);
include_once("/XML/serializer.php");

class Test{

	private $arrayXML = array('row' => array());

	private function obtenerValoresWS($value){
		if($sql!="ANY SQL"){
			$res = $this->db->query($sql);
			if ($this->db->num_rows($res) > 0){
				while ($row = $this->db->fetch_assoc($res)) {
					array_push($this->claveMovimiento['row'], array(
						"field" => $row['value'],
						"field" => $row['value'],
						"field" => $row['value'],
						"field" => round($row['value'],2),
						"field" => $row['value']
						));
				}
			}
		}
	}


	public function TestWS(){
		
		$this->obtenerValoresWS();

		$opts = array('ssl' => array('ciphers'   => 'RC4-SHA', 'verify_peer'=> false, 'verify_peer_name' => false));
		$opts = array ('encoding' => 'UTF-8',
			'verifypeer' => false,
			'verifyhost' => false,
			'soap_version' => SOAP_1_1,
			'trace' => 1,
			'exceptions' => 1,
			"connection_timeout" => 180,
			'stream_context' => stream_context_create($opts));
		$wsdl = "wsdl";//wsdl file
		$client = new SoapClient($wsdl,$opts);

		try{
			$params = new stdClass();
			$params->xmlDetalle = $this->generaXML();
			$response = $client->testWS($params);

			echo "<pre>";
			print_r($params);
			echo "</pre>";

			echo "<pre>";
			print_r($response);
			echo "</pre>";
		}
		catch (SoapFault $e){
			echo "<pre>";
			echo "Error: {$e}";
			echo "</pre>";
		}
	}


	private  function generateXML(){
		$options = array(
			XML_SERIALIZER_OPTION_INDENT       => '  ',
			XML_SERIALIZER_OPTION_ROOT_NAME    => 'root',
			XML_SERIALIZER_OPTION_MODE         => XML_SERIALIZER_MODE_SIMPLEXML
			);
		$serializer = new XML_Serializer($options);
		$result =  $serializer->serialize($this->claveMovimiento);
		$result =  $serializer->getSerializedData();			
		return $result;
	}
}

?>