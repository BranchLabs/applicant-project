<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use App\Service\Orm\Repository\AbstractRepository;
use App\Service\Orm\Database\Adapter\Mysql\MysqlAdapter;

class ContactRepository extends AbstractRepository
{
    protected const PRIMARY_KEY = 'id';

    protected const TABLE_NAME = 'contacts';

    public function __construct(MysqlAdapter $database)
    {
        parent::__construct($database, Contact::class);
    }

    protected function getPrimaryKey(): string
    {
        return self::PRIMARY_KEY;
    }

    protected function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}
