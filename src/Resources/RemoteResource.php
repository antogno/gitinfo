<?php

namespace antogno\GitInfo\Resources;

use antogno\GitInfo\GitInfoException;
use antogno\GitInfo\Utils\GitCommand\RemoteGitCommand;

/**
 * Remote resource class
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class RemoteResource {
	
	/**
	 * Remote name
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * Remote URL
	 *
	 * @var string
	 */
	protected string $url;

	/**
	 * @param string $name remote name, case sensitive
	 *
	 * @return void
	 *
	 * @throws GitInfoException if a valid remote could not be retrieved
	 */
	public function __construct(string $name)
	{
		$name = trim($name);

		if (!RemoteGitCommand::exists($name)) {
			throw new GitInfoException("Could not get remote \"$name\"");
		}

		$this->name = $name;
		$this->url = RemoteGitCommand::getRemoteUrl($name);
	}

	/**
	 * Returns the remote name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Returns the remote URL
	 *
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}
}
