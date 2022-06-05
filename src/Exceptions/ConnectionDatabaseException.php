<?php

namespace Lucaslz\CheckDatabaseConnection\Exceptions;

use Exception;

/**
 * Represents an exception that occurs when a connection to a MySQL database fails.
 * 
 * @author Lucas Lima <lucas.developmaster@gmail.com>
 * 
 * @package Lucaslz\CheckDatabaseConnection
 * @subpackage Exceptions
 * @category Exceptions
 * 
 * @license MIT
 * @copyright (c) 2022, Lucas Lima
 * @version 1.0.0
 */
class ConnectionDatabaseException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}