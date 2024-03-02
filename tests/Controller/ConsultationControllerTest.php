<?php

namespace App\Test\Controller;

use App\Entity\Consultation;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConsultationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ConsultationRepository $repository;
    private string $path = '/consultation/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Consultation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Consultation index');

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
            'consultation[description]' => 'Testing',
            'consultation[duree_maladie]' => 'Testing',
            'consultation[poids]' => 'Testing',
            'consultation[taille]' => 'Testing',
            'consultation[temperature]' => 'Testing',
            'consultation[frequence_cardique]' => 'Testing',
            'consultation[respiration]' => 'Testing',
            'consultation[conseils]' => 'Testing',
            'consultation[medicament]' => 'Testing',
            'consultation[date_prochaine]' => 'Testing',
            'consultation[rdv]' => 'Testing',
        ]);

        self::assertResponseRedirects('/consultation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Consultation();
        $fixture->setDescription('My Title');
        $fixture->setDuree_maladie('My Title');
        $fixture->setPoids('My Title');
        $fixture->setTaille('My Title');
        $fixture->setTemperature('My Title');
        $fixture->setFrequence_cardique('My Title');
        $fixture->setRespiration('My Title');
        $fixture->setConseils('My Title');
        $fixture->setMedicament('My Title');
        $fixture->setDate_prochaine('My Title');
        $fixture->setRdv('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Consultation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Consultation();
        $fixture->setDescription('My Title');
        $fixture->setDuree_maladie('My Title');
        $fixture->setPoids('My Title');
        $fixture->setTaille('My Title');
        $fixture->setTemperature('My Title');
        $fixture->setFrequence_cardique('My Title');
        $fixture->setRespiration('My Title');
        $fixture->setConseils('My Title');
        $fixture->setMedicament('My Title');
        $fixture->setDate_prochaine('My Title');
        $fixture->setRdv('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'consultation[description]' => 'Something New',
            'consultation[duree_maladie]' => 'Something New',
            'consultation[poids]' => 'Something New',
            'consultation[taille]' => 'Something New',
            'consultation[temperature]' => 'Something New',
            'consultation[frequence_cardique]' => 'Something New',
            'consultation[respiration]' => 'Something New',
            'consultation[conseils]' => 'Something New',
            'consultation[medicament]' => 'Something New',
            'consultation[date_prochaine]' => 'Something New',
            'consultation[rdv]' => 'Something New',
        ]);

        self::assertResponseRedirects('/consultation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDuree_maladie());
        self::assertSame('Something New', $fixture[0]->getPoids());
        self::assertSame('Something New', $fixture[0]->getTaille());
        self::assertSame('Something New', $fixture[0]->getTemperature());
        self::assertSame('Something New', $fixture[0]->getFrequence_cardique());
        self::assertSame('Something New', $fixture[0]->getRespiration());
        self::assertSame('Something New', $fixture[0]->getConseils());
        self::assertSame('Something New', $fixture[0]->getMedicament());
        self::assertSame('Something New', $fixture[0]->getDate_prochaine());
        self::assertSame('Something New', $fixture[0]->getRdv());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Consultation();
        $fixture->setDescription('My Title');
        $fixture->setDuree_maladie('My Title');
        $fixture->setPoids('My Title');
        $fixture->setTaille('My Title');
        $fixture->setTemperature('My Title');
        $fixture->setFrequence_cardique('My Title');
        $fixture->setRespiration('My Title');
        $fixture->setConseils('My Title');
        $fixture->setMedicament('My Title');
        $fixture->setDate_prochaine('My Title');
        $fixture->setRdv('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/consultation/');
    }
}
