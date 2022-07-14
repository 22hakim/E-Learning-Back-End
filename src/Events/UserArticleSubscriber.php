<?php 

namespace App\Events;

use App\Entity\Article;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Security;

class UserArticleSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
                KernelEvents::VIEW => [ 'currentUserForArticle', EventPriorities::PRE_WRITE]
            ];
    }

    public function currentUserForArticle(ViewEvent $e):void
    {
        $article = $e->getControllerResult();
        $httpMethod = $e->getRequest()->getMethod();

        if($article instanceof Article && $httpMethod == Request::METHOD_POST){
            $article->setAuthor($this->security->getUser());
        }
    }
}