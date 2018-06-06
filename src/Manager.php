<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\FileSystem\Manager
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

namespace Kit\FileSystem;

use Kit\FileSystem\Converter;
use Kit\FileSystem\File\FileManager;
use Kit\FileSystem\File\Uploader\Uploader;
use Kit\FileSystem\Directory\DirectoryManager;

class Manager
{

	/**
	* @var 		$file
	* @access 	private
	*/
	private 	$file = null;

	/**
	* @var 		$directory
	* @access 	private
	*/
	private 	$directory = null;
	
	/**
	* Constructor
	*
	* @param 	$resource <String> Name of file or directory.
	* @access 	public
	* @return 	Object <Kit\FileSystem\Manager>
	*/
	public function __construct(String $resource=null)
	{
		if ($resource !== null) {
			$this->file = new FileManager($resource);
			$this->directory = new DirectoryManager($resource);
		}

		return $this;
	}

	/**
	* Returns an instance of Kit\FileSystem\File\FileManager.
	*
	* @param 	$resource <String>
	* @access 	public
	* @return 	Object <Kit\FileSystem\File\FileManager>
	*/
	public function file(String $resource=null) : FileManager
	{
		if ($this->file == null) {
			return new FileManager($resource);
		}

		return $this->file;
	}

	/**
	* Returns an instance of Kit\FileSystem\Directory\DirectoryManager.
	*
	* @param 	$resource <String>
	* @access 	public
	* @return 	Object <Kit\FileSystem\Directory\DirectoryManager>
	*/
	public function directory(String $resource=null) : DirectoryManager
	{
		if ($this->directory == null) {
			return new DirectoryManager($resource);
		}

		return $this->directory;
	}

	/**
	* Returns an instance of Kit\FileSystem\File\Uploader\Uploader
	*
	* @param 	$options <Array> | Options passed to uploader constructor.
	* @access 	public
	* @return 	Object <Kit\FileSystem\File\Uploader\Uploader>
	*/
	public function uploader(Array $options=[]) : Uploader
	{
		return new Uploader($options);
	}

}