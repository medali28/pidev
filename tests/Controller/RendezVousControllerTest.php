<?php

namespace App\Test\Controller;

use App\Entity\RendezVous;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RendezVousControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RendezVousRepository $repository;
    private string $path = '/rendez/vous/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(RendezVous::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RendezVou index');

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
            'rendez_vou[date_heure]' => 'Testing',
            'rendez_vou[status_rdv]' => 'Testing',
            'rendez_vou[description]' => 'Testing',
            'rendez_vou[reponse_refuse]' => 'Testing',
            'rendez_vou[urgence]' => 'Testing',
            'rendez_vou[patient]' => 'Testing',
            'rendez_vou[medecin]' => 'Testing',
            'rendez_vou[expert]' => 'Testing',
            'rendez_vou[consultation]' => 'Testing',
        ]);

        self::assertResponseRedirects('/rendez/vous/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new RendezVous();
        $fixture->setDate_heure('My Title');
        $fixture->setStatus_rdv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setReponse_refuse('My Title');
        $fixture->setUrgence('My Title');
        $fixture->setPatient('My Title');
        $fixture->setMedecin('My Title');
        $fixture->setExpert('My Title');
        $fixture->setConsultation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RendezVou');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new RendezVous();
        $fixture->setDate_heure('My Title');
        $fixture->setStatus_rdv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setReponse_refuse('My Title');
        $fixture->setUrgence('My Title');
        $fixture->setPatient('My Title');
        $fixture->setMedecin('My Title');
        $fixture->setExpert('My Title');
        $fixture->setConsultation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'rendez_vou[date_heure]' => 'Something New',
            'rendez_vou[status_rdv]' => 'Something New',
            'rendez_vou[description]' => 'Something New',
            'rendez_vou[reponse_refuse]' => 'Something New',
            'rendez_vou[urgence]' => 'Something New',
            'rendez_vou[patient]' => 'Something New',
            'rendez_vou[medecin]' => 'Something New',
            'rendez_vou[expert]' => 'Something New',
            'rendez_vou[consultation]' => 'Something New',
        ]);

        self::assertResponseRedirects('/rendez/vous/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate_heure());
        self::assertSame('Something New', $fixture[0]->getStatus_rdv());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getReponse_refuse());
        self::assertSame('Something New', $fixture[0]->getUrgence());
        self::assertSame('Something New', $fixture[0]->getPatient());
        self::assertSame('Something New', $fixture[0]->getMedecin());
        self::assertSame('Something New', $fixture[0]->getExpert());
        self::assertSame('Something New', $fixture[0]->getConsultation());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new RendezVous();
        $fixture->setDate_heure('My Title');
        $fixture->setStatus_rdv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setReponse_refuse('My Title');
        $fixture->setUrgence('My Title');
        $fixture->setPatient('My Title');
        $fixture->setMedecin('My Title');
        $fixture->setExpert('My Title');
        $fixture->setConsultation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/rendez/vous/');
    }
}
