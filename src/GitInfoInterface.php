<?php

namespace antogno\GitInfo;

use antogno\GitInfo\Resources\AuthorResource;
use antogno\GitInfo\Resources\BranchResource;
use antogno\GitInfo\Resources\CommitResource;
use antogno\GitInfo\Resources\RemoteResource;
use antogno\GitInfo\Resources\TagResource;

/**
 * GitInfo interface
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
interface GitInfoInterface {

	/**
	 * Returns the Git version
	 *
	 * @return string
	 */
	public function getGitVersion(): string;

	/**
	 * Retrieves the author of the current commit
	 *
	 * @return AuthorResource
	 */
	public function getCurrentCommitAuthor(): AuthorResource;

	/**
	 * Retrieves the given author, if exists
	 *
	 * If only one of email and name is given and there are multiple authors
	 * with the same given email or name, the first matching author will be used
	 *
	 * @param string $email author email, case insensitive
	 * @param string $name author name, case insensitive
	 *
	 * @return AuthorResource
	 */
	public function getAuthor(string $email = '', string $name = ''): AuthorResource;

	/**
	 * Retrieves the authors list
	 *
	 * @return AuthorResource[]
	 */
	public function getAuthors(): array;

	/**
	 * Retrieves the current branch
	 *
	 * @return BranchResource
	 */
	public function getCurrentBranch(): BranchResource;

	/**
	 * Retrieves the given branch, if exists
	 *
	 * @param string $name branch name, case sensitive
	 *
	 * @return BranchResource
	 */
	public function getBranch(string $name): BranchResource;

	/**
	 * Retrieves the branches list
	 *
	 * @return BranchResource[]
	 */
	public function getBranches(): array;

	/**
	 * Retrieves the current commit
	 *
	 * @return CommitResource
	 */
	public function getCurrentCommit(): CommitResource;

	/**
	 * Retrieves the given commit, if exists
	 *
	 * @param string $hash long or short commit hash
	 *
	 * @return CommitResource
	 */
	public function getCommit(string $hash): CommitResource;

	/**
	 * Retrieves the commits list
	 *
	 * @return CommitResource[]
	 */
	public function getCommits(): array;

	/**
	 * Retrieves the current tag, if in a tag
	 *
	 * @return TagResource
	 */
	public function getCurrentTag(): TagResource;

	/**
	 * Retrieves the given tag, if exists
	 *
	 * @param string $name tag name, case sensitive
	 *
	 * @return TagResource
	 */
	public function getTag(string $name): TagResource;

	/**
	 * Retrieves the tags list
	 *
	 * @return TagResource[]
	 */
	public function getTags(): array;

	/**
	 * Retrieves the remote from which the current branch is tracking
	 *
	 * @return RemoteResource
	 */
	public function getCurrentRemote(): RemoteResource;

	/**
	 * Retrieves the given remote, if exists
	 *
	 * @param string $name remote name, case sensitive
	 *
	 * @return RemoteResource
	 */
	public function getRemote(string $name): RemoteResource;

	/**
	 * Retrieves the remotes list
	 *
	 * @return RemoteResource[]
	 */
	public function getRemotes(): array;

	/**
	 * Retrieves any deleted file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getDeletedFiles(bool $full_path = false): array;

	/**
	 * Retrieves any modified file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getModifiedFiles(bool $full_path = false): array;

	/**
	 * Retrieves any renamed file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getRenamedFiles(bool $full_path = false): array;

	/**
	 * Retrieves any unmerged file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getUnmergedFiles(bool $full_path = false): array;

	/**
	 * Retrieves any untracked file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getUntrackedFiles(bool $full_path = false): array;

	/**
	 * Retrieves any staged file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getStagedFiles(bool $full_path = false): array;

	/**
	 * Retrieves any unstaged file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 */
	public function getUnstagedFiles(bool $full_path = false): array;

	/**
	 * Whether the given author exists or not
	 *
	 * @param string $email author email to search for, case insensitive
	 * @param string $name author name to search for, case insensitive
	 *
	 * @return bool
	 */
	public function hasAuthor(string $email = '', string $name = ''): bool;

	/**
	 * Whether the given branch exists or not
	 *
	 * @param string $name branch name to search for, case sensitive
	 *
	 * @return bool
	 */
	public function hasBranch(string $name): bool;

	/**
	 * Whether a commit with the given hash exists or not
	 *
	 * @param string $hash long or short hash to search for
	 *
	 * @return bool
	 */
	public function hasCommit(string $hash): bool;

	/**
	 * Whether the given tag exists or not
	 *
	 * @param string $name tag name to search for, case sensitive
	 *
	 * @return bool
	 */
	public function hasTag(string $name): bool;

	/**
	 * Whether the given remote exists or not
	 *
	 * @param string $name remote name to search for, case sensitive
	 *
	 * @return bool
	 */
	public function hasRemote(string $name): bool;
}
