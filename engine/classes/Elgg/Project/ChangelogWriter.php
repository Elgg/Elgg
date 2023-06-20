<?php

namespace Elgg\Project;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * Helper class to write the changelog during release
 *
 * @since 5.1
 * @internal
 */
class ChangelogWriter {
	
	protected array $options;
	
	protected array $commit_types = [
		'feature' => 'Features',
		'performance' => 'Performance',
		'documentation' => 'Documentation',
		'fix' => 'Bug fixes',
		'deprecated' => 'Deprecations',
		'breaking' => 'Breaking Changes',
		'removed' => 'Removed',
	];
	
	/**
	 * Constructor
	 *
	 * @param array $options writer options
	 */
	public function __construct(array $options = []) {
		$defaults = [
			'changelog' => Paths::elgg() . 'CHANGELOG.md',
			'version' => null,
			'notes' => '',
			'repository' => 'https://github.com/Elgg/Elgg/',
		];
		
		$options = array_merge($defaults, $options);
		if (empty($options['version'])) {
			throw new InvalidArgumentException('Please provide a release version number');
		}
		
		if (!file_exists($options['changelog']) || !is_writable($options['changelog'])) {
			throw new InvalidArgumentException("The changelog file doesn't exist or is not writable");
		}
		
		$this->options = $options;
	}
	
	/**
	 * Write the changelog for the current release
	 *
	 * @return void
	 */
	public function __invoke(): void {
		$tags = $this->getGitTags();
		
		$sections = [];
		
		$sections[] = $this->formatHeader();
		$sections[] = $this->readNotes();
		
		$contributors = $this->getGitContributors([
			'exclude' => $tags,
		]);
		$sections[] = $this->formatContributors($contributors);
		
		$commits = $this->getGitCommits([
			'exclude' => $tags,
		]);
		$sections[] = $this->formatCommits($commits);
		
		$sections = array_filter($sections);
		$output = trim(implode(PHP_EOL . PHP_EOL, $sections));
		
		$this->writeChangelog($output);
	}
	
	/**
	 * Read anything in the changelog before the first '<a name="">' and consider this release notes
	 *
	 * @return string
	 */
	protected function readNotes(): string {
		$contents = file_get_contents($this->getOption('changelog'));
		$first_anchor = strpos($contents, '<a name="');
		if ($first_anchor === false) {
			return trim($this->getOption('notes', ''));
		}
		
		return trim($this->getOption('notes', '') . substr($contents, 0, $first_anchor));
	}
	
	/**
	 * Get the current git tags
	 *
	 * @return array
	 */
	protected function getGitTags(): array {
		return $this->executeCommand('git tag') ?: [];
	}
	
	/**
	 * Get all the commits
	 *
	 * @param array $options options
	 *
	 * @return array
	 */
	protected function getGitCommits(array $options): array {
		$defaults = [
			'grep' => '^[a-z]+(\(.*\))?:|BREAKING',
			'format' => '%H%n%h%n%s%n%b%n==END==',
			'exclude' => [],
			'to' => 'HEAD',
		];
		$options = array_merge($defaults, $options);
		
		$command = vsprintf('git log --grep="%s" -E --format=%s %s %s', [
			$options['grep'],
			$options['format'],
			$options['to'],
			implode(' ', array_map(function ($value) {
				if (str_contains(PHP_OS, 'WIN')) {
					return "^^{$value}";
				}
				
				return "^{$value}";
			}, $options['exclude'])),
		]);
		
		$commits = $this->executeCommand($command);
		if (!isset($commits)) {
			return [];
		}
		
		$results = [];
		$result = [
			'body' => '',
		];
		$index = 0;
		$subject_pattern = '/^((Merge )|(Revert )|((\w*)\(([\w]+)\)\: ([^\n]*))$)/';
		foreach ($commits as $line) {
			if ($line === '==END==') {
				$result['body'] = trim($result['body'] ?: '', PHP_EOL);
				
				$results[] = $result;
				$index = 0;
				$result = [
					'body' => '',
				];
				continue;
			}
			
			switch ($index) {
				case 0: // long hash
					$result['hash'] = $line;
					break;
				
				case 1: // short hash
					$result['short_hash'] = $line;
					break;
					
				case 2: // subject
					$matches = [];
					preg_match($subject_pattern, $line, $matches);
					
					$result['type'] = $matches[5] ?? 'skip';
					$result['component'] = $matches[6] ?? '';
					$result['subject'] = $matches[7] ?? '';
					break;
					
				default: // the rest of the commit body
					if (empty($line)) {
						break;
					}
					
					$result['body'] .= $line . PHP_EOL;
					break;
			}
			
			$index++;
		}
		
		$filtered = [];
		$fixes_pattern = '/(closes|fixes)\s+#(\d+)/i';
		foreach ($results as $result) {
			if ($result['type'] === 'skip') {
				continue;
			}
			
			// check if the commit contains a breaking change
			if (str_contains(strtolower($result['body']), 'breaking change:')) {
				$result['type'] = 'break';
			}
			
			// see if the commit fixed/closed issues
			$matches = [];
			preg_match_all($fixes_pattern, $result['body'], $matches);
			if (!empty($matches) && !empty($matches[2])) {
				$result['closes'] = array_map(function ($value) {
					return (int) $value;
				}, $matches[2]);
			}
			
			$filtered[] = $result;
		}
		
		return $filtered;
	}
	
