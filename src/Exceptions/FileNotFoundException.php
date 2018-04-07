<?php
namespace Kit\FileSystem\Exceptions;

use App\BaseException;

class FileNotFoundException extends BaseException
{

	/**
	* @var 		$template
	* @access 	protected
	*/
	protected 	$template = 'default';

	/**
	* @param 	$message <String>
	* @access 	public
	* @return 	void
	*/
	public function __construct($message='')
	{
		$this->message = $message;
		parent::__construct();
	}

}