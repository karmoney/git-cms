<?php

class Zend_Git {

	/**
	 * Create a new git repository
	 *
	 * Accepts a creation path, and, optionally, a source path
	 *
	 * @access  public
	 * @param   string  repository path
	 * @param   string  directory to source
	 * @return  GitRepo
	 */	
	public static function &create($repo_path, $source = null) {
		return Zend_Git_Repo::create_new($repo_path, $source);
	}

	/**
	 * Open an existing git repository
	 *
	 * Accepts a repository path
	 *
	 * @access  public
	 * @param   string  repository path
	 * @return  GitRepo
	 */	
	public static function open($repo_path) {
		return new Zend_Git_Repo($repo_path);
	}
}