	/**
	 * Get the contributors
	 *
	 * @param array $options options
	 *
	 * @return array
	 */
	protected function getGitContributors(array $options = []): array {
		$defaults = [
			'exclude' => [],
			'to' => 'HEAD',
		];
		$options = array_merge($defaults, $options);
		
		$command = vsprintf('git shortlog -sne %s --no-merges %s', [
			$options['to'],
			implode(' ', array_map(function ($value) {
				if (str_contains(PHP_OS, 'WIN')) {
					return "^^{$value}";
				}
				
				return "^{$value}";
			}, $options['exclude'])),
		]);
		
		$contributors = $this->executeCommand($command);
		if (!isset($contributors)) {
			return [];
		}
		
		$contributor_pattern = '/\s+([0-9]+)\s+(.*)\s<(.*)>/';
		$result = [];
		foreach ($contributors as $contributor) {
			$matches = [];
			preg_match($contributor_pattern, $contributor, $matches);
			if (empty($matches)) {
				continue;
			}
			
			$result[] = [
				'count' => (int) $matches[1],
				'name' => $matches[2],
				'email' => $matches[3],
			];
		}
		
		// sort the contributors with most contributed first
		usort($result, function ($a, $b) {
			return $b['count'] - $a['count'];
		});
		
		return $result;
	}
	
	/**
	 * Format the different commits into sections
	 *
	 * @param array $commits all the commits
	 *
	 * @return string
	 */
	protected function formatCommits(array $commits): string {
		if (empty($commits)) {
			return '';
		}
		
		// group commits by type
		$types = [];
		foreach ($commits as $commit) {
			$type = $commit['type'];
			if (str_starts_with($type, 'feat')) {
				$type = 'feature';
			} elseif (str_starts_with($type, 'fix')) {
				$type = 'fix';
			} elseif (str_starts_with($type, 'perf')) {
				$type = 'performance';
			} elseif (str_starts_with($type, 'doc')) {
				$type = 'documentation';
			} elseif (str_starts_with($type, 'deprecate')) {
				$type = 'deprecated';
			} elseif (str_starts_with($type, 'break')) {
				$type = 'breaking';
			} elseif (str_starts_with($type, 'remove')) {
				$type = 'removed';
			} else {
				continue;
			}
			
			if (!isset($types[$type])) {
				$types[$type] = [];
			}
			
			$component = $commit['component'];
			if (!isset($types[$type][$component])) {
				$types[$type][$component] = [];
			}
			
			$subject = $commit['subject'];
			$commit_link = $this->makeCommitLink($commit);
			$closes = '';
			if (!empty($commit['closes'])) {
				$closes .= 'closes ';
				foreach ($commit['closes'] as $issue_id) {
					$closes .= $this->makeIssueLink($issue_id) . ', ';
				}
			}
			
			$types[$type][$component][] = trim(vsprintf('%s %s %s', [
				$subject,
				$commit_link,
				$closes,
			]), ' ,');
		}
		
		if (empty($types)) {
			return '';
		}
		
		// format the different types into sections
		$sections = [];
		foreach ($this->commit_types as $type => $label) {
			if (!isset($types[$type])) {
				continue;
			}
			
			$section = "#### {$label}" . PHP_EOL . PHP_EOL;
			
			foreach ($types[$type] as $component => $commits) {
				if (count($commits) === 1) {
					$section .= "* **{$component}:** {$commits[0]}" . PHP_EOL;
				} else {
					$section .= "* **{$component}:**" . PHP_EOL;
					
					foreach ($commits as $commit) {
						$section .= '  * ' . $commit . PHP_EOL;
					}
				}
			}
			
			$sections[] = $section;
		}
		
		return trim(implode(PHP_EOL . PHP_EOL, $sections));
	}
	
