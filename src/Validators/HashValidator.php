<?php

namespace antogno\GitInfo\Validators;

/**
 * Hash validator class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class HashValidator implements ValidatorsInterface {
	
	/**
	 * Escapes the given hash
	 *
	 * @param string $hash
	 *
	 * @return string
	 */
	public function escape(string $hash): string
	{
		return trim($hash);
	}

	/**
	 * Whether the given string is a valid SHA-1 hash or not
	 *
	 * @param string $hash
	 *
	 * @return bool
	 */
	public function isValid(string $hash): bool
	{
		// The hash can be long (40 characters) or short (7 characters)
		return boolval(preg_match('/^[0-9a-f]{7}([0-9a-f]{33})?$/i', $hash));
	}
}
