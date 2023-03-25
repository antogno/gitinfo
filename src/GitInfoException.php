<?php

namespace antogno\GitInfo;

use ErrorException;
use Throwable;

/**
 * GitInfo exception class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class GitInfoException extends ErrorException {
	
	public function __construct(string $message = '', ?Throwable $previous = null)
	{
		parent::__construct($message, 0, 1, __FILE__, __LINE__, $previous);
	}
}
