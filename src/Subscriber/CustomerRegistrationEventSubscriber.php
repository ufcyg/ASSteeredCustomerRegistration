<?php declare(strict_types=1);

namespace ASSteeredCustomerRegistration\Subscriber;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ASMailService\Core\MailServiceHelper;
use Psr\Container\ContainerInterface;

class CustomerRegistrationEventSubscriber implements EventSubscriberInterface
{
    /** @var SystemConfigService $systemConfigService */
    private $systemConfigService;
    /** @var ContainerInterface $container */
    protected $container;
    /** @var MailServiceHelper $mailServiceHelper */
    private $mailServiceHelper;
    /** @var string $senderName */
    private $senderName;
    public function __construct(SystemConfigService $systemConfigService,
                                MailServiceHelper $mailServiceHelper)
    {
        $this->systemConfigService = $systemConfigService;
        $this->mailServiceHelper = $mailServiceHelper;
        $this->senderName = 'Steered Customer Registration';
    }

    /** @internal @required */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    public static function getSubscribedEvents(): array
    {
        // Return the events to listen to as array like this:  <event to listen to> => <method to execute>
        return [
            CustomerRegisterEvent::class => 'onCustomerRegistered'
        ];
    }

    public function onCustomerRegistered(CustomerRegisterEvent $event)
    {
        $registrationState = $this->systemConfigService->get('ASSteeredCustomerRegistration.config.registrationActiveState');
        if($registrationState == 'open')
        {// do not validate user if registration is fully open, this value is defined in the plugin configuration
            return;
        }

        /** @var CustomerEntity $customer */
        $customer = $event->getCustomer();
        // $customerGroupsRespository = $this->container->getCustomerGroup();

        if ($this->whitelistEntryExistsCk($customer->getEmail()))
        {//internal customer by registered email domain
            $steeredCustomerGroup = $this->systemConfigService->get('ASSteeredCustomerRegistration.config.internalCustomerGroup');
            $whitelistRepository = $this->container->get('synlab_steered_customer_registration_whitelist.repository');
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('targetMail', $customer->getEmail()));
            $searchResult = $whitelistRepository->search($criteria,Context::createDefaultContext());
            $whitelistEntity = $searchResult->first();
            $whitelistRepository->delete([
                ['id' => $whitelistEntity->getId()],
            ],Context::createDefaultContext());
        }
        else
        {//idle registration for approval
            $notification = 'Hallo,<br>
                            vielen Dank für Ihre Registrierung.<br>
                            Unser Support Team wird Ihre Anfrage prüfen und Ihren Account gegebenenfalls freischalten.<br>';
            $this->mailServiceHelper->sendMyMail([$customer->getEmail() => $customer->getFirstName() . ' ' . $customer->getLastName()],$event->getSalesChannelContext()->getSalesChannel()->getId(), $this->senderName,'Willkommen!',$notification, $notification,['']);
            $steeredCustomerGroup = $this->systemConfigService->get('ASSteeredCustomerRegistration.config.idleCustomerGroup');
        }

        if($steeredCustomerGroup == null)
        {
            return;
        }
        $this->updateCustomerEntity($customer, $steeredCustomerGroup);
    }

    private function updateCustomerEntity(CustomerEntity $customer, string $steeredCustomerGroup)
    {
        $customerID = $customer->getId();
        $customerRepository = $this->container->get('customer.repository');
        $customerRepository->update([
            [ 'id' => $customerID, 'groupId' => $steeredCustomerGroup, 'requestedGroupId' => null]
        ], Context::createDefaultContext());
    }

    private function whitelistEntryExistsCk(string $listEntry): bool
    {
        $whitelistRepository = $this->container->get('synlab_steered_customer_registration_whitelist.repository');
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('targetMail',$listEntry));
        $searchResult = $whitelistRepository->search($criteria,Context::createDefaultContext());
        return count($searchResult) == 0 ? false : true;
    }
}