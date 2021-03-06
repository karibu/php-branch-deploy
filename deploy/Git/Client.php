<?php

/**
 * @todo PHPDOC
 * @author Ben Tadiar <ben@handcraftedbyben.co.uk>
 */
namespace Git;
class Client
{
	/**
	 * Path to the directory containing the Git binary
	 * @todo Path is only required for Windows, put in check
	 * @var string
	 */
	private $path = 'C:\Program Files (x86)\Git\bin';
	
	/**
	 * Add Git to the PATH environment variable
	 * @return void
	 */
	public function __construct()
	{
		putenv('PATH=' . getenv('PATH') . $this->path);
	}
	
	/**
	 * Clone a repository
	 * @param string $repo The GitHub repository
	 * @param ull|string $dir The directory to clone into
	 */
	public function cloneRepo($repo, $dir = null)
	{
		// If no directory is specified, use the
		// repository name, less the .git extension
		if($dir === null){
			$start = strrpos($repo, '/') + 1;
			$dir = substr($repo, $start, -4);
		}
		
		$cmd = "git clone $repo $dir 2>&1";
		$output = $this->call($cmd);
		return $dir;
	}
	
	/**
	 * List all local branches
	 * @return array
	 */
	public function branches($repo)
	{
		chdir($repo);
		$branches = array();
		foreach($this->call('git branch -a') as $branch){
			if($branch[0] == '*') $branch = substr($branch, 2);
			$parts = explode('/', $branch);
			$branch = end($parts);
			$branches[] = trim($branch);
		}
		return array_unique($branches);
	}
	
	public function checkout($branch)
	{
		if($branch != 'master') $branch = '-t ' . $branch;
		$cmd = "git checkout $branch";
		$output = $this->call($cmd);
		return $output;
	}
	
	public function pull($branch)
	{
		$cmd = "git pull 2>&1";
		$output = $this->call($cmd);
		return $output;
	}
	
	/**
	 * Execute the command
	 * @param string $cmd
	 */
	private function call($cmd)
	{
		exec($cmd, $output, $status);
		if($status != 0){
			var_dump($output, $status);
		}
		return $output;
	}
}
