<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\FileSystem\File\FileManager
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

use Kit\FileSystem\File\Reader;
use Kit\FileSystem\File\Writer;
use Kit\FileSystem\Permission\PermissionMaker;
use Kit\FileSystem\Exceptions\FileNotFoundException;
use Kit\FileSystem\Permission\Contracts\Permittable;

class FileManager implements Permittable
{

	/**
	* @var 		$file
	* @access 	private
	*/
	private 	$file = null;

	/**
	* FileManager constructor.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Object> <Kit\FileSystem\File\FileManager>
	*/
	public function __construct(String $file='')
	{
		$this->file = $file;
		return $this;
	}

	/**
	* Creates a new file and returns file object afterwards.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Object> <Kit\FileManager\File\FileManager>
	*/
	public function create(String $file=null)
	{
		if ($file !== null) {
			$this->file = $file;
		}

		$file = ['file' => $this->file];
		$setPointer = fopen($this->file, 'w');

		if (false == $setPointer) {

			touch($this->file);
		
		}

		return $this;
	}

	/**
	* This method does the same thing with File::create but it checks if the file exists
	* or not before attempting to create the file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<void>
	*/
	public function createIfNotExist(String $file=null)
	{
		if (!$this->exists($file)) {
			return $this->create($this->file);
		}

		return $this;
	}

	/**
	* Checks if a file exists.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Boolean>
	*/
	public function exists(String $file=null) : Bool
	{
		$response = false;
		if ($file !== null) {
			$this->file = $file;
		}
		
		if (file_exists($this->file) && is_file($this->file)) {
			$response = true;
		}

		return $response;
	}

	/**
	* Returns the size of a file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getFileSize(String $file=null)
	{
		$response = false;

		if ($this->exists($file)) {
			$size = filesize($this->file);
			
			return $size;
		
		}
	}

	/**
	* Gets the file type of the specified file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getFileType(String $file=null)
	{
		if ($this->exists($file)) {
			return filetype($this->file);
		}

		return null;
	}

	/**
	* Copies a file to a new location.
	*
	* @param 	$file <String>
	* @param 	$newDestination <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function copyTo(String $file=null, String $newDestination='')
	{
		if ($this->exists($file) && file_exists($newDestination)) {
			copy($this->file, $newDestination);
			return true;
		}

		return null;
	}

	/**
	* Renames the file to the string name returned in @param $newName.
	*
	* @param 	$newName <String>
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function rename(String $newName='', String $file=null)
	{
		if ('' == $newName) {
			$newName = uniqid();
		}

		if ($this->exists($file)) {
			rename($this->file, $newName);
			return true;
		}
	}

	/**
	* Deletes a file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Boolean>
	*/
	public function delete(String $file=null)
	{
		if ($file !== null) {
			$this->file = $file;
		}

		unlink($this->file);
	}

	/**
	* Deletes multiple files.
	*
	* @param 	$files <Array>
	* @access 	public
	* @return 	void
	*/
	public function deleteMultiple(array $files=[])
	{
		if (sizeof($files) > 0) {
			return array_map([$this, 'deleteIfExists'], $array);
		}
	}

	/**
	* Delets a file only if it exists.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function deleteIfExists(String $file='')
	{
		if ($this->exists($file)) {
			$this->delete($this->file);
			return true;
		}

		return null;
	}

	/**
	* Gets the modification time of file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getModifiedTime(String $file=null)
	{
		if ($this->exists($file)) { 
			return filemtime($this->file);
		}

		return null;
	}

	/**
	* Gets the last access time of file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getLastAccessTime(String $file=null)
	{
		if ($this->exists($file)) {
			return fileatime($this->file);
		}

		return null;
	}

	/**
	* Returns the name of file we are working with mainly because the property $file
	* is given a private access.
	*
	* @access 	public
	* @return 	<String>
	*/
	public function getFile()
	{
		return $this->file;
	}

	/**
	* Gets the content of a file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<String>
	*/
	public function read(String $file=null)
	{
		if ($this->exists($file)) {
			return Reader::read($this);
		}
	}

