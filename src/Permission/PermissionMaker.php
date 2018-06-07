<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\FileSystem\Permission\PermissionMaker
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

namespace Kit\FileSystem\Permission;

use Kit\FileSystem\Exceptions\BadPermissionException;
use Kit\FileSystem\Permission\Interfaces\Permittable;

class PermissionMaker
{

	/**
	* Changes a file or directory's group permission.
	*
	* @param 	$permittable <Kit\FileSystem\Permission\Interfaces\Permittable>
	* @param 	$group <String>
	* @access 	public
	* @throws 	<Kit\FileSystem\Exceptions\BadPermissionException>
	* @return 	<Boolean>
	*/
	public function changeGroup(Permittable $permittable, $group='') : Bool
	{
		if (!function_exists('chgrp')) {
			return false;
		}

		chgrp(
			$permittable->getPermitted(),
			$group
		);

		return true;
	}

	/**
	* Changes a file or directory's owner permission.
	*
	* @param 	$permittable <Kit\FileSystem\Permission\Interfaces\Permittable>
	* @param 	$owner <String>
	* @access 	public
	* @throws 	<Kit\FileSystem\Exceptions\BadPermissionException>
	* @return 	<Boolean>
	*/
	public function changeOwner(Permittable $permittable, $owner='') : Bool
	{
		if (!function_exists('chown')) {
			return false;
		}

		if (!chown($permittable->getPermitted(), $owner)) {
			throw new BadPermissionException("Could not change file ownership to $owner");
		}

		return true;
	}

	/**
	* Changes a file or directory's mode.
	*
	* @param 	$permittable <Kit\FileSystem\Permission\Interfaces\Permittable>
	* @param 	$mode <Integer>
	* @access 	public
	* @throws 	<Kit\FileSystem\Exceptions\BadPermissionException>
	* @return 	<Boolean>
	*/
	public function changeMode(Permittable $permittable, $mode=0644) : Bool
	{
		if (!function_exists('chmod')) {
			return false;
		}

		if (!chmod($permittable->getPermitted(), $mode)) {
			throw new BadPermissionException("Could not change file mode to $mode. Integer expected, string given");
		}
		
		return true;
	}

}