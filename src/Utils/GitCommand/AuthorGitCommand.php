<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use Exception;

/**
 * Author GitCommand class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class AuthorGitCommand implements ResourcesGitCommandInterface {

	/**
	 * Whether the given author exists or not
	 *
	 * @param string $email author email to search for, case insensitive
	 * @param string $name author name to search for, case insensitive
	 *
	 * @return bool
	 *
	 * @throws GitInfoException if none of email or name is given
	 */
	public static function exists(string $email = '', string $name = ''): bool
	{
		$email = strtolower(trim($email));
		$name = strtolower(trim($name));

		if (empty($email) && empty($name)) {
			throw new GitInfoException('One of email or name is required');
		}

		$list = self::getList();

		foreach ($list as $author) {
			if (!empty($email) && !empty($name)) {
				if (
					strtolower($author[0]) === $email
					&& strtolower($author[1]) === $name
				) {
					return true;
				}
			} else {
				if (
					in_array($email, array_map('strtolower', $author))
					|| in_array($name, array_map('strtolower', $author))
				) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Returns the author of the current commit
	 *
	 * @return string
	 */
	public static function getCurrent(): string
	{
		return CommitGitCommand::getAuthor(CommitGitCommand::getCurrent());
	}

	/**
	 * Returns the author email of the author with the given name
	 *
	 * @param string $name author name, case insensitive
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the email could not be retrieved
	 */
	public static function getEmail(string $name): string
	{
		try {
			$email = GitCommand::exec('log', [
				'-1',
				'--format' => '%ae',
				'--author' => $name
			])[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not get the author email from \"$name\"",
				$e
			);
		}

		return $email;
	}

	/**
	 * Returns the author name of the author with the given email
	 *
	 * @param string $email author email, case insensitive
	 *
	 * @return string
	 *
	 * @throws GitInfoException if the name could not be retrieved
	 */
	public static function getName(string $email): string
	{
		try {
			$name = GitCommand::exec('log', [
				'-1',
				'--format' => '%an',
				'--author' => $email
			])[0];
		} catch (Exception $e) {
			throw new GitInfoException(
				"Could not get the author name from \"$email\"",
				$e
			);
		}

		return $name;
	}

	/**
	 * Returns the author commits list
	 *
	 * If only one of email and name is given and there are multiple authors
	 * with the same given email or name, the first matching author will be used
	 *
	 * @param string $email author email, case insensitive
	 * @param string $name author name, case insensitive
	 *
	 * @return string[] array where each item is a long commit hash
	 *
	 * @throws GitInfoException if a valid author could not be retrieved from
	 * the given email and name, or if the commits list could not be retrieved
	 */
	public static function getCommits(string $email = '', string $name = ''): array
	{
		$email = trim($email);
		$name = trim($name);

		if (!self::exists($email, $name)) {
			throw new GitInfoException('Could not get a valid author');
		}

		$author_array = [];

		if (!empty($email)) {
			$author_array[] = $email;
		}

		if (!empty($name)) {
			$author_array[] = $name;
		}

		/**
		 * Instead of doing it this way, it could be done by setting the
		 * "--author" option like "<$email> $name", but the GitCommand::exec()
		 * method would escape the "<" and ">" characters
		 */
		foreach ($author_array as $author) {
			try {
				$commits[$author] = GitCommand::exec('log', [
					'--format' => '%H',
					'--author' => $author
				]);
			} catch (Exception $e) {
				throw new GitInfoException(
					"Could not get the author \"$author\" commits list",
					$e
				);
			}
		}

		/**
		 * If both of email and name are given, it returns only the common
		 * commits between the two
		 */
		return count($commits) > 1
			? array_values(
				array_unique(array_intersect($commits[$email], $commits[$name]))
			)
			: $commits;
	}

	/**
	 * Returns the authors list
	 *
	 * @return array[] array where each item is an array with two items, the
	 * email and the name
	 *
	 * @throws GitInfoException if the authors list could not be retrieved
	 */
	public static function getList(): array
	{
		try {
			$list = GitCommand::exec('log', ['--pretty' => '%ae %an']);
		} catch (Exception $e) {
			throw new GitInfoException('Could not get the authors list', $e);
		}

		$list = array_values(array_unique($list));

		return array_map(function (string $email_name) {
			// Each item is like "johndoe@email.com John Doe"
			$email_name_array = explode(' ', $email_name);

			return [
				array_shift($email_name_array),
				implode(' ', $email_name_array)
			];
		}, $list);
	}
}
