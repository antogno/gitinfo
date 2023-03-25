<?php

namespace antogno\GitInfo\Utils\GitCommand;

use antogno\GitInfo\GitInfoException;
use Exception;

/**
 * Files GitCommand class
 * 
 * This class is still work in progress, meaning it doesn't work correctly
 *
 * @author Antonio Granaldi <tonio.granaldi@gmail.com>
 */
class FilesGitCommand {

	/**
	 * Returns any deleted file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the deleted files list could not be retrieved
	 */
	public static function getDeletedFiles(bool $full_path = false): array
	{
		try {
			$files = GitCommand::exec('ls-files', [
				'--deleted',
				'--exclude-standard',
				'--full-name'
			]);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the deleted files list',
				$e
			);
		}

		return self::parseLsFilesOutput($files, $full_path);
	}

	/**
	 * Returns any modified file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the modified files list could not be
	 * retrieved
	 */
	public static function getModifiedFiles(bool $full_path = false): array
	{
		try {
			$files = GitCommand::exec('ls-files', [
				'--modified',
				'--exclude-standard',
				'--full-name'
			]);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the modified files list',
				$e
			);
		}

		return self::parseLsFilesOutput($files, $full_path);
	}

	/**
	 * Returns any renamed file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the renamed files list could not be retrieved
	 */
	public static function getRenamedFiles(bool $full_path = false): array
	{
		$absolute_path = GitCommand::getAbsolutePath() . '/';

		$untracked_files = self::getUntrackedFiles(true);
		$deleted_files = self::getDeletedFiles(false);

		$renamed_files = [];

		foreach ($deleted_files as $deleted_file) {
			foreach ($untracked_files as $untracked_file) {
				try {
					$output = GitCommand::exec(
						'diff',
						[],
						["HEAD:$deleted_file", $untracked_file]
					);
				} catch (Exception $e) {
					continue;
				}

				if (empty($output)) {
					$deleted_file = $full_path
						? $absolute_path . $deleted_file
						: $deleted_file;
					$untracked_file = $full_path
						? $untracked_file
						: str_replace($absolute_path, '', $untracked_file);

					$renamed_files[$deleted_file] = $untracked_file;
					break;
				}
			}
		}

		return $renamed_files;
	}

	/**
	 * Returns any staged file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the staged files list could not be retrieved
	 */
	public static function getStagedFiles(bool $full_path = false): array
	{
		$options = ['--cached', '--name-only'];

		if ($full_path) {
			$options['--line-prefix'] = GitCommand::getAbsolutePath() . '/';
		}

		try {
			$files = GitCommand::exec('diff', $options);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the staged files list',
				$e
			);
		}

		return $files;
	}

	/**
	 * Returns any unmerged file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the unmerged files list could not be
	 * retrieved
	 */
	public static function getUnmergedFiles(bool $full_path = false): array
	{
		try {
			$files = GitCommand::exec('ls-files', [
				'--unmerged',
				'--exclude-standard',
				'--full-name'
			]);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the unmerged files list',
				$e
			);
		}

		return self::parseLsFilesOutput($files, $full_path);
	}

	/**
	 * Returns any unstaged file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the unstaged files list could not be
	 * retrieved
	 */
	public static function getUnstagedFiles(bool $full_path = false): array
	{
		try {
			$files = GitCommand::exec('ls-files', [
				'--modified',
				'--deleted',
				'--unmerged',
				'--killed',
				'--exclude-standard',
				'--full-name'
			]);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the unstaged files list',
				$e
			);
		}

		return self::parseLsFilesOutput($files, $full_path);
	}

	/**
	 * Returns any untracked file
	 *
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[]
	 *
	 * @throws GitInfoException if the untracked files list could not be
	 * retrieved
	 */
	public static function getUntrackedFiles(bool $full_path = false): array
	{
		try {
			$files = GitCommand::exec('ls-files', [
				'--others',
				'--exclude-standard',
				'--full-name'
			]);
		} catch (Exception $e) {
			throw new GitInfoException(
				'Could not get the untracked files list',
				$e
			);
		}

		return self::parseLsFilesOutput($files, $full_path);
	}

	/**
	 * Parses a "ls-files" Git command output so that it only shows the file
	 * name
	 *
	 * @param array $output retrieved ouput from the "ls-files" Git command
	 * @param bool $full_path whether to get the files full path or not
	 *
	 * @return string[] array where each item is a valid file path
	 */
	private static function parseLsFilesOutput(array $output, bool $full_path): array
	{
		$prefix = $full_path ? GitCommand::getAbsolutePath() . '/' : '';

		return array_values(
			array_unique(
				array_map(function (string $line) use ($prefix) {
					// A line could be like "100644 3937ae8a391ca34c321f916496a2f261f266976f 0       src/GitInfo.php"
					$line_array = explode(
						' ',
						trim(preg_replace('/\s+/', ' ', $line))
					);

					return $prefix . array_pop($line_array);
				}, $output)
			)
		);
	}
}
