<?php

namespace antogno\GitInfo;

use antogno\GitInfo\Resources\AuthorResource;
use antogno\GitInfo\Resources\BranchResource;
use antogno\GitInfo\Resources\CommitResource;
use antogno\GitInfo\Resources\RemoteResource;
use antogno\GitInfo\Resources\TagResource;
use antogno\GitInfo\Utils\GitCommand\AuthorGitCommand;
use antogno\GitInfo\Utils\GitCommand\BranchGitCommand;
use antogno\GitInfo\Utils\GitCommand\CommitGitCommand;
use antogno\GitInfo\Utils\GitCommand\FilesGitCommand;
use antogno\GitInfo\Utils\GitCommand\GitCommand;
use antogno\GitInfo\Utils\GitCommand\RemoteGitCommand;
use antogno\GitInfo\Utils\GitCommand\TagGitCommand;

/**
 * GitInfo class
 *
 * Get information about the current Git repository
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class GitInfo implements GitInfoInterface {

	/**
	 * The minimum required Git version
	 *
	 * This is not necessarily the minimum version needed for GitInfo to work
	 * properly, but it's the minimum version it was successfully tested against
	 *
	 * @var string
	 */
	protected const MIN_GIT_VERSION = '2.22.0';

	public function __construct()
	{
		$current_git_version = GitCommand::getVersion();

		if (
			version_compare($current_git_version, self::MIN_GIT_VERSION) === -1
		) {
			throw new GitInfoException(
				"The minimum required Git version \"" . self::MIN_GIT_VERSION . "\" is greater than the current \"$current_git_version\""
			);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getGitVersion(): string
	{
		return GitCommand::getVersion();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCurrentCommitAuthor(): AuthorResource
	{
		$author = AuthorGitCommand::getCurrent();

		$author_array = explode(' ', $author);

		$Author = new AuthorResource(
			array_shift($author_array),
			implode(' ', $author_array)
		);

		return $Author;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAuthor(string $email = '', string $name = ''): AuthorResource
	{
		$Author = new AuthorResource($email, $name);

		return $Author;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAuthors(): array
	{
		$list = AuthorGitCommand::getList();

		return array_map(function (array $author) {
			$Author = new AuthorResource($author[0], $author[1]);

			return $Author;
		}, $list);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCurrentBranch(): BranchResource
	{
		$branch = BranchGitCommand::getCurrent();

		$Branch = new BranchResource($branch);

		return $Branch;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBranch(string $name): BranchResource
	{
		$Branch = new BranchResource($name);

		return $Branch;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBranches(): array
	{
		$list = BranchGitCommand::getList();

		return array_map(function (string $branch) {
			$Branch = new BranchResource($branch);

			return $Branch;
		}, $list);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCurrentCommit(): CommitResource
	{
		$commit = CommitGitCommand::getCurrent();

		$Commit = new CommitResource($commit);

		return $Commit;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCommit(string $hash): CommitResource
	{
		$Commit = new CommitResource($hash);

		return $Commit;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCommits(): array
	{
		$list = CommitGitCommand::getList();

		return array_map(function (string $hash) {
			$Commit = new CommitResource($hash);

			return $Commit;
		}, $list);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCurrentTag(): TagResource
	{
		$tag = TagGitCommand::getCurrent();

		$Tag = new TagResource($tag);

		return $Tag;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTag(string $name): TagResource
	{
		$Tag = new TagResource($name);

		return $Tag;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTags(): array
	{
		$list = TagGitCommand::getList();

		return array_map(function (string $tag) {
			$Tag = new TagResource($tag);

			return $Tag;
		}, $list);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCurrentRemote(): RemoteResource
	{
		$remote = RemoteGitCommand::getCurrent();

		$Remote = new RemoteResource($remote);

		return $Remote;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRemote(string $name): RemoteResource
	{
		$Remote = new RemoteResource($name);

		return $Remote;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRemotes(): array
	{
		$list = RemoteGitCommand::getList();

		return array_map(function (string $remote) {
			$Remote = new RemoteResource($remote);

			return $Remote;
		}, $list);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDeletedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getDeletedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getModifiedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getModifiedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRenamedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getRenamedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUnmergedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getUnmergedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUntrackedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getUntrackedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getStagedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getStagedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUnstagedFiles(bool $full_path = false): array
	{
		return FilesGitCommand::getUnstagedFiles($full_path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasAuthor(string $email = '', string $name = ''): bool
	{
		return AuthorGitCommand::exists($email, $name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasBranch(string $name): bool
	{
		return BranchGitCommand::exists($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasCommit(string $hash): bool
	{
		return CommitGitCommand::exists($hash);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasTag(string $name): bool
	{
		return TagGitCommand::exists($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasRemote(string $name): bool
	{
		return RemoteGitCommand::exists($name);
	}
}
