<?php
$GLOBALS['config'] = array('dbPath' => 'db.php');
define('PHPPREFIX', '<?php /* ');// Prefix to encapsulate data in php code.
define('PHPSUFFIX', ' */ ?>');// Suffix to encapsulate data in php code.

class linkdb {
	public $colourDictionary = array();

	// Constructor:
	function __construct() {
		$this->checkdb();// Make sure data file exists.
		$this->readdb();// Then read it.
	}

	private function checkdb()// Check if db directory and file exists.
	{
		if (!file_exists($GLOBALS['config']['dbPath']))// Create a dummy database for example.
		{
			$this->colourDictionary = array();
			file_put_contents($GLOBALS['config']['dbPath'], PHPPREFIX.base64_encode(gzdeflate(serialize($this->colourDictionary))).PHPSUFFIX);// Write database to disk
		}
	}

// Read database from disk to memory
	private function readdb() {
		// Read data
		$this->colourDictionary = (file_exists($GLOBALS['config']['dbPath'])?unserialize(gzinflate(base64_decode(substr(file_get_contents($GLOBALS['config']['dbPath']), strlen(PHPPREFIX), -strlen(PHPSUFFIX))))):array());
		// Note that gzinflate is faster than gzuncompress. See: http://www.php.net/manual/en/function.gzdeflate.php#96439
	}

// Save database from memory to disk.
	public function savedb() {
		file_put_contents($GLOBALS['config']['dbPath'], PHPPREFIX.base64_encode(gzdeflate(serialize($this->colourDictionary))).PHPSUFFIX);
	}
}
$db = new linkdb();

if (isset($_GET['entries'])) {
	echo json_encode($db->colourDictionary);
} else {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->name)) {
		$name                        = $data->name;
		$colour                      = $data->colour;
		$db->colourDictionary[$name] = $colour;
		$db->savedb();
		echo json_encode($db->colourDictionary);

	}
}

?>