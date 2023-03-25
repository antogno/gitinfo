# GitInfo

<p>
    <a href="https://github.com/antogno/gitinfo/blob/master/LICENSE"><img src="https://img.shields.io/github/license/antogno/gitinfo" alt="License"></a>
    <a href="https://github.com/antogno/gitinfo/commits"><img src="https://img.shields.io/github/last-commit/antogno/gitinfo" alt="Last commit"></a>
    <a href="https://github.com/antogno/gitinfo/releases/latest"><img src="https://img.shields.io/github/v/tag/antogno/gitinfo?label=last%20release" alt="Last release"></a>
</p>

GitInfo is a tool that lets you get information about the current Git repository.

---

## Installation

Use the dependency manager [Composer](https://getcomposer.org/download/) to install GitInfo.

```console
$ composer require antogno/gitinfo
```

## Usage

```php
use antogno\GitInfo\GitInfo;

$GitInfo = new GitInfo();
```

Get the Git version:

```php
$GitInfo->getGitVersion();
// For example: string(6) "2.40.0"
```

Get any deleted file:

```php
$GitInfo->getDeletedFiles(false);
// For example: array(2) { [0]=> string(13) "deleted_1.php" [1]=> string(13) "deleted_2.php" }

$GitInfo->getDeletedFiles(true);
// For example: array(2) { [0]=> string(36) "/Users/antogno/gitinfo/deleted_1.php" [1]=> string(36) "/Users/antogno/gitinfo/deleted_2.php" }
```

Get any modified file:

```php
$GitInfo->getModifiedFiles(false);
// For example: array(2) { [0]=> string(14) "modified_1.php" [1]=> string(14) "modified_2.php" }

$GitInfo->getModifiedFiles(true);
// For example: array(2) { [0]=> string(37) "/Users/antogno/gitinfo/modified_1.php" [1]=> string(37) "/Users/antogno/gitinfo/modified_2.php" }
```

Get any renamed file:

```php
$GitInfo->getRenamedFiles(false);
// For example: array(1) { ["old_name.php"]=> string(12) "new_name.php" }

$GitInfo->getRenamedFiles(true);
// For example: array(1) { ["/Users/antogno/gitinfo/old_name.php"]=> string(35) "/Users/antogno/gitinfo/new_name.php" }
```

Get any unmerged file:

```php
$GitInfo->getUnmergedFiles(false);
// For example: array(2) { [0]=> string(14) "unmerged_1.php" [1]=> string(14) "unmerged_2.php" }

$GitInfo->getUnmergedFiles(true);
// For example: array(2) { [0]=> string(37) "/Users/antogno/gitinfo/unmerged_1.php" [1]=> string(37) "/Users/antogno/gitinfo/unmerged_2.php" }
```

Get any untracked file:

```php
$GitInfo->getUntrackedFiles(false);
// For example: array(2) { [0]=> string(15) "untracked_1.php" [1]=> string(15) "untracked_2.php" }

$GitInfo->getUntrackedFiles(true);
// For example: array(2) { [0]=> string(38) "/Users/antogno/gitinfo/untracked_1.php" [1]=> string(38) "/Users/antogno/gitinfo/untracked_2.php" }
```

Get any staged file:

```php
$GitInfo->getStagedFiles(false);
// For example: array(2) { [0]=> string(12) "modified.php" [1]=> string(11) "deleted.php" }

$GitInfo->getStagedFiles(true);
// For example: array(2) { [0]=> string(35) "/Users/antogno/gitinfo/modified.php" [1]=> string(34) "/Users/antogno/gitinfo/deleted.php" }
```

Get any unstaged file:

```php
$GitInfo->getUnstagedFiles(false);
// For example: array(2) { [0]=> string(12) "modified.php" [1]=> string(11) "deleted.php" }

$GitInfo->getUnstagedFiles(true);
// For example: array(2) { [0]=> string(35) "/Users/antogno/gitinfo/modified.php" [1]=> string(34) "/Users/antogno/gitinfo/deleted.php" }
```

### Author

Whether the given author exists or not:

```php
$GitInfo->hasAuthor('tonio.granaldi@gmail.com', 'antogno');
// For example: bool(true)

$GitInfo->hasAuthor('', 'johndoe');
// For example: bool(false)

$GitInfo->hasAuthor('TONIO.GRANALDI@GMAIL.COM', 'ANTOGNO');
// For example: bool(true)
```

Get the author of the current commit:

```php
$GitInfo->getCurrentCommitAuthor();
```

Get the given author, if exists:

```php
$GitInfo->getAuthor('tonio.granaldi@gmail.com', 'antogno');

$GitInfo->getAuthor('', 'antogno');

$GitInfo->getAuthor('TONIO.GRANALDI@GMAIL.COM');
```

Get the authors list:

```php
$GitInfo->getAuthors();
```

Each of the previous three methods returns an [`AuthorResource`](#authorresource) object (or a list of such).

#### `AuthorResource`

```php
use antogno\GitInfo\Resources\AuthorResource;

$Author = new AuthorResource('tonio.granaldi@gmail.com', 'antogno');

$Author = new AuthorResource('', 'antogno');

$Author = new AuthorResource('TONIO.GRANALDI@GMAIL.COM');
```

Get the author name:

```php
$Author->getName();
// For example: string(7) "antogno"
```

Get the author email:

```php
$Author->getEmail();
// For example: string(24) "tonio.granaldi@gmail.com"
```

Get the author commits ([`CommitResource`](#commitresource) list):

```php
$Author->getCommits();
```

### Branch

Whether the given branch exists or not:

```php
$GitInfo->hasBranch('master');
// For example: bool(true)

$GitInfo->hasBranch('MASTER');
// For example: bool(false)
```

Get the current branch:

```php
$GitInfo->getCurrentBranch();
```

Get the given branch, if exists:

```php
$GitInfo->getBranch('master');
```

Get the branches list:

```php
$GitInfo->getBranches();
```

Each of the previous three methods returns a [`BranchResource`](#branchresource) object (or a list of such).

#### `BranchResource`

```php
use antogno\GitInfo\Resources\BranchResource;

$Branch = new BranchResource('master');
```

Get the author name:

```php
$Branch->getName();
// For example: string(6) "master"
```

Get the branch last commit ([`CommitResource`](#commitresource)):

```php
$Branch->getLastCommit();
```

### Commit

Whether a commit with the given hash exists or not:

```php
$GitInfo->hasCommit('ed8f9325485f108ddafe3890dc4b13be07aa13cb');
// For example: bool(true)

$GitInfo->hasCommit('ed8f932');
// For example: bool(true)
```

Get the current commit:

```php
$GitInfo->getCurrentCommit();
```

Get the given commit, if exists:

```php
$GitInfo->getCommit('ed8f9325485f108ddafe3890dc4b13be07aa13cb');

$GitInfo->getCommit('ed8f932');
```

Get the commits list:

```php
$GitInfo->getCommits();
```

Each of the previous three methods returns a [`CommitResource`](#commitresource) object (or a list of such).

#### `CommitResource`

```php
use antogno\GitInfo\Resources\CommitResource;

$Commit = new CommitResource('ed8f9325485f108ddafe3890dc4b13be07aa13cb');

$Commit = new CommitResource('ed8f932');
```

Get the long commit hash:

```php
$Commit->getLongHash();
// For example: string(40) "ed8f9325485f108ddafe3890dc4b13be07aa13cb"
```

Get the short commit hash:

```php
$Commit->getShortHash();
// For example: string(7) "ed8f932"
```

Get the commit message:

```php
$Commit->getMessage();
// For example: string(14) "Initial commit"
```

Get the commit date (`DateTime`):

```php
$Commit->getDate();
```

Get the commit author ([`AuthorResource`](#authorresource)):

```php
$Commit->getAuthor();
```

### Tag

Whether the given tag exists or not:

```php
$GitInfo->hasTag('v1.0.0');
// For example: bool(true)

$GitInfo->hasTag('V1.0.0');
// For example: bool(false)
```

Get the current tag, if in a tag:

```php
$GitInfo->getCurrentTag();
```

Get the given tag, if exists:

```php
$GitInfo->getTag('v1.0.0');
```

Get the tags list:

```php
$GitInfo->getTags();
```

Each of the previous three methods returns a [`TagResource`](#tagresource) object (or a list of such).

#### `TagResource`

```php
use antogno\GitInfo\Resources\TagResource;

$Tag = new TagResource('v1.0.0');
```

Get the tag name:

```php
$Tag->getName();
// For example: string(6) "v1.0.0"
```

Get the tag last commit ([`CommitResource`](#commitresource)):

```php
$Tag->getLastCommit();
```

### Remote

Whether the given remote exists or not:

```php
$GitInfo->hasRemote('origin');
// For example: bool(true)

$GitInfo->hasRemote('ORIGIN');
// For example: bool(false)
```

Get the remote from which the current branch is tracking:

```php
$GitInfo->getCurrentRemote();
```

Get the given remote, if exists:

```php
$GitInfo->getRemote('origin');
```

Get the remotes list:

```php
$GitInfo->getRemotes();
```

Each of the previous three methods returns a [`RemoteResource`](#remoteresource) object (or a list of such).

#### `RemoteResource`

```php
use antogno\GitInfo\Resources\RemoteResource;

$Remote = new RemoteResource('origin');
```

Get the remote name:

```php
$Remote->getName();
// For example: string(6) "origin"
```

Get the remote URL:

```php
$Remote->getUrl();
// For example: string(38) "https://github.com/antogno/gitinfo.git"
```

## License

GitInfo is licensed under the terms of the [Creative Commons Zero v1.0 Universal license](https://github.com/antogno/gitinfo/blob/master/LICENSE).

For more information, see the [Creative Commons website](https://creativecommons.org/publicdomain/zero/1.0/).
