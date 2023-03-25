<?php

namespace antogno\GitInfo\Resources;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Utils\GitCommand\TagGitCommand;

/**
 * Tag resource class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class TagResource {
	
	/**
	 * Tag name
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * @param string $name tag name, case sensitive
	 *
	 * @return void
	 *
	 * @throws GitInfoException if a valid tag could not be retrieved
	 */
	public function __construct(string $name)
	{
		$name = trim($name);

		if (!TagGitCommand::exists($name)) {
			throw new GitInfoException("Could not get tag \"$name\"");
		}

		$this->name = $name;
	}

	/**
	 * Returns the tag name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Returns the tag last commit
	 *
	 * @return CommitResource
	 */
	public function getLastCommit(): CommitResource
	{
		$hash = TagGitCommand::getLastCommit($this->name);

		$LastCommit = new CommitResource($hash);

		return $LastCommit;
	}
}
