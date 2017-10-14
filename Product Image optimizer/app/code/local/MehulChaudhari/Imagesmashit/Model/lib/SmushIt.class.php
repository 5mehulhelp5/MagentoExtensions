<?php

/**
 * Smush.it PHP Library, a simple PHP library for accessing the Yahoo! Smush.it™
 * lossless image compressor
 * @author  Ghislain PHU <contact@ghislainphu.fr>
 * @version 1.0
 */
class SmushIt
{
	/**
	 * Flag used to preserve objects with errors
	 */
	const KEEP_ERRORS = 0x01;

	/**
	 * Flag used to throw exceptions when an error
	 * occurred
	 */
	const THROW_EXCEPTION = 0x02;

	/**
	 * Internal flag used to notify that the current
	 * source is a local file
	 */
	const LOCAL_ORIGIN = 0x04;

	/**
	 * Internal flag used to notify that the current
	 * source is a remote file (source is an URL)
	 */
	const REMOTE_ORIGIN = 0x08;

	/**
	 * The base URL of the Yahoo! Smush.it™ API
	 */
	const SERVICE_API_URL = "http://api.resmush.it/ws.php";

	/**
	 * Maximum filesize allowed by Yahoo! Smush.it™ API
	 */
	const SERVICE_API_LIMIT = 1048576; // 1MB limitation

	/**
	 * Error message
	 * @access public
	 * @var string | null
	 */
	public $error;

	/**
	 * Source URI
	 * @access public
	 * @var string | null
	 */
	public $source;

	/**
	 * Compressed image URL
	 * @access public
	 * @var string | null
	 */
	public $destination;

	/**
	 * Filesize of the source image (in Bytes)
	 * @access public
	 * @var int | null
	 */
	public $sourceSize;

	/**
	 * Filesize of the compressed image (in Bytes)
	 * @access public
	 * @var int | null
	 */
	public $destinationSize;

	/**
	 * Saving percentage
	 * @access public
	 * @var float | null
	 */
	public $savings;

	/**
	 * Effective flags
	 * @access private
	 * @var int | null
	 */
	private $flags = null;

	/**
	 * Array of SmushIt objects
	 * @access private
	 * @var array
	 */
	private $items = array();

	/**
	 * Smush.it constructor
	 * @access public
	 * @param  array | string           $sources List of files to compress
	 * @param  int                      $flags   List of flags
	 * @return object
	 * @see    SmushIt::KEEP_ERRORS
	 * @see    SmushIt::THROW_EXCEPTION
	 */
	public function __construct($sources, $flags = null)
	{
		$this->flags = $flags;
		$sources = $this->clean($sources);

		if (is_string($sources)) {
			if ($this->check($sources)) {
				$this->smush();
			}
		} else {
			foreach($sources as $source) {
				$smush = new SmushIt($source, $flags);
				$smushResult = $smush->get();
				if (!empty($smushResult)) {
					$this->items[] = $smushResult;
				}
			}
		}
	}

	/**
	 * Return the list of SmushIt objects
	 * @access public
	 * @return array
	 * @see    SmushIt::$items
	 */
	public function get()
	{
		return $this->items;
	}

	/**
	 * Sometimes, Yahoo! Smush.it converts files (from gif to png, jpg to png)
	 * during compression process. This function check for this case, based on
	 * source and destination extensions.
	 * @access public
	 * @return bool
	 * @throws LogicException
	 */
	public function hasBeenConverted()
	{
		if ($this->hasFlag(self::THROW_EXCEPTION) AND (empty($this->source) OR empty($this->destination))) {
			throw new LogicException('Can\'t compare extensions: source or destination is empty');
		}

		return mb_strtolower(pathinfo($this->source, PATHINFO_EXTENSION)) !== mb_strtolower(pathinfo($this->destination, PATHINFO_EXTENSION));
	}

