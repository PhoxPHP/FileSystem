<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\FileSystem\File\Writer
* @license 		MIT License
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace Kit\FileSystem\File;

use Kit\FileSystem\File\FileManager;

class Writer
{

	/**
	* @var 		$file
	* @access 	private
	*/
	private 	$file;

	/**
	* @var 		$maxStringLength
	* @access 	private
	*/
	private static $maxStringLength = null;

	/**
	* @var 		$minStringLength
	* @access 	private
	*/
	private static $minStringLength = null;

	/**
	* @var 		$toNewLine
	* @access 	private
	*/
	private static $toNewLine = null;

	/**
	* Constructor
	*
	* @param 	$file <Kit\FileSystem\File\FileManager>
	* @access 	public
	* @return 	<void>
	*/
	public function __construct(FileManager $file)
	{
		$this->file = $file->getFile();
	}

	/**
	* Sets the maximum length of characters expected from a string.
	*
	* @param 	$length <Integer>
	* @access 	public
	* @return 	<void>
	*/
	public static function setMaximumLength(int $length=0)
	{
		return (Integer) Writer::$maxStringLength = $length;
	}

	/**
	* Sets the minimum length of characters expected from a string.
	*
	* @param 	$length <Integer>
	* @access 	public
	* @return 	<void>
	*/
	public static function setMinimumLength(int $length=0)
	{
		return (Integer) Writer::$minStringLength = $length;
	}

	/**
	* Runs all validations on length of data that will be saved.
	*
	* @param 	$data <String>
	* @access 	private
	* @throws 	RuntimeException
	* @return 	<void>
	*/
	private static function validateDataLength($data) {
		if (null !== Writer::$minStringLength && Writer::$minStringLength > 0 && ctype_digit(Writer::$minStringLength)) {

			if (strlen($data) < Writer::$minStringLength) {
				throw new RuntimeException('Unable to write data into file. Data length is lower than required length.');
			}
		
		}

		if (null !== Writer::$maxStringLength && Writer::$maxStringLength > 0 && is_integer((Integer) Writer::$maxStringLength)) {
			
			if (strlen($data) > Writer::$maxStringLength) {
				throw new RuntimeException('Unable to write data into file. Data length is higher than required length.');
			}
		
		}

	}

	/**
	* Sets a flag that tells the file writer to append data to a new line.
	*
	* @access 	public
	* @return 	<Boolean>
	*/
	public static function toNewLine() : Bool
	{
		return (Boolean) Writer::$toNewLine = true;
	}

	/**
	* Writes data into a file.
	* The new line flag is not added at the beginning of the data because if the file is empty before
	* writing into it, it omits the first line of the file and makes it empty.
	*
	* @param 	$data <String>
	* @access 	public
	* @return 	<void>
	*/
	public function write(String $data='')
	{
		Writer::validateDataLength($data);

		if (true == boolval(Writer::$toNewLine)) {
			$data = $data . "\n";
		}

		$filePointer = fopen($this->file, 'a');
		fwrite($filePointer, $data);
		fclose($filePointer);
	}

}