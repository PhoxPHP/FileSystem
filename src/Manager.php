<?php
/**
* @author 		Peter Taiwo
* @version 		1.0.0
* @package 		FileSystem
* @copyright 	MIT License
* Copyright (c) 2017 PhoxPHP
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
use Kit\FileSystem\Directory\DirectoryManager;

class Manager
{

	/**
	* @var 		$resource
	* @access 	public
	*/
	public 		$resource;

	/**
	* @var 		$file
	* @access 	private
	*/
	private 		$file;

	/**
	* @var 		$directory
	* @access 	private
	*/
	private 		$directory;
	
	/**
	* Constructor
	*
	* @param 	$resource <String> Name of file or directory.
	* @access 	public
	* @return 	Object <Kit\FileSystem\Manager>
	*/
	public function __construct(String $resource)
	{
		(String) $this->resource = $resource;
		$this->file = new FileManager($resource);
		$this->directory = new DirectoryManager($resource);

		return $this;
	}

	/**
	* Returns an instance of Kit\FileSystem\File\FileManager.
	*
	* @access 	public
	* @return 	Object <Kit\FileSystem\File\FileManager>
	*/
	public function file() : FileManager
	{
		return $this->file;
	}

	/**
	* Returns an instance of Kit\FileSystem\Directory\DirectoryManager.
	*
	* @access 	public
	* @return 	Object <Kit\FileSystem\Directory\DirectoryManager>
	*/
	public function directory() : DirectoryManager
	{
		return $this->directory;
	}

}