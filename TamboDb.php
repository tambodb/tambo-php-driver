<?php

class TamboDb
{

    protected $host;
    protected $port;
    protected $connection;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function connect()
    {

        $this->connection = pfsockopen("tcp://" . $this->host, $this->port, $errno, $errstr);

        if (!$this->connection) {
            echo "$errstr ($errno)<br/>\n";
            echo $this->connection;
        }
        fwrite($this->connection, "USE\r\ndbh\r\n\r\n\r\n");
        return true;
    }

    public function disconnect()
    {
        if (isset($this->connection)) {
            fwrite($this->connection, "QUIT\r\n\r\n\r\n");
            fclose($this->connection);
        }
    }

    public function feof_segura($fp, &$inicio = NULL)
    {
        $inicio = microtime(true);
        return feof($fp);
    }

    public function create($key, $value)
    {
        $this->connect();
        (string) $command = "CREATE\r\n$key\r\n$value\r\n\r\n\r\n";
        fwrite($this->connection, $command);

        $c = fread($this->connection, 1000);
        $this->disconnect();
        return $c;
    }

    public function read($key)
    {
        $this->connect();
        (string) $command = "READ\r\n$key\r\n\r\n\r\n";
        fwrite($this->connection, $command);
        while (!feof($this->connection)) {
            echo fgets($this->connection, 128);
        }
        $this->disconnect();
    }

    public function update($key, $value)
    {
        $this->connect();
        (string) $command = "UPDATE\r\n$key\r\n$value\r\n\r\n\r\n";
        fwrite($this->connection, $command);
        while (!feof($this->connection)) {
            echo fgets($this->connection, 128);
        }
        $this->disconnect();
    }

    public function delete($key)
    {
        $this->connect();
        (string) $command = "DELETE\r\n$key\r\n\r\n\r\n";
        fwrite($this->connection, $command);
        while (!feof($this->connection)) {
            echo fgets($this->connection, 128);
        }
        $this->disconnect();
    }

}