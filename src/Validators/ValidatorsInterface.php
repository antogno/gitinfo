<?php

namespace antogno\GitInfo\Validators;

/**
 * Validators interface
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
interface ValidatorsInterface {
	
	/**
	 * Escapes the given subject
	 *
	 * @return string
	 */
	public function escape(string $subject): string;

	/**
	 * Whether the given subject is valid or not
	 *
	 * @return bool
	 */
	public function isValid(string $subject): bool;
}
