<?php declare(strict_types=1);

namespace ASSteeredCustomerRegistration\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1609934294Whitelist extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1609934294;
    }

    public function update(Connection $connection): void
    {
        $connection->exec("CREATE TABLE IF NOT EXISTS `synlab_steered_customer_registration_whitelist` (
            `id`            BINARY(16) NOT NULL,
            `target_mail`   VARCHAR(255) NOT NULL,
            `created_at`    DATETIME(3),
            `updated_at`    DATETIME(3)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
