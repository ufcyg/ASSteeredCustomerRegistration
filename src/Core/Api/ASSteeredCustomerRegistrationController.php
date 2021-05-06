<?php

declare(strict_types=1);

namespace ASSteeredCustomerRegistration\Core\Api;

use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ASMailService\Core\MailServiceHelper;

/**
 * @RouteScope(scopes={"api"})
 */
class ASSteeredCustomerRegistrationController extends AbstractController
{
    /** @var SystemConfigService $systemConfigService */
    private $systemConfigService;
    /** @var MailServiceHelper $mailserviceHelper */
    private $mailserviceHelper;
    /** @var string $senderName */
    private $senderName;

    public function __construct(
        SystemConfigService $systemConfigService,
        MailServiceHelper $mailserviceHelper
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->mailserviceHelper = $mailserviceHelper;
        $this->senderName = 'Steered Customer Registration';
    }

    /**
     * @Route("/api/v{version}/_action/as-steered-customer-registration/dummyRoute", name="api.custom.as_steered_customer_registration.dummyRoute", methods={"POST"})
     * @param Context $context;
     * @return Response
     */
    public function dummyRoute(Context $context)
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/v{version}/_action/as-steered-customer-registration/updateWhitelist", name="api.custom.as_steered_customer_registration.updateWhitelist", methods={"POST"})
     * @param Context $context;
     * @return Response
     */
    public function updateWhitelist(Request $request)
    {
        $data = null;
        $whitelistRepository = $this->container->get('synlab_steered_customer_registration_whitelist.repository');

        $mailWhitelist = json_decode($request->getContent(false), true);
        foreach ($mailWhitelist as $list) {
            $whiteList = explode(';', $list);
        }
        foreach ($whiteList as $listEntry) {
            if ($this->whitelistEntryExistsCk($listEntry)) {
                continue;
            }
            $data[] = [
                'id' => Uuid::randomHex(),
                'targetMail' => $listEntry
            ];
        }
        if ($data != null)
            $whitelistRepository->create($data, Context::createDefaultContext());

        return new Response('', Response::HTTP_NO_CONTENT);
    }
    /**
     * @Route("/api/v{version}/_action/as-steered-customer-registration/sendInvite", name="api.custom.as_steered_customer_registration.sendInvite", methods={"POST"})
     * @param Context $context;
     * @return Response
     */
    public function sendInvite(Request $request)
    {
        $inviteList = json_decode($request->getContent(false), true);
        $inviteEntries = explode(';', $inviteList['inviteList']);
        $inviteLink = explode(';', $inviteList['inviteLink']);

        $recipients = null;
        foreach ($inviteEntries as $inviteEntry) {
            $recipients[$inviteEntry] = $inviteEntry;
        }
        $inviteString = "Willkommen beim zentralen Proben- und Materialversand!<br><br>Bitte klicken Sie auf unten stehenden Link um sich im eShop zu registrieren. <br><br> {$inviteLink[0]}<br><br> Nutzen Sie für die Registrierung die Lieferadresse des Standortes für den Sie benötigte Rohmaterialen bestellen werden. Nutzen Sie zwingend die eMail-Adresse auf der Sie diese eMail erhalten. Sollten Sie versuchen sich mit einer anderen Mailadresse zu registrieren wird der dadurch entstehende Account sofort gelöscht.<br><br> Vielen Dank und viel Spaß!";

        $this->mailserviceHelper->sendMyMail($recipients, null, $this->senderName, 'Einladung zur Registrierung für den eShop des ZPMV', $inviteString, $inviteString, ['']);


        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function whitelistEntryExistsCk(string $listEntry): bool
    {
        $whitelistRepository = $this->container->get('synlab_steered_customer_registration_whitelist.repository');
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('targetMail', $listEntry));
        $searchResult = $whitelistRepository->search($criteria, Context::createDefaultContext());
        return count($searchResult) == 0 ? false : true;
    }
}
