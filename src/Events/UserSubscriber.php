<?php

namespace App\Events;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Authorizations\UserAuthorizationChecker;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class UserSubscriber implements EventSubscriberInterface
{
    private array $methodNotAllowed = [
        Request::METHOD_POST,
        Request::METHOD_GET
    ];

    public function __construct(UserAuthorizationChecker $uac )
    {
        $this->authorizationChecker = $uac;
    }

    public static function getSubscribedEvents()
    {
        return [
                KernelEvents::VIEW => [ 'check', EventPriorities::PRE_VALIDATE]
            ];
    }

    public function check(ViewEvent $e):void
    {
        $user = $e->getControllerResult();
        $httpMethod = $e->getRequest()->getMethod();

        if($user instanceof User && !in_array($httpMethod,$this->methodNotAllowed, true))
        {
            $this->authorizationChecker->check($user,$httpMethod); 
            $user->setUpdatedAt(new \DateTimeImmutable());
        }

    }
}