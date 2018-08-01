<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\PersistentObject;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UserControllerFunctionalTest extends WebTestCase
{

    protected function setUp()
    {
        /** @var PersistentObject persist,flush */

        static::createClient();

        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->createDb($em);

        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../Fixtures/fixtures.yml')->getObjects();

        foreach ($objectSet as $object) {
            $em->persist($object);
        }

        $em->flush();
        $em->clear();

        parent::setUp(); // TODO: Change the autogenerated stub
    }

   /* public function testLoadUserAction()
    {

        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("GET", "/user/32132dsf132ds1f3ds21fsd");

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }*/

    /*public function testDeleteUser()
    {
        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("DELETE", "/user_delete/32132dsf132ds1f3ds21fsd");

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }*/

    /*function testCreateUserAction()
    {
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("POST", "/user", array(), array(), array(),

            '{"firstname":"aifaska","lastname":"karim","birthday":"2000-08-01"}', true);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        //je recupere l'id renvoyer je le transforme en tableau
        $content = json_decode($client->getResponse()->getContent(), true);

        //je recup l'id grace a content[id]
        $user = $em->getRepository(User::class)->find($content['id']);

        $this->assertEquals("aifaska", $user->getFirstname());
        $this->assertEquals("karim", $user->getLastname());
        $this->assertEquals("2000-08-01", $user->getBirthday()->format('Y-m-d'));

    }*/

   /* public function testModifyUserAction()
    {
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("PUT", "/user/modify/32132dsf132ds1f3ds21fsd", array(), array(), array(),
            '{"id":"32132dsf132ds1f3ds21fsd","lastname":"Mill","firstname":"Mike",
            "birthday":"1988-08-01"}', true);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $user = $em->getRepository(User::class)->findOneBy(["id" => "32132dsf132ds1f3ds21fsd"]);

        $this->assertEquals("Mill", $user->getLastname());
        $this->assertEquals("Mike", $user->getFirstname());
        $this->assertEquals("1988-08-01", $user->getBirthday()->format('Y-m-d'));

    }*/

    public function testLoadAllUserAction()
    {

        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("GET", "/userAll");

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $response = $client->getResponse();

        $expected = '[{"id":"025caf9e-e6e6-4aac-a45b","firstname":"John","lastname":"Doe","birthday":"2018-08-01","comments":[{"title":"Le voyage de Chihiro","comment":"Une fillette de 10 ans, prise au pi\u00e8ge dans une maison sur la plage hantee par des esprits et des fant\u00f4mes, doit combattre sorci\u00e8res et dragons"}]},{"id":"32132dsf132ds1f3ds21fsd","firstname":"Abdellah","lastname":"Ailali","birthday":"2018-08-01","comments":[{"title":"Le ch\u00e2teau ambulant","comment":"La jeune Sophie, \u00e2g\u00e9e de 18 ans, travaille sans rel\u00e2che dans la boutique de chapelier que tenait son p\u00e8re avant de mourir. Lors de l\u0027une de ses rares sorties en ville, elle fait la connaissance de Hauru le Magicien"}]},{"id":"4eb298dd-5cd7-4d10-9b9q","firstname":"ben","lastname":"Malik","birthday":"2018-08-01","comments":[{"title":"Kill Bill","comment":"Condamnee \u00e0 mort par son propre patron Bill, une femme-assassin survit \u00e0 une balle dans la t\u00eate.  Quatre ans plus tard elle sort du coma et jure d\u2019avoir sa vengeance.\u2026 "}]}]';
        var_dump($expected);
        $this->assertEquals($expected, $response->getContent());
    }


    private function createDb(EntityManager $em)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = [];
        /** @var ClassMetadata $class */

        foreach ($em->getMetadataFactory()->getAllMetadata() as $class) {
            $classes[] = $class;
        }

        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }


}