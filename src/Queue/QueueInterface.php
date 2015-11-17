<?php

namespace ActiveCollab\JobsQueue\Queue;

use ActiveCollab\JobsQueue\Jobs\JobInterface;
use Countable;

/**
 * @package ActiveCollab\JobsQueue\Queue
 */
interface QueueInterface extends Countable
{
    const MAIN_CHANNEL = 'main';

    /**
     * Add a job to the queue
     *
     * @param  JobInterface $job
     * @param  string       $channel
     * @return mixed
     */
    public function enqueue(JobInterface $job, $channel = self::MAIN_CHANNEL);

    /**
     * Execute a job now (sync, waits for a response)
     *
     * @param  JobInterface $job
     * @param  $channel     $channel
     * @return mixed
     */
    public function execute(JobInterface $job, $channel = self::MAIN_CHANNEL);

    /**
     * Return true if there's an active job of the give type with the given properties
     *
     * @param  string     $job_type
     * @param  array|null $properties
     * @return boolean
     */
    public function exists($job_type, array $properties = null);

    /**
     * Return a total number of jobs that are in the given channel
     *
     * @param  string  $channel
     * @return integer
     */
    public function countByChannel($channel);

    /**
     * Return Job that is next in line to be executed
     *
     * @param  string            ...$from_channels
     * @return JobInterface|null
     */
    public function nextInLine();

    /**
     * What to do when job fails
     *
     * @param callable|null $callback
     */
    public function onJobFailure(callable $callback = null);

    /**
     * Restore failed job by job ID and optionally update job properties
     *
     * @param  mixed        $job_id
     * @param  array|null   $update_data
     * @return JobInterface
     */
    public function restoreFailedJobById($job_id, array $update_data = null);

    /**
     * Restore failed jobs by job type
     *
     * @param string     $job_type
     * @param array|null $update_data
     */
    public function restoreFailedJobsByType($job_type, array $update_data = null);

    /**
     * @param  string $type1
     * @return integer
     */
    public function countByType($type1);

    /**
     * @return integer
     */
    public function countFailed();

    /**
     * @param  string  $type1
     * @return integer
     */
    public function countFailedByType($type1);

    /**
     * Let jobs report that they raised background process
     *
     * @param JobInterface $job
     * @param integer      $process_id
     */
    public function reportBackgroundProcess(JobInterface $job, $process_id);

    /**
     * Return a list of background processes that jobs from this queue have launched
     *
     * @return array
     */
    public function getBackgroundProcesses();

    /**
     * Check stuck jobs
     */
    public function checkStuckJobs();

    /**
     * Clean up the queue
     */
    public function cleanUp();
    /**
     * Clear up the all failed jobs
     */
    public function clear();
    /**
     * Return all distinct reasons why a job of the given type failed us in the past
     *
     * @param string $job_type
     * @returns array
     */
    public function getFailedJobReasons($job_type);
    /**
     * Search for a full job class name
     *
     * @param string $search_for
     * @return mixed
     * @throws \Exception
     */
    public function unfurlType($search_for);

    /**
     * Method that returns failed job statistics
     * @return array Key is job type, value is an array where keys are dates and values are number of failed jobs on that particular day.
     */
    public function failedJobStatistics();

    /**
     * @return array where key is job type and value is number of jobs in the queue of that type.
     */
    public function countJobsByType();
    /**
     * Create one or more tables
     *
     * @param  string     ...$additional_tables
     * @throws \Exception
     */
    public function createTables();
}