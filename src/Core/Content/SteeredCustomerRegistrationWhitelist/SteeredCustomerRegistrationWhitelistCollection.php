<?php

declare(strict_types=1);

namespace ASSteeredCustomerRegistration\Core\Content\SteeredCustomerRegistrationWhitelist;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(SteeredCustomerRegistrationCollection $entity)
 * @method void              set(string $key, SteeredCustomerRegistrationCollection $entity)
 * @method SteeredCustomerRegistrationCollection[]    getIterator()
 * @method SteeredCustomerRegistrationCollection[]    getElements()
 * @method SteeredCustomerRegistrationCollection|null get(string $key)
 * @method SteeredCustomerRegistrationCollection|null first()
 * @method SteeredCustomerRegistrationCollection|null last()
 */
class SteeredCustomerRegistrationWhitelistCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SteeredCustomerRegistrationWhitelistEntity::class;
    }
}
