<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use Exception;

/**
 * Branch GitCommand class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class BranchGitCommand implements ResourcesGitCommandInterface {
	
	/**
	 * Returns the last commit long hash of the given branch
	 *
	 * @param string $name branch name, case sensitive
	 *
	 * @return string
	 *
	 * @throws GitInfoException if a valid branch could not be retrieved from
	 * the given branch name, or if the given branch last commit could not be
	 * retrieved
	 */
	public static function getLastCommit(string $name): string
	{
		if (!self::exists($name)) {
			throw new GitInfoException("Could not get branch \"$name\"");
		}

		try {
			$hash = GitCommand::exec(
				'log',
				['--format' => '%H', '-n' => 1],
				[$name]
			)[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not get branch \"$name\" last commit",
				$e
			);
		}

		return $hash;
	}

	/**
	 * Returns the current branch name
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the current branch name could not be
	 * retrieved
	 */
	public static function getCurrent(): string
	{
		try {
			$branch = GitCommand::exec('branch', ['--show-current'])[0];
		} catch (Exception $e) {
			throw new GitInfoException('Could not get the current branch', $e);
		}

		return $branch;
	}

	/**
	 * Whether the given branch exists or not
	 *
	 * @param string $name branch name to search for, case sensitive
	 *
	 * @return bool
	 */
	public static function exists(string $name): bool
	{
		try {
			GitCommand::exec(
				'show-ref',
				['--verify', '--quiet'],
				["refs/heads/$name"]
			);
		} catch (Exception $e) {
			return false;
		}

		return in_array($name, self::getList(), true);
	}

	/**
	 * Returns the branches list
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the branches list could not be retrieved
	 */
	public static function getList(): array
	{
		try {
			$list = GitCommand::exec('branch');
		} catch (Exception $e) {
			throw new GitInfoException('Could not get the branch list', $e);
		}

		return array_map(function (string $branch) {
			// The current branch it's prefixed by "* " (e.g.: "* master")
			return trim(
				preg_replace('/\s+/', ' ', str_replace('*', '', $branch))
			);
		}, $list);
	}
}
