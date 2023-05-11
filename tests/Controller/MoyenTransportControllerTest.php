<?php

namespace App\Test\Controller;

use App\Entity\MoyenTransport;
use App\Repository\MoyenTransportRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MoyenTransportControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MoyenTransportRepository $repository;
    private string $path = '/moyen/transport/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(MoyenTransport::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MoyenTransport index');

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
            'moyen_transport[matricule]' => 'Testing',
            'moyen_transport[num]' => 'Testing',
            'moyen_transport[capacite]' => 'Testing',
            'moyen_transport[type_vehicule]' => 'Testing',
            'moyen_transport[marque]' => 'Testing',
            'moyen_transport[etat]' => 'Testing',
            'moyen_transport[id_ligne]' => 'Testing',
            'moyen_transport[station]' => 'Testing',
        ]);

        self::assertResponseRedirects('/moyen/transport/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new MoyenTransport();
        $fixture->setMatricule('My Title');
        $fixture->setNum('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setType_vehicule('My Title');
        $fixture->setMarque('My Title');
        $fixture->setEtat('My Title');
        $fixture->setId_ligne('My Title');
        $fixture->setStation('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MoyenTransport');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new MoyenTransport();
        $fixture->setMatricule('My Title');
        $fixture->setNum('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setType_vehicule('My Title');
        $fixture->setMarque('My Title');
        $fixture->setEtat('My Title');
        $fixture->setId_ligne('My Title');
        $fixture->setStation('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'moyen_transport[matricule]' => 'Something New',
            'moyen_transport[num]' => 'Something New',
            'moyen_transport[capacite]' => 'Something New',
            'moyen_transport[type_vehicule]' => 'Something New',
            'moyen_transport[marque]' => 'Something New',
            'moyen_transport[etat]' => 'Something New',
            'moyen_transport[id_ligne]' => 'Something New',
            'moyen_transport[station]' => 'Something New',
        ]);

        self::assertResponseRedirects('/moyen/transport/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMatricule());
        self::assertSame('Something New', $fixture[0]->getNum());
        self::assertSame('Something New', $fixture[0]->getCapacite());
        self::assertSame('Something New', $fixture[0]->getType_vehicule());
        self::assertSame('Something New', $fixture[0]->getMarque());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getId_ligne());
        self::assertSame('Something New', $fixture[0]->getStation());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new MoyenTransport();
        $fixture->setMatricule('My Title');
        $fixture->setNum('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setType_vehicule('My Title');
        $fixture->setMarque('My Title');
        $fixture->setEtat('My Title');
        $fixture->setId_ligne('My Title');
        $fixture->setStation('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/moyen/transport/');
    }
}
