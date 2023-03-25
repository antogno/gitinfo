<?php

namespace antogno\GitInfo\Utils\GitCommand;

/**
 * Resources GitCommand interface
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
interface ResourcesGitCommandInterface {
	
	/**
	 * Whether the given subject exists or not
	 *
	 * @return bool
	 */
	public static function exists(string $subject): bool;

	/**
	 * Returns the current subject
	 *
	 * @return string
	 */
	public static function getCurrent(): string;

	/**
	 * Returns the subjects list
	 *
	 * @return array
	 */
	public static function getList(): array;
}