	/**
	 * Clean the $sources parameter from SmushIt::__construct()
	 * (flatten array and remove non-string values)
	 * @access private
	 * @param  string | array           $sources List of files to compress
	 * @return string | array
	 * @throws InvalidArgumentException
	 */
	private function clean($sources)
	{
		if (is_array($sources)) {
			$clean = array();
			array_walk_recursive($sources, function($line) use (&$clean) {
				$clean[] = $line;
			});
			$sources = array_filter(array_map(function($line) {
				if (!empty($line) AND is_string($line)) {
					return $line;
				}
			}, array_unique($clean)));
		} else if (!is_string($sources)) {
			$sources = null;
		}

		if (empty($sources) AND $this->hasFlag(self::THROW_EXCEPTION)) {
			throw new InvalidArgumentException('Sources can\'t be empty');
		}

		return $sources;
	}

	/**
	 * Check if source is a readable local file and doesn't exceed filesize limit
	 * @access private
	 * @param  string    $path Location of the current image to compress
	 * @return bool
	 * @throws Exception
	 */
	private function check($path)
	{
		if ($this->setSource($path) === false) {
			$this->error = "$path is not a valid path";
		} else if ($this->hasFlag(self::LOCAL_ORIGIN)) {
			if (!is_readable($path)) {
				$this->error = "$path is not readable";
			} else if(filesize($path) > self::SERVICE_API_LIMIT) {
				$this->error = "$path exceeds 1MB size limit";
			}
		}

		if (!empty($this->error)) {
			if ($this->hasFlag(self::THROW_EXCEPTION)) {
				throw new Exception($this->error);
			}
			return false;
		}

		return true;
	}

	/**
	 * Check if the flag $flag is set in the current object
	 * @access private
	 * @param  int  $flag The flag to check
	 * @return bool
	 */
	private function hasFlag($flag)
	{
		return (bool)($this->flags & $flag);
	}

	/**
	 * Set SmushIt::$source and check if it's a local or remote file
	 * @access private
	 * @param  string $flag The source to set
	 * @return false | null
	 */
	private function setSource($source)
	{
		$this->source = $source;
		if (filter_var($this->source, FILTER_VALIDATE_URL) !== false) {
			$this->flags |= self::REMOTE_ORIGIN;
		} else if (file_exists($this->source) AND !is_dir($this->source)) {
			$this->flags |= self::LOCAL_ORIGIN;
		} else {
			return false;
		}
	}

	/**
	 * Send current source to the API and get response
	 * @access private
	 * @throws Exception
	 */
	private function smush()
	{
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		if ($this->hasFlag(self::LOCAL_ORIGIN)) {
			curl_setopt($handle, CURLOPT_URL, self::SERVICE_API_URL);
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, array('files' => '@' . $this->source));
		} else {
			curl_setopt($handle, CURLOPT_URL, self::SERVICE_API_URL . '?img=' . $this->source);
		}
		$json = curl_exec($handle);
		if ($json === false) {
			if (self::hasFlag(self::THROW_EXCEPTION)) {
				throw new Exception('Curl error: ' . curl_error($handle));
			}
			return;
		}
		$this->set($json);
	}

	/**
	 * Set API response data to the current object
	 * @access private
	 * @throws Exception
	 */
	private function set($json)
	{
		$response = json_decode($json);
		if (empty($response)) {
			if (self::hasFlag(self::THROW_EXCEPTION)) {
				throw new Exception('Empty JSON response');
			}
			return;
		}
		$this->error = empty($response->error) ? $this->error : $response->error;
		$this->destination = empty($response->dest) ? null : $response->dest;
		$this->sourceSize = empty($response->src_size) ? null : intval($response->src_size);
		$this->destinationSize = empty($response->dest_size) ? null : intval($response->dest_size);
		$this->savings = empty($response->percent) ? null : floatval($response->percent);

		if (!empty($this->error) AND $this->hasFlag(self::THROW_EXCEPTION)) {
			throw new Exception($this->error);
		} else if (empty($this->error) OR $this->hasFlag(self::KEEP_ERRORS)) {
			$this->items[] = $this;
		}
	}
}
