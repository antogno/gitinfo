<?php

namespace antogno\GitInfo\Resources;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Utils\GitCommand\BranchGitCommand;

/**
 * Branch resource class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class BranchResource {
	
	/**
	 * Branch name
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * @param string $name branch name, case sensitive
	 *
	 * @return void
	 *
	 * @throws GitInfoException if a valid branch could not be retrieved
	 */
	public function __construct(string $name)
	{
		$name = trim($name);

		if (!BranchGitCommand::exists($name)) {
			throw new GitInfoException("Could not get branch \"$name\"");
		}

		$this->name = $name;
	}

	/**
	 * Returns the branch name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Returns the branch last commit
	 *
	 * @return CommitResource
	 */
	public function getLastCommit(): CommitResource
	{
		$hash = BranchGitCommand::getLastCommit($this->name);

		$LastCommit = new CommitResource($hash);

		return $LastCommit;
	}
}