	/**
	 * Format the contributors into a section
	 *
	 * @param array $contributors contributors
	 *
	 * @return string
	 */
	protected function formatContributors(array $contributors): string {
		if (empty($contributors)) {
			return '';
		}
		
		$section = '#### Contributors' . PHP_EOL . PHP_EOL;
		
		foreach ($contributors as $contributor) {
			$section .= "* {$contributor['name']} ({$contributor['count']})" . PHP_EOL;
		}
		
		return trim($section);
	}
	
	/**
	 * Format release header
	 *
	 * @return string
	 */
	protected function formatHeader(): string {
		$version = $this->getOption('version', '');
		$parts = explode('.', $version);
		$date = date('Y-m-d');
		
		$section = '<a name="' . $version . '"></a>' . PHP_EOL;
		if ($parts[2] === '0') {
			// major version
			$section .= "## {$version} ({$date})";
		} else {
			// patch version
			$section .= "### {$version} ({$date})";
		}
		
		return trim($section);
	}
	
	/**
	 * Get a link to a GitHub commit
	 *
	 * @param array $commit commit information
	 *
	 * @return string
	 */
	protected function makeCommitLink(array $commit): string {
		if (empty($commit)) {
			return '';
		}
		
		return vsprintf('[%s](%s/commit/%s)', [
			$commit['short_hash'],
			$this->getOption('repository'),
			$commit['hash'],
		]);
	}
	
	/**
	 * Generate a link to a GitHub issue
	 *
	 * @param int $issue_id the issue ID
	 *
	 * @return string
	 */
	protected function makeIssueLink(int $issue_id): string {
		if (empty($issue_id)) {
			return '';
		}
		
		return vsprintf('[#%s](%s/commit/%s)', [
			$issue_id,
			$this->getOption('repository'),
			$issue_id,
		]);
	}
	
	/**
	 * Write the release notes to the changelog
	 *
	 * @param string $release_notes release notes
	 *
	 * @return void
	 */
	protected function writeChangelog(string $release_notes): void {
		$contents = file_get_contents($this->getOption('changelog'));
		$first_anchor = strpos($contents, '<a name="');
		if ($first_anchor !== false) {
			$contents = substr($contents, $first_anchor);
		}
		
		$contents = $release_notes  . PHP_EOL . PHP_EOL . PHP_EOL . $contents;
		
		file_put_contents($this->getOption('changelog'), $contents);
	}
	
	/**
	 * Execute a command
	 *
	 * @param string $command the command to execute
	 *
	 * @return null|array
	 */
	protected function executeCommand(string $command): ?array {
		$output = [];
		$result_code = null;
		
		exec($command, $output, $result_code);
		
		if ($result_code !== 0) {
			return null;
		}
		
		return $output;
	}
	
	/**
	 * Get an option
	 *
	 * @param string $option  name op the option
	 * @param mixed  $default default value
	 *
	 * @return mixed
	 */
	protected function getOption(string $option, mixed $default = null): mixed {
		return $this->options[$option] ?? $default;
	}
}
