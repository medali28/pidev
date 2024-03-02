<?php

namespace App\Test\Controller;

use App\Entity\Cnam;
use App\Repository\CnamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CnamControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CnamRepository $repository;
    private string $path = '/cnam/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Cnam::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cnam index');

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
            'cnam[Numero_carnet]' => 'Testing',
            'cnam[Prix_consultation]' => 'Testing',
            'cnam[consultation]' => 'Testing',
        ]);

        self::assertResponseRedirects('/cnam/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cnam();
        $fixture->setNumero_carnet('My Title');
        $fixture->setPrix_consultation('My Title');
        $fixture->setConsultation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cnam');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cnam();
        $fixture->setNumero_carnet('My Title');
        $fixture->setPrix_consultation('My Title');
        $fixture->setConsultation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'cnam[Numero_carnet]' => 'Something New',
            'cnam[Prix_consultation]' => 'Something New',
            'cnam[consultation]' => 'Something New',
        ]);

        self::assertResponseRedirects('/cnam/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNumero_carnet());
        self::assertSame('Something New', $fixture[0]->getPrix_consultation());
        self::assertSame('Something New', $fixture[0]->getConsultation());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Cnam();
        $fixture->setNumero_carnet('My Title');
        $fixture->setPrix_consultation('My Title');
        $fixture->setConsultation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/cnam/');
    }
}
