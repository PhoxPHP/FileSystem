<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\FileSystem\File\Uploader\Uploader
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

namespace Kit\FileSystem\File\Uploader;

use Closure;
use StdClass;
use RuntimeException;
use Kit\FileSystem\File\FileManager;

class Uploader
{

	/**
	* @var 		$destinationFolder
	* @access 	protected
	*/
	protected 	$destinationFolder;

	/**
	* @var 		$destinationFolders
	* @access 	protected
	*/
	protected 	$destinationFolders = [];

	/**
	* @var 		$maxFileSize
	* @access 	protected
	*/
	protected 	$maxFileSize = 500;

	/**
	* @var 		$fileTypes
	* @access 	protected
	*/
	protected 	$fileTypes = [];

	/**
	* @var 		$filename
	* @access 	protected
	*/
	protected 	$filename = null;

	/**
	* @var 		$filenameGenerator
	* @access 	protected
	*/
	protected 	$filenameGenerator  = null;

	/**
	* @var 		$handle
	* @access 	protected
	*/
	protected 	$handle = null;

	/**
	* @var 		$haltProcess
	* @access 	protected
	*/
	protected 	$haltProcess = false;

	/**
	* @var 		$error
	* @access 	protected
	*/
	protected 	$error;

	/**
	* @var 		$uploadedFiles
	* @access 	protected
	*/
	protected 	$uploadedFiles = [];

	/**
	* @var 		$fileManager
	* @access 	protected
	*/
	protected 	$fileManager;

	/**
	* Uploader constructor.
	*
	* @param 	$fileManager <String>
	* @access 	public
	*/
	public function __construct(FileManager $fileManager)
	{
		$this->fileManager = $fileManager;
	}

	/**
	* Sets the file request name.
	*
	* @param 	$handle <String>
	* @access 	public
	* @return 	<void>
	*/
	public function setHandle(String $handle)
	{
		$this->handle = $handle;
	}

	/**
	* Sets new filename for uploaded file.
	*
	* @param 	$filename <String>
	* @access 	public
	* @return 	<void>
	*/
	public function setNewFilename(String $filename)
	{
		$this->filename = $filename;
	}

	/**
	* Sets filename generator when uploading multiple files.
	*
	* @param 	$generator <String>
	* @access 	public
	* @return 	<void>
	*/
	public function setNewFilenamesGenerator(String $generator)
	{
		$this->filenameGenerator = $generator;
	}

	/**
	* Sets maximum file size in bytes.
	*
	* @param 	$size <Integer>
	* @access 	public
	* @return 	<void>
	*/
	public function setMaxFileSize(int $size=500)
	{
		$this->maxFileSize = $size;
	}

	/**
	* Sets file types.
	*
	* @param 	$fileTypes <Array>
	* @access 	public
	* @return 	<void>
	*/
	public function setFileTypes(Array $fileTypes)
	{
		$this->fileTypes = $fileTypes;
	}

	/**
	* Sets the upload directory to use.
	*
	* @param 	$destinationFolder <String>
	* @access 	public
	* @return 	<void>
	*/
	public function setDestinationFolder(String $destinationFolder=null)
	{
		$this->destinationFolder = $destinationFolder;
	}

	/**
	* Sets upload directory based on different file types provided.
	*
	* @param 	$criteriaTypes <Array>
	* @access 	public
	* @return 	<void>
	*/
	public function setDestinationFolderByFileTypes(Array $criteriaTypes=[])
	{
		$this->destinationFolders = $criteriaTypes;
	}

	/**
	* Check if an error has been set.
	*
	* @access public
	* @return 	<Boolean>
	*/
	public function hasError() : Bool
	{
		return ($this->error !== null) ? true : false;
	}

	/**
	* Returns error encountered when processing uploads.
	*
	* @access 	public
	* @return 	Array
	*/
	public function getError()
	{
		return $this->error;
	}

	/**
	* Returns an array of uploaded files.
	*
	* @access 	public
	* @return 	Array
	*/
	public function getUploadedFiles() : Array
	{
		return $this->uploadedFiles;
	}

	/**
	* Halts the current upload process.
	*
	* @access 	public
	* @return 	<void>
	*/
	public function haltProcess()
	{
		$this->haltProcess = true;
	}

