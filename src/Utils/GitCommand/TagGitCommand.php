<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use Exception;

/**
 * Tag GitCommand class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class TagGitCommand implements ResourcesGitCommandInterface {
	
	/**
	 * Returns the last commit long hash of the given tag
	 *
	 * @param string $name tag name, case sensitive
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the given tag could not be retrieved, or if
	 * the given tag last commit could not be retrieved
	 */
	public static function getLastCommit(string $name): string
	{
		if (!self::exists($name)) {
			throw new GitInfoException("Could not get tag \"$name\"");
		}

		try {
			$hash = GitCommand::exec(
				'log',
				['--format' => '%H', '-n' => 1],
				[$name]
			)[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not get tag \"$name\" last commit",
				$e
			);
		}

		return $hash;
	}

	/**
	 * Returns the current tag, if in a tag
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the last commit of a tag (to be compared with
	 * the current commit) could not be retrieved
	 */
	public static function getCurrent(): string
	{
		$current_commit_hash = CommitGitCommand::getCurrent();
		$tags = self::getList();

		if (!empty($tags)) {
			foreach ($tags as $tag) {
				try {
					$output = GitCommand::exec('rev-list', ['-n' => 1], [$tag]);
				} catch (Exception $e) {
					throw new GitInfoException(
						"Could not get the last commit hash for the \"$tag\" tag",
						$e
					);
				}

				if (empty($output)) {
					continue;
				}

				if ($current_commit_hash === $output) {
					return $tag;
				}
			}
		}

		return '';
	}

	/**
	 * Whether the given tag exists or not
	 *
	 * @param string $name tag name to search for, case sensitive
	 *
	 * @return bool
	 */
	public static function exists(string $name): bool
	{
		try {
			GitCommand::exec(
				'show-ref',
				['--verify', '--quiet'],
				["refs/tags/$name"]
			);
		} catch (Exception $e) {
			return false;
		}

		return in_array($name, self::getList(), true);
	}

	/**
	 * Returns the tags list
	 *
	 * @return string[] array where each item is a tag name
	 *
	 * @throws GitInfoException if the tags list could not be retrieved
	 */
	public static function getList(): array
	{
		try {
			$list = GitCommand::exec('tag');
		} catch (Exception $e) {
			throw new GitInfoException('Could not get the tags list', $e);
		}

		return array_map(function (string $tag) {
			return trim(preg_replace('/\s+/', ' ', $tag));
		}, $list);
	}
}