	/**
	* @todo 	Create documentation
	* @param 	$file <String>
	* @access 	public
	* @return 	Array
	*/
	public function readRaw(String $file=null)
	{
		if ($this->exists($file)) {
			return Reader::readAsArray($this);
		}
	}

	/**
	* Checks if a file is writable.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Boolean>
	*/
	public function isWritable(String $file=null)
	{
		(Boolean) $response = false;

		if ($file !== null) {
			$this->file = $file;
		}

		if (is_writable($this->file)) {
			$response = true; 
		}

		return $response;
	}

	/**
	* Checks if a file is executable.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Boolean>
	*/
	public function isExecutable(String $file=null)
	{
		(Boolean) $response = false;

		if ($file !== null) {
			$this->file = $file;
		}

		if (is_executable($this->file)) {
			$response = true;
		}

		return $response;
	}

	/**
	* Writes data into file.
	*
	* @param 	$data <String>
	* @param 	$file <String>
	* @access 	public
	* @return 	<Boolean>
	*/
	public function write(String $data='', String $file=null) : Bool
	{
		if (!$this->exists($file)) {
			return false;
		}

		$writer = new Writer($this);

		return ($writer->write($data)) ? true : false;
	}

	/**
	* Changes a file owner.
	*
	* @param 	$owner <String>
	* @param 	$file <String>
	* @access 	public
	* @return 	<void>
	*/
	public function chown(String $owner='', String $file=null)
	{
		if (!$this->exists($file)) {
			return;
		} 

		return $this->permissionInstance()->changeOwner($this, $owner);
	}

	/**
	* Changes a file group.
	*
	* @param 	$group <String>
	* @param 	$file <String>
	* @access 	public
	* @return 	<void>
	*/
	public function chgrp(String $group='', String $file=null)
	{
		if (!$this->exists($file)) {
			return;
		}

		return $this->permissionInstance()->changeGroup($this, $group);
	}

	/**
	* Changes a file mode.
	*
	* @param 	$mode <String>
	* @param 	$file <String>
	* @access 	public
	* @return 	<void>
	*/
	public function chmod(String $mode='', String $file=null)
	{
		if (!$this->exists($file)) {
			return;
		}

		return $this->permissionInstance()->changeMode($this, $mode);
	}

	/**
	* Reads and returns a line from file.
	*
	* @param 	$line <Integer>
	* @param 	$file <String>
	* @access 	public
	* @throws 	<Kit\FileSystem\Exceptions\FileNotFoundException>
	* @return 	<Mixed>
	*/
	public function getLine(int $line=0, String $file=null)
	{
		if (!$this->exists($file)) {
			throw new FileNotFoundException("Unable to get line from file $this->file");
		}

		$reader = Reader::readAsArray($this);
		return $reader[$line];
	}

	/**
	* Checks if file was uploaded via http post.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Boolean>
	*/
	public function isPostedUpload(String $file=null) : Bool
	{
		if ($file !== null) {
			$this->file = $file;
		}

		if (!is_uploaded_file($this->file)) {
			return false;
		}

		return true;
	}

	/**
	* Returns the real path of file.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getRealPath(String $file=null)
	{
		if ($this->exists($file)) {
			return realpath($this->file);
		}

		return false;
	}

	/**
	* Returns a file extension.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<void>
	*/
	public function getExtension(String $file=null)
	{
		if ($this->exists($file)) {
			return pathinfo($file, PATHINFO_EXTENSION);
		}

		return false;
	}

	/**
	* Returns a filename without it's extension.
	*
	* @param 	$file <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getNameWithoutExtension(String $file=null)
	{
		if ($this->exists($file)) {
			return pathinfo($file, PATHINFO_FILENAME);
		}

		return false;
	}

	/**
	* {@inheritDoc}
	*/
	public function getPermitted()
	{
		return $this->file;
	}

	/**
	* Returns an instance of PermissionMaker.
	*
	* @access 	private
	* @return 	<Object> <FileSystem\Permission\PermissionMaker>
	*/
	private function permissionInstance() : PermissionMaker
	{
		return new PermissionMaker();
	}

}