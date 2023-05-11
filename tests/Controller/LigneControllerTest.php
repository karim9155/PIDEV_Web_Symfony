<?php

namespace App\Test\Controller;

use App\Entity\Ligne;
use App\Repository\LigneRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LigneControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private LigneRepository $repository;
    private string $path = '/ligne/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Ligne::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ligne index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'ligne[nom_ligne]' => 'Testing',
            'ligne[type_ligne]' => 'Testing',
        ]);

        self::assertResponseRedirects('/ligne/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ligne();
        $fixture->setNom_ligne('My Title');
        $fixture->setType_ligne('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ligne');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ligne();
        $fixture->setNom_ligne('My Title');
        $fixture->setType_ligne('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ligne[nom_ligne]' => 'Something New',
            'ligne[type_ligne]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ligne/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom_ligne());
        self::assertSame('Something New', $fixture[0]->getType_ligne());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Ligne();
        $fixture->setNom_ligne('My Title');
        $fixture->setType_ligne('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/ligne/');
    }
}
