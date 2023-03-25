<?php

namespace antogno\GitInfo\Validators;

/**
 * GitCommand validator class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class GitCommandValidator implements ValidatorsInterface {
	
	/**
	 * Escapes the given command
	 *
	 * @param string $command
	 *
	 * @return string
	 */
	public function escape(string $command): string
	{
		return trim(escapeshellcmd($command));
	}

	/**
	 * Whether the given command is a valid Git command or not
	 *
	 * @param string $command
	 *
	 * @return bool
	 */
	public function isValid(string $command): bool
	{
		if (empty($command)) {
			return false;
		}

		// If the command doesn't start with "git"
		if (strpos($command, 'git', 0) !== 0) {
			return false;
		}

		/**
		 * If the command has only three characters it means you're trying to
		 * execute "git" which is not valid
		 */
		if (strlen(trim($command)) === 3) {
			return false;
		}

		return true;
	}
}
