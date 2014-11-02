<?php

namespace Msi\CmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CrudControllerTest extends WebTestCase
{
    public function testPageCrud()
    {
        $input1 = [
            'form[translations][0][title]' => 'Test',
        ];

        $input2 = [
            'form[translations][0][title]' => 'Foo',
        ];

        $this->baseCrudTest('page', $input1, $input2, 'Test', 'Foo');
    }

    public function testUserCrud()
    {
        $input1 = [
            'form[email]' => 'test@test.test',
            'form[plainPassword][first]' => '123456',
            'form[plainPassword][second]' => '123456',
        ];

        $input2 = [
            'form[email]' => 'foo@foo.foo',
            'form[plainPassword][first]' => '123456',
            'form[plainPassword][second]' => '123456',
        ];

        $this->baseCrudTest('user', $input1, $input2, 'test@test.test', 'foo@foo.foo');
    }

    private function baseCrudTest($path, $input1, $input2, $name1, $name2)
    {
        // Create a new client to browse the application
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'alexisjoubert@groupemsi.com',
            'PHP_AUTH_PW'   => '123456',
        ]);

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/'.$path);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $crawler = $client->click($crawler->filter('a.msi_admin_crud_new')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('submit')->form($input1);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $id = intval($crawler->filter('.entity_id')->text());

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('h1>small:contains("'.$name1.'")')->count());

        // Edit the entity
        $crawler = $client->click($crawler->filter('a.msi_admin_crud_edit')->link());

        $form = $crawler->selectButton('submit')->form($input2);

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('h1>small:contains("'.$name2.'")')->count());

        // Delete the entity
        $crawler = $client->click($crawler->filter('a.msi_admin_crud_delete')->link());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $crawler = $client->request('GET', '/admin/'.$path.'/show?id='.$id);
        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