	/**
	* Initialize upload.
	*
	* @param 	$strict <Boolean> | If this is set to true, exceptions will be thrown if an error
	* 			occurs while uploading.
	* @param 	$beforeCallback <Closure> | Closure function that is run before a file is uploaded.
	* @param 	$afterCallback <Closure> | Closure function that is run aftr a file is uploaded.
	* @access 	public
	* @return 	<void>
	*/
	public function upload(Bool $strict=false, Closure $beforeCallback=null, Closure $afterCallback=null)
	{
		if ($this->handle && isset($_FILES[$this->handle])) {
			$files = $_FILES[$this->handle];
			if (is_array($files['name'])) {
				// If multiple files, loop through the files and process each file.
				for($i = 0; $i < count($files['name']); $i++) {
					if ($this->haltProcess == true) {
						break;
					}

					$file = new StdClass();
					$file->originalName = $files['name'][$i];
					$file->size = $files['size'][$i];
					$file->tmpName = $files['tmp_name'][$i];
					$file->errorNumber = $files['error'][$i];
					$file->type = $files['type'][$i];
					$file->newName = ($this->filenameGenerator !== null) ? $this->filenameGenerator . $i : pathinfo($file->originalName, PATHINFO_FILENAME);
					$file->hasIndex = true;
					$file->index = $i;
					$file->extension = pathinfo($file->originalName, PATHINFO_EXTENSION);

					$this->processUpload($file, $strict, $beforeCallback, $afterCallback);
				}
			}else{
				$file = new StdClass();
				$file->originalName = $files['name'];
				$file->size = $files['size'];
				$file->tmpName = $files['tmp_name'];
				$file->errorNumber = $files['error'];
				$file->type = $files['type'];
				$file->newName = ($this->filenameGenerator !== null) ? $this->filenameGenerator . $i : pathinfo($file->originalName, PATHINFO_FILENAME);
				$file->hasIndex = false;
				$file->extension = pathinfo($file->originalName, PATHINFO_EXTENSION);

				$this->processUpload($file, $strict, $beforeCallback, $afterCallback);
			}
		}
	}

	/**
	* Process file ready to be uploaded.
	*
	* @param 	$file <StdClass>
	* @param 	$strict <Boolean>
	* @param 	$beforeCallback <Mixed> | If you modified the file object and you would like to use the
	*			modified file object in the beforeCallback closure, all you need to do is return the object
	*			in this closure. 
	* @param 	$afterCallback <Mixed>
	* @access 	protected
	* @return 	<void>
	*/
	protected function processUpload(StdClass $file, Bool $strict, $beforeCallback, $afterCallback)
	{
		if ($beforeCallback instanceof Closure) {
			$modifiedFile = $beforeCallback($file, $this);

			if ($modifiedFile instanceof StdClass) {
				$file = $modifiedFile;
			}
		}

		if ($this->haltProcess == true) {
			return;
		}

		if ($file->errorNumber !== 0) {
			$this->haltProcess();
			$this->error = 'File could not be uploaded.';

			if ($strict == true) {
				throw new RuntimeException($this->error);
			}

			return;
		}

		if ($file->size > $this->maxFileSize) {
			$this->haltProcess();
			$this->error = 'File size must be greater than ' . $this->formatSize($this->maxFileSize);

			if ($strict == true) {
				throw new RuntimeException($this->error);
			}

			return;
		}

		$destinationFolder = (isset($this->destinationFolders[$file->type])) ? $this->destinationFolders[$file->type] : $this->destinationFolder;

		if (!isset($file->destinationFolder)) {
			$file->destinationFolder = $destinationFolder;
		}

		if (!empty($this->errors)) {
			$this->haltProcess();
			return;
		}

		$file->newLocation = $file->destinationFolder . $file->newName . '.' . $file->extension;
		if(!move_uploaded_file($file->tmpName, $file->destinationFolder . $file->newName . '.' . $file->extension)) {
			return $this->haltProcess();
		}

		$file->uploadStatus = 'success';
		$this->uploadedFiles[] = $file;
		
		if ($afterCallback instanceof Closure) {
			$afterCallback($file, $this);
		}
	}

	/**
	* Formats byte sizes to kilobytes, megabytes, gigabytes and terabytes. 
	*
	* @param 	$size <Integer>
	* @access 	protected
	* @return 	<Integer>
	*/
	protected function formatSize(int $size=0)
	{
		$base = log($size) / log(1024);
		$suffix = array('bytes', 'kb', 'mb', 'gb', 'tb')[floor($base)];
		
		$formattedSize = pow(1024, $base - floor($base));
		return round($formattedSize, 2) . ' ' . $suffix;
	}

}