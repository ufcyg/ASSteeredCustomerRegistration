<?php declare(strict_types=1);

namespace ASSteeredCustomerRegistration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin;

class ASSteeredCustomerRegistration extends Plugin
{
    /** @inheritDoc */
    public function install(InstallContext $context): void
    {
    }

    /** @inheritDoc */
    public function postInstall(InstallContext $context): void
    {
    }

    /** @inheritDoc */
    public function update(UpdateContext $context): void
    {
    }

    /** @inheritDoc */
    public function postUpdate(UpdateContext $context): void
    {
    }

    /** @inheritDoc */
    public function activate(ActivateContext $context): void
    {
    }

    /** @inheritDoc */
    public function deactivate(DeactivateContext $context): void
    {
    }

    /** @inheritDoc */
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);
        
        $connection->executeUpdate('DROP TABLE IF EXISTS `synlab_steered_customer_registration_whitelist`');
        //
        $connection->executeUpdate('DROP TABLE IF EXISTS `synlab_steered_customer_registration`');
        parent::uninstall($context);
    }
}