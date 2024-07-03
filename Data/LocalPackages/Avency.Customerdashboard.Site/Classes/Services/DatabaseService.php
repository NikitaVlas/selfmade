<?php
namespace Avency\Customerdashboard\Site\Services;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Neos\Flow\Annotations as Flow;

class DatabaseService
{
    /**
     * @Flow\InjectConfiguration(path="dbOptions")
     * @var array
     */
    protected $dbOptions;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @return void
     * @throws Exception
     */
    public function initializeObject()
    {
        $configuration = new Configuration();
        $this->connection = DriverManager::getConnection($this->dbOptions, $configuration);
    }

    /**
     * Get connection
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        if (!$this->connection->isConnected()) {
            $this->connection->connect();
        }

        return $this->connection;
    }
}
