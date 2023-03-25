<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use Exception;

/**
 * Remote GitCommand class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class RemoteGitCommand implements ResourcesGitCommandInterface {
	
	/**
	 * Returns the given remote URL
	 *
	 * @param string $remote remote name, case sensitive
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the given remote URL could not be retrieved
	 */
	public static function getRemoteUrl(string $remote): string
	{
		try {
			$url = GitCommand::exec('remote', [], ['get-url', $remote])[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not retrieve the \"$remote\" remote URL",
				$e
			);
		}

		return $url;
	}

	/**
	 * Whether the given remote exists or not
	 *
	 * @param string $name remote name to search for, case sensitive
	 *
	 * @return bool
	 */
	public static function exists(string $name): bool
	{
		return in_array($name, self::getList(), true);
	}

	/**
	 * Returns the remote from which the current branch is tracking
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the current remote could not be retrieved
	 */
	public static function getCurrent(): string
	{
		$branch = BranchGitCommand::getCurrent();

		try {
			$remote = GitCommand::exec(
				'for-each-ref',
				['--format' => "'%(upstream:remotename)'"],
				["\"refs/heads/$branch\""],
				false
			)[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not retrieve the current remote',
				$e
			);
		}

		if (!self::exists($remote)) {
			throw new GitInfoException(
				"The retrieved output \"$remote\" is not a valid remote"
			);
		}

		return $remote;
	}

	/**
	 * Returns the remotes list
	 *
	 * @return string[] array where each item is a remote name
	 *
	 * @throws GitInfoException if the remotes list could not be retrieved
	 */
	public static function getList(): array
	{
		try {
			$list = GitCommand::exec('remote');
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not retrieve the remotes list',
				$e
			);
		}

		return $list;
	}
}
