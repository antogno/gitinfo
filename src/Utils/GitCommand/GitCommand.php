<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Validators\GitCommandValidator;
use ErrorException;
use Exception;

/**
 * GitCommand class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class GitCommand {

	/**
	 * Returns the Git version
	 *
	 * @return string
	 */
	public static function getVersion(): string
	{
		try {
			$version = GitCommand::exec('', ['--version'])[0];
		} catch (Exception $e) {
			throw new GitInfoException('Could not get the Git version', $e);
		}

		/**
		 * The string could be similar to "git version 2.22.0" or
		 * "git version 2.9.3 (Apple Git-75)" etc., so the first 12 characters
		 * are always useless
		 */
		$version = substr($version, 12);

		/**
		 * After the Git version number there could be anything (e.g.:
		 * " (Apple Git-75)", ".windows.1 (64bit)", etc.) so we take the first
		 * three strings separated by dots not caring about the rest
		 */
		$version_array = array_map(function (string $string) {
			/**
			 * If the starting string is like "2.9.3 (Apple Git-75)", the last
			 * exploded item will be "3 (Apple Git-75)"; using intval() converts
			 * it to "3"
			 */
			return intval(trim($string));
		}, array_slice(explode('.', $version), 0, 3));

		return implode('.', $version_array);
	}

	/**
	 * Returns the absolute path of the top-level directory of the working tree
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the absolute path could not be retrieved
	 */
	public static function getAbsolutePath(): string
	{
		try {
			$path = GitCommand::exec('rev-parse', ['--show-toplevel'])[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the repository absolute path',
				$e
			);
		}

		return $path;
	}

	/**
	 * Executes a Git command
	 *
	 * @param string $command a valid Git command
	 * @param array $options options list to use along with the command; if an
	 * option requires an argument, the option itself should be passed as a key
	 * and the argument as its value (the argument can also be an array)
	 * @param array $args arguments to use along with the command
	 * @param bool $escape whether to escape the command to make it safe or not
	 *
	 * @return string[] array with every line of output from the command
	 *
	 * @throws ErrorException if the generated command string is not a valid Git
	 * command, or if the command exited with an error
	 */
	public static function exec(string $command, array $options = [], array $args = [], bool $escape = true): array
	{
		$full_command = 'git ' . (!empty($command) ? ($escape ? escapeshellarg($command) : $command) : '');

		if (!empty($options)) {
			foreach ($options as $key => $value) {
				$option_arg_separator = '';

				/**
				 * If the key starts with a dash, it means that you're trying to
				 * also pass an argument
				 */
				if (strpos($key, '-', 0) === 0 && !empty($value)) {
					$option = $key;

					if ($escape) {
						$option_args = is_array($value)
							? array_map('escapeshellarg', $value)
							: [escapeshellarg($value)];
					} else {
						$option_args = is_array($value) ? $value : [$value];
					}
				} else {
					$option = $value;
					$option_args = [];
				}

				$option_arg_separator = strpos($option, '--') === 0 ? '=' : ' ';

				$full_command .= ' ' . $option . (!empty($option_args)
					? $option_arg_separator . implode(' ', $option_args)
					: ''
				);
			}
		}

		if (!empty($args)) {
			$full_command .= ' ' . implode(' ', $escape ? array_map('escapeshellarg', $args) : $args);
		}

		$Validator = new GitCommandValidator();

		if ($escape) {
			$full_command = $Validator->escape($full_command);
		}

		if (!$Validator->isValid($full_command)) {
			throw new ErrorException(
				"The given \"$full_command\" is not a valid Git command"
			);
		}

		exec($full_command, $output, $code);

		if ($code !== 0) {
			throw new ErrorException(
				"The \"$full_command\" Git command exited with code $code"
			);
		}

		return $output;
	}
}
