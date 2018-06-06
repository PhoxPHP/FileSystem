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

class Uploader
{

	/**
	* @var 		$upload_directory
	* @access 	protected
	*/
	protected 	$upload_directory;

	/**
	* @var 		$max_file_size
	* @access 	protected
	*/
	protected 	$max_file_size;

	/**
	* @var 		$file_types
	* @access 	protected
	*/
	protected 	$file_types;

	/**
	* @param 	$options <Array> 
	* @access 	public
	* @return 	void
	*/
	public function __construct(Array $options=[])
	{

	}

	/**
	* Sets new filename for uploaded file.
	*
	* @param 	$filename <String>
	* @access 	public
	* @return 	void
	*/
	public function setNewFilename(String $filename)
	{

	}

	/**
	* Sets new filename for uploaded file.
	*
	* @param 	$filename <String>
	* @access 	public
	* @return 	void
	*/
	public function setNewFilenamesGenerator(String $filename)
	{

	}

	/**
	* Sets maximum file size in bytes.
	*
	* @param 	$size <Integer>
	* @access 	public
	* @return 	void
	*/
	public function setMaximumFileSize(int $size=500)
	{

	}

	/**
	* Sets file types.
	*
	* @param 	$fileTypes <Array>
	* @access 	public
	* @return 	void
	*/
	public function setFileTypes(Array $fileTypes)
	{

	}

}