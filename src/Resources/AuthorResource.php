<?php

namespace antogno\GitInfo\Resources;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Utils\GitCommand\AuthorGitCommand;

/**
 * Author resource class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class AuthorResource {

	/**
	 * Author email
	 *
	 * @var string
	 */
	protected string $email;

	/**
	 * Author name
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * If only one of email and name is given and there are multiple authors
	 * with the same given email or name, the first matching author will be used
	 *
	 * @param string $email author email, case insensitive
	 * @param string $name author name, case insensitive
	 *
	 * @return void
	 *
	 * @throws GitInfoException if a valid author could not be retrieved
	 */
	public function __construct(string $email = '', string $name = '')
	{
		$email = strtolower(trim($email));
		$name = strtolower(trim($name));

		if (!AuthorGitCommand::exists($email, $name)) {
			throw new GitInfoException('Could not get a valid author');
		}

		$list = AuthorGitCommand::getList();

		foreach ($list as $author) {
			if (!empty($email) && !empty($name)) {
				/**
				 * If both email and name are given, it searches for an author
				 * with exactly that email and name
				 */
				if (
					strtolower($author[0]) === $email
					&& strtolower($author[1]) === $name
				) {
					/**
					 * The replacement takes place to prevent any difference in
					 * letter case
					 */
					$email = $author[0];
					$name = $author[1];
					break;
				}
			} else {
				/**
				 * Otherwise it uses the first matching author with the given
				 * email or name
				 */
				if (
					in_array($email, array_map('strtolower', $author))
					|| in_array($name, array_map('strtolower', $author))
				) {
					$email = $author[0];
					$name = $author[1];
					break;
				}
			}
		}

		$this->email = $email;
		$this->name = $name;
	}

	/**
	 * Returns the author email
	 *
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * Returns the author name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Returns the author commits
	 *
	 * @return CommitResource[]
	 */
	public function getCommits(): array
	{
		$list = AuthorGitCommand::getCommits($this->email, $this->name);

		return array_map(function (string $hash) {
			$Commit = new CommitResource($hash);

			return $Commit;
		}, $list);
	}
}
