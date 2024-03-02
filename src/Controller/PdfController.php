<?php

// src/Controller/PdfController.php

namespace App\Controller;

use App\Entity\Cnam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Consultation; // Assurez-vous d'importer l'entité Consultation si ce n'est pas déjà fait

class PdfController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Injection de dépendance via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePDF(): Response
    {
        // Récupérer les consultations depuis la base de données
        $consultations = $this->entityManager->getRepository(Consultation::class)->findAll();

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // ... Paramètres du document, en-tête, pied de page, etc.

        // Ajout d'une page
        $pdf->AddPage();
        $pdf->Image('C:/xampp/htdocs/pidev-GestionConsultationsEtCnam/images/logo.png', 170, 10, 30, 0, '', '', '', false, 300, '', false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12); // Police, style (gras), taille
        $pdf->Cell(0, 10, 'My eDr ', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Gérer PDF', 0, 1, 'C');
        $pdf->Ln(20);

// Construction du tableau HTML dans le PDF
        $pdf->SetFont('helvetica', 'B', 9); // Police, style (gras), taille

// Construction du tableau HTML dans le PDF
        $html = '<table border="1">
    <thead>
        <tr style="background-color:#ccc;"> <!-- Couleur de fond pour les titres des colonnes -->
            <th scope="col">Nom du Patient</th>
            <th scope="col">Description</th>
            <th scope="col">Durée Maladie</th>
            <th scope="col">Poids</th>
            <th scope="col">Taille</th>
            <th scope="col">Température</th>
            <th scope="col">Fréquence Cardiaque</th>
            <th scope="col">Respiration</th>
            <th scope="col">Conseils</th>
            <th scope="col">Médicament</th>
            <th scope="col">Date Prochaine</th>
        </tr>
    </thead>
    <tbody>';
        $compteur = 1; // Initialisation du compteur
        foreach ($consultations as $consultation) {
            $html .= '<tr>';
            $html .= '<td>' . $consultation->getRdv()->getPatient()->getLastName() . '</td>';
            $html .= '<td>' . $consultation->getDescription() . '</td>';
            $html .= '<td>' . ($consultation->getDureeMaladie() ? $consultation->getDureeMaladie()->format('Y-m-d H:i:s') : '') . '</td>';
            $html .= '<td>' . $consultation->getPoids() . '</td>';
            $html .= '<td>' . $consultation->getTaille() . '</td>';
            $html .= '<td>' . $consultation->getTemperature() . '</td>';
            $html .= '<td>' . $consultation->getFrequenceCardique() . '</td>';
            $html .= '<td>' . $consultation->getRespiration() . '</td>';
            $html .= '<td>' . $consultation->getConseils() . '</td>';
            $html .= '<td>' . $consultation->getMedicament() . '</td>';
            $html .= '<td>' . ($consultation->getDateProchaine() ? $consultation->getDateProchaine()->format('Y-m-d H:i:s') : '') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

// Écriture du contenu HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');

// Afficher la date et l'heure en bas du fichier, centrée horizontalement
        $pdf->SetY(-40);
        $pdf->Cell(0, 10, date('d/m/Y H:i:s'), 0, false, 'C', 0, '', 0, false, 'T', 'M');

// Nom du fichier PDF à télécharger
        $filename = 'consultation_report_' . date('Ymd_His') . '.pdf';

// Téléchargement du PDF
        $pdf->Output($filename, 'D');

// Retourner une réponse vide, car le téléchargement est déjà initié
        return new Response();
    }


    #[Route('/imprimer/{id}', name: 'imprimer')]
    public function generatePDFPatient($id): Response
    {
        // Récupérer la consultation spécifique à partir de l'ID
        $consultation = $this->entityManager->getRepository(Consultation::class)->find($id);

        if (!$consultation) {
            throw $this->createNotFoundException('Consultation non trouvée');
        }

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Ajout d'une page
        $pdf->AddPage();

        // Ajouter le contenu de l'ordonnance PDF en fonction des informations de la consultation
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Ordonnance pour le patient ' . $consultation->getRdv()->getPatient()->getLastName(), 0, true, 'C');

        // Ajouter les détails de la consultation
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Description: ' . $consultation->getDescription(), 0, true);
        $pdf->Cell(0, 10, 'Durée Maladie: ' . ($consultation->getDureeMaladie() ? $consultation->getDureeMaladie()->format('Y-m-d H:i:s') : ''), 0, true);
        $pdf->Cell(0, 10, 'Poids: ' . $consultation->getPoids(), 0, true);
        $pdf->Cell(0, 10, 'Taille: ' . $consultation->getTaille(), 0, true);
        $pdf->Cell(0, 10, 'Température: ' . $consultation->getTemperature(), 0, true);
        $pdf->Cell(0, 10, 'Fréquence Cardiaque: ' . $consultation->getFrequenceCardique(), 0, true);
        $pdf->Cell(0, 10, 'Respiration: ' . $consultation->getRespiration(), 0, true);
        $pdf->Cell(0, 10, 'Conseils: ' . $consultation->getConseils(), 0, true);
        $pdf->Cell(0, 10, 'Médicament: ' . $consultation->getMedicament(), 0, true);
        $pdf->Cell(0, 10, 'Date Prochaine: ' . ($consultation->getDateProchaine() ? $consultation->getDateProchaine()->format('Y-m-d H:i:s') : ''), 0, true);

        // Nom du fichier PDF à télécharger
        $filename = 'ordonnance_patient_' . $consultation->getRdv()->getPatient()->getLastName() . '.pdf';

        // Téléchargement du PDF
        $pdf->Output($filename, 'D');

        // Retourner une réponse vide, car le téléchargement est déjà initié
        return new Response();
    }





    ////////////////cnam///////////////////
    #[Route('/imprimercnam/{id}', name: 'imprimercnam')]
    public function generatePDFCNAM($id): Response
    {
        // Récupérer le CNAM spécifique à partir de l'ID
        $cnam = $this->entityManager->getRepository(CNAM::class)->find($id);


        if (!$cnam) {
            throw $this->createNotFoundException('CNAM non trouvé');
        }

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Ajout d'une page
        $pdf->AddPage();
        $pdf->Image('C:/xampp/htdocs/pidev-GestionConsultationsEtCnam/images/cnam.jpg',  10, 10, 30, '', false, 300, '', false, false, 0, false, false, false);

        // Insérer le logo en haut à droite
        $pdf->Image('C:/xampp/htdocs/pidev-GestionConsultationsEtCnam/images/logo.png', 175, 10, 30 , '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Ajouter le contenu du CNAM PDF en fonction des informations du CNAM
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, '', 0, true, 'C');
        $pdf->Cell(0, 10, 'Détails du CNAM ', 0, 1, 'C');

        // Ajouter les détails du CNAM
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Nom: ' . ($cnam->getUser() ? $cnam->getUser()->getFullName() : 'Non assigné'), 0, true);
        $pdf->Cell(0, 10, 'Numéro Carnet: ' . $cnam->getNumeroCarnet(), 0, true);
        $pdf->Cell(0, 10, 'Prix Consultation: ' . $cnam->getPrixConsultation(), 0, true);
        $pdf->SetY(-40);
        $pdf->Cell(0, 10, date('d/m/Y H:i:s'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // Nom du fichier PDF à télécharger
        $filename = 'cnam_details_' . $cnam->getId() . '.pdf';

        // Téléchargement du PDF
        $pdf->Output($filename, 'D');

        // Retourner une réponse vide, car le téléchargement est déjà initié
        return new Response();
    }


}
