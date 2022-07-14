<?php 

namespace App\Authorizations;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAuthorizationChecker
{
    private array $methodAllowed = [
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE
    ];

    private ?UserInterface $user;


    public function __construct(Security $security)
    {
        $this->user = $security->getUser(); 
    }

    public function check(UserInterface $user, string $httpMethod):void
    {
        $this->isAuthenticated();
        if($this->isMethodAllowed($httpMethod) 
        && $user->getUserIdentifier() !== $this->user->getUserIdentifier())
        {
            $php_errormsg = "you are not allowed to access this ressource";
            throw new UnauthorizedHttpException($php_errormsg, $php_errormsg);
        }
    }

    public function isAuthenticated():void
    {
        if(null === $this->user){
            $php_errormsg = "you are not authenticated";
            throw new UnauthorizedHttpException($php_errormsg, $php_errormsg);
        }
    }

    public function isMethodAllowed(string $httpMethod):bool
    {
        return in_array($httpMethod, $this->methodAllowed);
    }

}