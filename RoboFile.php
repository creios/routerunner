<?php

/**
 * This is routerunner's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{

    /**
     * Shows .semver file as a String
     * @throws \Robo\Exception\TaskException
     */
    public function semver()
    {
        $this->taskExec('echo ' . $this->taskSemVer()->__toString())
            ->run();
    }

    /**
     * Increments major segment of .semver file
     * @throws \Robo\Exception\TaskException
     */
    public function semverMajor()
    {
        $this->taskSemVer()
            ->increment('major')
            ->run();
    }

    /**
     * Increments minor segment of .semver file
     * @throws \Robo\Exception\TaskException
     */
    public function semverMinor()
    {
        $this->taskSemVer()
            ->increment('minor')
            ->run();
    }

    /**
     * Increments patch segment of .semver file
     * @throws \Robo\Exception\TaskException
     */
    public function semverPatch()
    {
        $this->taskSemVer()
            ->increment('patch')
            ->run();
    }

    /**
     * Creates a new tag equal to the .semver file
     */
    public function gitTag()
    {
        $semVer = $this->taskSemVer()->__toString();
        $this->taskExec('git tag -a -m "' . $semVer . '" ' . $semVer)->run();
    }

    /**
     * Deletes the tag equal to the .semver file
     */
    public function gitTagDelete()
    {
        $semVer = $this->taskSemVer()->__toString();
        $this->taskExec('git tag -d ' . $semVer)->run();
    }
}