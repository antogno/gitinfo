<?php

namespace antogno\GitInfo\Resources;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Utils\GitCommand\CommitGitCommand;
use antogno\GitInfo\Validators\HashValidator;
use DateTime;

/**
 * Commit resource class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class CommitResource {

	/**
	 * Long commit hash
	 *
	 * @var string
	 */
	protected string $long_hash;

	/**
	 * Short commit hash
	 *
	 * @var string
	 */
	protected string $short_hash;

	/**
	 * Commit message
	 *
	 * @var string
	 */
	protected string $message;

	/**
	 * Commit date
	 *
	 * @var DateTime
	 */
	protected DateTime $date;

	/**
	 * Commit author
	 *
	 * @var AuthorResource
	 */
	protected AuthorResource $Author;

	/**
	 * @param string $hash long or short commit hash
	 *
	 * @return void
	 *
	 * @throws GitInfoException if the given string is not a valid hash, if a
	 * valid commit from the given hash could not be retrieved, or if a valid
	 * commit date could not be retrieved
	 */
	public function __construct(string $hash)
	{
		$Validator = new HashValidator();

		$hash = $Validator->escape($hash);

		if (!$Validator->isValid($hash)) {
			throw new GitInfoException("\"$hash\" is not a valid SHA-1 hash");
		}

		if (!CommitGitCommand::exists($hash)) {
			throw new GitInfoException("Could not get commit \"$hash\"");
		}

		$this->long_hash = CommitGitCommand::getLongHash($hash);
		$this->short_hash = CommitGitCommand::getShortHash($hash);
		$this->message = CommitGitCommand::getMessage($hash);

		$iso8601_date = CommitGitCommand::getDate($hash);
		$date = DateTime::createFromFormat('Y-m-d H:i:s O', $iso8601_date);
		if ($date === false) {
			throw new GitInfoException(
				"Could not instantiate a valid DateTime object from \"$iso8601_date\""
			);
		}

		$this->date = $date;

		$author = CommitGitCommand::getAuthor($this->long_hash);

		$author_array = explode(' ', $author);

		$this->Author = new AuthorResource(
			array_shift($author_array),
			implode(' ', $author_array)
		);
	}

	/**
	 * Returns the long commit hash
	 *
	 * @return string
	 */
	public function getLongHash(): string
	{
		return $this->long_hash;
	}

	/**
	 * Returns the short commit hash
	 *
	 * @return string
	 */
	public function getShortHash(): string
	{
		return $this->short_hash;
	}

	/**
	 * Returns the commit message
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * Returns the commit date
	 *
	 * @return DateTime
	 */
	public function getDate(): DateTime
	{
		return $this->date;
	}

	/**
	 * Returns the commit author
	 *
	 * @return AuthorResource
	 */
	public function getAuthor(): AuthorResource
	{
		return $this->Author;
	}
}
