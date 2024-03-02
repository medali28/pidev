<?php

namespace App\Command;

use App\Entity\RendezVous;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'SendAppointmentReminders',
    description: 'Send appointment reminders to patients two days before the appointment',
)]
class SendAppointmentRemindersCommand extends Command
{

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    { $email = (new Email())
        ->from(new Address('myedr@gmail.com', 'My edr'))
        ->to("issrakhemir33@gmail.com")
        ->subject('Reminder: Your Appointment')
        ->text('Test');

        $this->mailer->send($email);
        return 1;
      /*  $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $appointments = $entityManager->getRepository(RendezVous::class)->findAppointmentsToRemind();

        foreach ($appointments as $appointment) {
            $patientEmail = $appointment->getPatient()->getEmail();
            $this->sendReminderEmail($patientEmail, $appointment);
            $appointment->setReminderSent(true);
            $entityManager->persist($appointment);
        }

        $entityManager->flush();*/
    }

     function sendReminderEmail(): void
    {

        $email = (new Email())
            ->from(new Address('myedr@gmail.com', 'My edr'))
            ->to("issrakhemir33@gmail.com")
            ->subject('Reminder: Your Appointment')
            ->text('Test');

        $this->mailer->send($email);
    }

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }
}
