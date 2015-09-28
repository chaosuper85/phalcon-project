<?php

namespace Pheanstalk;

/**
 * Pheanstalk is a PHP client for the beanstalkd workqueue.
 * The Pheanstalk class is a simple facade for the various underlying components.
 *
 * @see http://github.com/kr/beanstalkd
 * @see http://xph.us/software/beanstalkd/
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Pheanstalk implements PheanstalkInterface
{
    const VERSION = '3.0.2';

    private $_connection;
    private $_using = PheanstalkInterface::DEFAULT_TUBE;
    private $_watching = array(PheanstalkInterface::DEFAULT_TUBE => true);

    /**
     * @param string $host
     * @param int    $port
     * @param int    $connectTimeout
     */
    public function __construct($host, $port = PheanstalkInterface::DEFAULT_PORT, $connectTimeout = null)
    {
        $this->setConnection(new Connection($host, $port, $connectTimeout));
    }

    /**
     * {@inheritDoc}
     */
    public function setConnection(Connection $connection)
    {
        $this->_connection = $connection;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    // ----------------------------------------

    /**
     * {@inheritDoc}
     */
    public function bury($job, $priority = PheanstalkInterface::DEFAULT_PRIORITY)
    {
        $this->_dispatch(new Command\BuryCommand($job, $priority));
    }

    /**
     * {@inheritDoc}
     */
    public function delete($job)
    {
        $this->_dispatch(new Command\DeleteCommand($job));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function ignore($tube)
    {
        if (isset($this->_watching[$tube])) {
            $this->_dispatch(new Command\IgnoreCommand($tube));
            unset($this->_watching[$tube]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function kick($max)
    {
        $response = $this->_dispatch(new Command\KickCommand($max));

        return $response['kicked'];
    }

    /**
     * {@inheritDoc}
     */
    public function kickJob($job)
    {
        $this->_dispatch(new Command\KickJobCommand($job));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function listTubes()
    {
        return (array) $this->_dispatch(
            new Command\ListTubesCommand()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function listTubesWatched($askServer = false)
    {
        if ($askServer) {
            $response = (array) $this->_dispatch(
                new Command\ListTubesWatchedCommand()
            );
            $this->_watching = array_fill_keys($response, true);
        }

        return array_keys($this->_watching);
    }

    /**
     * {@inheritDoc}
     */
    public function listTubeUsed($askServer = false)
    {
        if ($askServer) {
            $response = $this->_dispatch(
                new Command\ListTubeUsedCommand()
            );
            $this->_using = $response['tube'];
        }

        return $this->_using;
    }

    /**
     * {@inheritDoc}
     */
    public function pauseTube($tube, $delay)
    {
        $this->_dispatch(new Command\PauseTubeCommand($tube, $delay));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function peek($jobId)
    {
        $response = $this->_dispatch(
            new Command\PeekCommand($jobId)
        );

        return new Job($response['id'], $response['jobdata']);
    }

    /**
     * {@inheritDoc}
     */
    public function peekReady($tube = null)
    {
        if ($tube !== null) {
            $this->useTube($tube);
        }

        $response = $this->_dispatch(
            new Command\PeekCommand(Command\PeekCommand::TYPE_READY)
        );

        return new Job($response['id'], $response['jobdata']);
    }

    /**
     * {@inheritDoc}
     */
    public function peekDelayed($tube = null)
    {
        if ($tube !== null) {
            $this->useTube($tube);
        }

        $response = $this->_dispatch(
            new Command\PeekCommand(Command\PeekCommand::TYPE_DELAYED)
        );

        return new Job($response['id'], $response['jobdata']);
    }

    /**
     * {@inheritDoc}
     */
    public function peekBuried($tube = null)
    {
        if ($tube !== null) {
            $this->useTube($tube);
        }

        $response = $this->_dispatch(
            new Command\PeekCommand(Command\PeekCommand::TYPE_BURIED)
        );

        return new Job($response['id'], $response['jobdata']);
    }

    /**
     * {@inheritDoc}
     */
    public function put(
        $data,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY,
        $ttr = PheanstalkInterface::DEFAULT_TTR
    )
    {
        $response = $this->_dispatch(
            new Command\PutCommand($data, $priority, $delay, $ttr)
        );

        return $response['id'];
    }

    /**
     * {@inheritDoc}
     */
    public function putInTube(
        $tube,
        $data,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY,
        $ttr = PheanstalkInterface::DEFAULT_TTR
    )
    {
        $this->useTube($tube);

        return $this->put($data, $priority, $delay, $ttr);
    }

    /**
     * {@inheritDoc}
     */
    public function release(
        $job,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY
    )
    {
        $this->_dispatch(
            new Command\ReleaseCommand($job, $priority, $delay)
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function reserve($timeout = null)
    {
        $response = $this->_dispatch(
            new Command\ReserveCommand($timeout)
        );

        $falseResponses = array(
            Response::RESPONSE_DEADLINE_SOON,
            Response::RESPONSE_TIMED_OUT,
        );

        if (in_array($response->getResponseName(), $falseResponses)) {
            return false;
        } else {
            return new Job($response['id'], $response['jobdata']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function reserveFromTube($tube, $timeout = null)
    {
        $this->watchOnly($tube);

        return $this->reserve($timeout);
    }

    /**
     * {@inheritDoc}
     */
    public function statsJob($job)
    {
        return $this->_dispatch(new Command\StatsJobCommand($job));
    }

    /**
     * {@inheritDoc}
     */
    public function statsTube($tube)
    {
        return $this->_dispatch(new Command\StatsTubeCommand($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function stats()
    {
        return $this->_dispatch(new Command\StatsCommand());
    }

    /**
     * {@inheritDoc}
     */
    public function touch($job)
    {
        $this->_dispatch(new Command\TouchCommand($job));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function useTube($tube)
    {
        if ($this->_using != $tube) {
            $this->_dispatch(new Command\UseCommand($tube));
            $this->_using = $tube;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function watch($tube)
    {
        if (!isset($this->_watching[$tube])) {
            $this->_dispatch(new Command\WatchCommand($tube));
            $this->_watching[$tube] = true;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function watchOnly($tube)
    {
        $this->watch($tube);

        $ignoreTubes = array_diff_key($this->_watching, array($tube => true));
        foreach ($ignoreTubes as $ignoreTube => $true) {
            $this->ignore($ignoreTube);
        }

        return $this;
    }

    // ----------------------------------------

    /**
     * Dispatches the specified command to the connection object.
     *
     * If a SocketException occurs, the connection is reset, and the command is
     * re-attempted once.
     *
     * @param  Command  $command
     * @return Response
     */
    private function _dispatch($command)
    {
        try {
            $response = $this->_connection->dispatchCommand($command);
        } catch (Exception\SocketException $e) {
            $this->_reconnect();
            $response = $this->_connection->dispatchCommand($command);
        }

        return $response;
    }

    /**
     * Creates a new connection object, based on the existing connection object,
     * and re-establishes the used tube and watchlist.
     */
    private function _reconnect()
    {
        $new_connection = new Connection(
            $this->_connection->getHost(),
            $this->_connection->getPort(),
            $this->_connection->getConnectTimeout()
        );

        $this->setConnection($new_connection);

        if ($this->_using != PheanstalkInterface::DEFAULT_TUBE) {
            $tube = $this->_using;
            $this->_using = null;
            $this->useTube($tube);
        }

        foreach ($this->_watching as $tube => $true) {
            if ($tube != PheanstalkInterface::DEFAULT_TUBE) {
                unset($this->_watching[$tube]);
                $this->watch($tube);
            }
        }

        if (!isset($this->_watching[PheanstalkInterface::DEFAULT_TUBE])) {
            $this->ignore(PheanstalkInterface::DEFAULT_TUBE);
        }
    }
}
