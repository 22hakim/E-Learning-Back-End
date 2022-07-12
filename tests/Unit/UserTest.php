<?php  

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testGetEmail(): void
    {
        $value = "test@test.fr";

        $response = $this->user->setEmail($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getEmail());
        self::assertEquals($value, $this->user->getUserIdentifier());

    }

    public function testGetRoles():void
    {
        self::assertContains('ROLE_USER',$this->user->getRoles());
    }

    public function testGetArticles():void
    {
        $article = new Article;
        $response = $this->user->addUserArticle($article);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getUserArticles());
        self::assertTrue($this->user->getUserArticles()->contains($article));

        $response = $this->user->removeUserArticle(($article));


        self::assertInstanceOf(User::class, $response);
        self::assertCount(0, $this->user->getUserArticles());
        self::assertNotTrue($this->user->getUserArticles()->contains($article));
    }
}