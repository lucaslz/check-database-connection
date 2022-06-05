<?php

namespace Lucaslz\CheckDatabaseConnection;

use Lucaslz\CheckDatabaseConnection\Exceptions\ConnectionDatabaseException;
use PDO;
use Exception;

/**
 * Represents a class that checks the connection to a MySQL database.
 * 
 * @author Lucas Lima <lucas.developmaster@gmail.com>
 * 
 * @package Lucaslz\CheckDatabaseConnection
 * 
 * @license MIT
 * @copyright (c) 2022, Lucas Lima
 * @version 1.0.0
 */
class DatabaseConnection
{
    /**
     * The array database configuration connection.
     * 
     * @var array $configConnection
     * @access private
     */
    private $configConnection = [];

    /**
     * The prepare configuration connection.
     * 
     * @var array $prepareConnection
     * @access private
     */
    private $prepareConnection = [];

    /**
     * Driver of database.
     * 
     * @var string $driver
     * @access private
     */
    private $driver = 'mysql';

    /**
     * The required values in array configuration.
     * 
     * @var array REQUIRED_CONFIG_CONNECTION
     */
    const REQUIRED_CONFIG_CONNECTION = [
        'host',
        'port',
        'user',
        'pass',
        'database'
    ];

    /**
     * The construction class.
     * 
     * @param array $configConnection
     * @access public
     * @return void
     */
    public function __construct($configConnection = [])
    {
        if (count($configConnection) == 0) {
            throw new ConnectionDatabaseException('You must pass a connection array to the constructor.');
        }
        $this->configConnection = $configConnection;
        $this->validateParamsArrayConfig();
        $this->prepareConnection();
    }

    /**
     * Validate the array configuration.
     * 
     * @access private
     * @return void
     */
    private function validateParamsArrayConfig()
    {
        $arrayKeys = [];
        foreach ($this->configConnection as $key => $value) {
            $arrayKeys = array_keys($value);
            if (count(array_diff(self::REQUIRED_CONFIG_CONNECTION, $arrayKeys)) > 0) {
                throw new ConnectionDatabaseException('You must pass a connection array with the following values: host, port, user, pass, database.');
            }
        }
    }

    /**
     * Get the privilegied connection database.
     * 
     * @access private
     * @return array
     */
    private function getPrivilegiedConnection()
    {
        $privilegiedConnection = [];
        foreach ($this->prepareConnection as $key => $value) {
            if (isset($value['privilegied']) && $value['privilegied'] =! '' && $value['privilegied'] == true) {
                $privilegiedConnection[$key] = $value;
            }
        }
        return $privilegiedConnection;
    }

    /**
     * Prepare the connection.
     * 
     * @access private
     * @return void
     */
    private function prepareConnection()
    {
        foreach ($this->configConnection as $key => $value) {
            $this->prepareConnection[$key] = [
                'dns' => sprintf(
                    '%s:host=%s;port=%s;dbname=%s',
                    isset($value['driver']) && $value['driver'] != '' ? $value['driver'] : $this->driver,
                    $value['host'],
                    $value['port'],
                    $value['database']
                ),
                'host' => $value['host'],
                'port' => $value['port'],
                'user' => $value['user'],
                'pass' => $value['pass'],
                'privilegied' => isset($value['privilegied']) && $value['privilegied'] != '' ? $value['privilegied'] : false,
            ];
        }
    }

    /**
     * Test if connection is running.
     * 
     * @param string $dns
     * @param string $user
     * @param string $pass
     * 
     * @access private
     * @return bool
     */
    private function testConnection($dns, $user, $pass)
    {
        $conn = null;

        try {
            $conn = new PDO($dns, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function iterateConnections($arrayConnections)
    {
        foreach ($arrayConnections as $key => $value) {
            if ($this->testConnection($value['dns'], $value['user'], $value['pass'])) {
                return [
                    $value['host'],
                    $value['port']
                ];
            }
        }
        return [];
    }

    /**
     * Get the information connection.
     * 
     * @access public
     * @return array
     */
    public function getConnection()
    {
        $privilegiedConnections = $this->getPrivilegiedConnection();
        $connections = [];
        $arrayConnections = [];

        if (count($privilegiedConnections) > 0) {
            $connections = array_diff_key($this->prepareConnection, $privilegiedConnections);
        }

        if (count($privilegiedConnections) > 0) {
            $arrayConnections = $this->iterateConnections($privilegiedConnections);
            if(count($arrayConnections) > 0) {
                return $arrayConnections;
            }
        }
    
        if (count($connections) > 0) {
            $arrayConnections = $this->iterateConnections($connections);
            if(count($arrayConnections) > 0) {
                return $arrayConnections;
            }
        }

        throw new ConnectionDatabaseException('No connection was made.');
    }
}