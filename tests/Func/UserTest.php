<?php  

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserTest extends WebTestCase
{
    public function testGetUsers():void
    {
        $client = self::createClient();

        // on rajoute .json pour recevoir le json et nom la page swagger 
        $client->request(Request::METHOD_GET,'/api/users.json');

        dd($client->getResponse());
    }
}