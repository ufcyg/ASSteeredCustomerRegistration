<?php declare(strict_types=1);

namespace ASSteeredCustomerRegistration\Core\Content\SteeredCustomerRegistrationWhitelist;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

class SteeredCustomerRegistrationWhitelistDefinition extends EntityDefinition
{

    public function getEntityName(): string
    {
        return 'synlab_steered_customer_registration_whitelist';
    }

    public function getCollectionClass(): string
    {
        return SteeredCustomerRegistrationWhitelistCollection::class;
    }

    public function getEntityClass(): string
    {
        return SteeredCustomerRegistrationWhitelistEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id','id'))->addFlags(new Required(), new PrimaryKey()),
                new StringField('target_mail','targetMail')   
            ]
        );
    }
}