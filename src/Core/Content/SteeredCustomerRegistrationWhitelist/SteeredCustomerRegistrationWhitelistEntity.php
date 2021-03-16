<?php declare(strict_types=1);

namespace ASSteeredCustomerRegistration\Core\Content\SteeredCustomerRegistrationWhitelist;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class SteeredCustomerRegistrationWhitelistEntity extends Entity
{
    use EntityIdTrait;
    
    /**
     * @var string
     */
    protected $targetMail;

    /**
     * Get the value of targetMail
     *
     * @return  string
     */ 
    public function getTargetMail()
    {
        return $this->targetMail;
    }

    /**
     * Set the value of targetMail
     *
     * @param  string  $targetMail
     *
     * @return  self
     */ 
    public function setTargetMail(string $targetMail)
    {
        $this->targetMail = $targetMail;

        return $this;
    }
}