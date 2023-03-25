<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Validators\HashValidator;
use Exception;

/**
 * Commit GitCommand class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class CommitGitCommand implements ResourcesGitCommandInterface {
	
	/**
	 * Returns the long hash of the given revision
	 *
	 * @param string $rev revision to get the long hash from
	 *
	 * @return string
	 */
	public static function getLongHash(string $rev): string
	{
		return self::getHash($rev, false);
	}

	/**
	 * Returns the short hash of the given revision
	 *
	 * @param string $rev revision to get the short hash from
	 *
	 * @return string
	 */
	public static function getShortHash(string $rev): string
	{
		return self::getHash($rev, true);
	}

	/**
	 * Whether a commit with the given hash exists or not
	 *
	 * @param string $hash long or short hash to search for
	 *
	 * @return bool
	 */
	public static function exists(string $hash): bool
	{
		try {
			$output = GitCommand::exec('cat-file', ['-t'], [$hash])[0];
		} catch (Exception $e) {
			return false;
		}

		return $output === 'commit';
	}

	/**
	 * Returns the long hash of the current commit
	 *
	 * @return string
	 */
	public static function getCurrent(): string
	{
		return self::getLongHash('HEAD');
	}

	/**
	 * Returns the message of the commit with the given hash
	 *
	 * @param string $hash long or short hash
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the given string is not a valid hash, or if
	 * the commit message could not be retrieved
	 */
	public static function getMessage(string $hash): string
	{
		$Validator = new HashValidator();

		if (!$Validator->isValid($hash)) {
			throw new GitInfoException("\"$hash\" is not a valid SHA-1 hash");
		}

		try {
			$message = GitCommand::exec(
				'log',
				['--format' => '%B', '-n' => 1],
				[$hash]
			)[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not retrieve the commit message from \"$hash\"",
				$e
			);
		}

		return $message;
	}

	/**
	 * Returns the author of the commit with the given hash
	 *
	 * @param string $hash long or short hash
	 *
	 * @return string the returned string is like "johndoe@email.com John Doe"
	 *
	 * @throws GitInfoException if the given string is not a valid hash, or if
	 * the commit author could not be retrieved
	 */
	public static function getAuthor(string $hash): string
	{
		$Validator = new HashValidator();

		if (!$Validator->isValid($hash)) {
			throw new GitInfoException("\"$hash\" is not a valid SHA-1 hash");
		}

		try {
			$author = GitCommand::exec(
				'log',
				['--format' => '%ae %an', '-n' => 1],
				[$hash]
			)[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not retrieve the commit author from \"$hash\"",
				$e
			);
		}

		return $author;
	}

	/**
	 * Returns the date of the commit with the given hash
	 *
	 * @param string $hash long or short hash
	 *
	 * @return string date in the "Y-m-d H:i:s O" format
	 *
	 * @throws GitInfoException if the given string is not a valid hash, or if
	 * the commit date could not be retrieved
	 */
	public static function getDate(string $hash): string
	{
		$Validator = new HashValidator();

		if (!$Validator->isValid($hash)) {
			throw new GitInfoException("\"$hash\" is not a valid SHA-1 hash");
		}

		try {
			$date = GitCommand::exec(
				'show',
				['-s', '--format' => '%ci', '-n' => 1],
				[$hash]
			)[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not retrieve the commit date from \"$hash\"",
				$e
			);
		}

		return $date;
	}

	/**
	 * Returns the commits list
	 *
	 * @return string[] array where each item is a long commit hash
	 *
	 * @throws GitInfoException if the commits list could not be retrieved
	 */
	public static function getList(): array
	{
		try {
			$list = GitCommand::exec('log', ['--format' => '%H']);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not retrieve the commits list',
				$e
			);
		}

		return $list;
	}

	/**
	 * Returns the hash of the given revision
	 *
	 * @param string $rev revision to get the hash from
	 * @param bool $short whether to get the short hash or not
	 *
	 * @return string
	 *
	 * @throws GitInfoException if a valid hash from the given revision could
	 * not be retrieved
	 */
	protected static function getHash(string $rev, bool $short): string
	{
		try {
			$hash = GitCommand::exec('rev-parse', $short ? ['--short'] : [], [
				$rev
			])[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not get a valid hash from revision \"$rev\"",
				$e
			);
		}

		return $hash;
	}
}
