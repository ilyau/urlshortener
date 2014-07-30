<?php

namespace Acme\UrlshortenerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use \Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultControllerTest extends WebTestCase
{
    private function auth(&$client)
    {
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("Вход")')->count() > 0);

        $form = $crawler->selectButton('Войти')->form();

        $crawler = $client->submit($form, [
            '_username' => 'user',
            '_password' => 'userpass'
        ]);

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $crawler = $client->followRedirect();

        return $crawler;
    }

    private function createShortUrl($url, &$crawler, &$client)
    {
        $form = $crawler->selectButton('Сократить')->form([
            'url' => 'test'
        ]);
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("URL не валиден")')->count() > 0);

        $form = $crawler->selectButton('Сократить')->form([
            'url' => $url
        ]);
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("vk.com")')->count() > 0);
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $this->auth($client);

        $this->assertTrue($crawler->filter('html:contains("Сократить ссылку")')->count() > 0);
    }

    public function testShortUrl()
    {
        $client = static::createClient();
        $crawler = $this->auth($client);

        $this->createShortUrl('http://vk.com/', $crawler, $client);
    }

    public function testDecode()
    {
        // DJ SNAKE/ALESIA - Bird Machine (Record Mix)

        $client = static::createClient();
        $crawler = $this->auth($client);

        $this->createShortUrl('http://vk.com/', $crawler, $client);

        $container = $client->getContainer();
        $em = $container->get('doctrine');

        $url = $em->getRepository('AcmeUrlshortenerBundle:Url')->findBy([], ['id' => 'DESC'], 1)[0];

        $urlshortenerService = $container->get('urlshortener.service');

        $code = $urlshortenerService->generateCode($url->getId());

        $crawler = $client->request('GET', $container->get('router')->generate('acme_urlshortener_decode', [
            'code' => $code
        ]) );

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);

        $this->assertTrue($client->getResponse()->getTargetUrl() === 'http://vk.com/');
    }
}
