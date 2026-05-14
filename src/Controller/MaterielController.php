<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;


#[Route('/materiel')]
class MaterielController extends AbstractController
{
    #[Route('/', name: 'materiel_index')]
    public function index(): Response
    {
        return $this->render('materiel/index.html.twig');
    }

    #[Route('/api/list', name: 'materiel_api_list', methods: ['GET'])]
    public function apiList(Request $request, MaterielRepository $repo): JsonResponse
    {
        $draw = $request->query->getInt('draw', 1);
        $start = $request->query->getInt('start', 0);
        $length = $request->query->getInt('length', 10);
    
        $searchParams = $request->query->all('search');
        $search = $searchParams['value'] ?? '';

        $total = $repo->countAvailable();
        $filtered = $repo->countAvailableSearch($search);
        $materiels = $repo->findAvailablePaginated($start, $length, $search);

        $data = [];
        foreach ($materiels as $m) {
            $data[] = [
                'id'          => $m->getId(),
                'nom'         => $m->getNom(),
                'prixHT'      => number_format($m->getPrixHT(), 2) . ' €',
                'tva'         => $m->getTva()->getLibelle(),
                'prixTTC'     => number_format($m->getPrixTTC(), 2) . ' €',
                'quantite'    => $m->getQuantite(),
                'dateCreation'=> $m->getDateCreation()->format('d/m/Y'),
            ];
        }

        return new JsonResponse([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    #[Route('/new', name: 'materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materiel->setDateCreation(new \DateTime());
            $em->persist($materiel);
            $em->flush();
            return $this->redirectToRoute('materiel_index');
        }

        return $this->render('materiel/form.html.twig', [
            'form'  => $form->createView(),
            'titre' => 'Ajouter un matériel',
        ]);
    }

    #[Route('/{id}/edit', name: 'materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Materiel $materiel, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('materiel_index');
        }

        return $this->render('materiel/form.html.twig', [
            'form'  => $form->createView(),
            'titre' => 'Modifier un matériel',
        ]);
    }

    #[Route('/{id}/decrement', name: 'materiel_decrement', methods: ['POST'])]
    public function decrement(
        Materiel $materiel,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire('%env(ADMIN_EMAIL)%')]
        string $adminEmail
    ): JsonResponse {
        if ($materiel->getQuantite() > 0) {
            $materiel->setQuantite($materiel->getQuantite() - 1);
            $em->flush();

            if ($materiel->getQuantite() === 0) {
                $email = (new \Symfony\Component\Mime\Email())
                    ->from('noreply@materiel.fr')
                    ->to($adminEmail)
                    ->subject('Stock épuisé : ' . $materiel->getNom())
                    ->html(
                        '<h2>Stock épuisé</h2>' .
                        '<p>Le produit <strong>' . $materiel->getNom() . '</strong> est arrivé à 0.</p>' .
                        '<p>Pensez à réapprovisionner.</p>'
                    );

                $mailer->send($email);
            }
        }

        return new JsonResponse(['quantite' => $materiel->getQuantite()]);
    }
    #[Route('/{id}/pdf', name: 'materiel_pdf', methods: ['GET'])]
    public function pdf(Materiel $materiel): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->renderView('materiel/pdf.html.twig', [
            'materiel' => $materiel,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="materiel-' . $materiel->getId() . '.pdf"',
            ]
        );
    }
    #[Route('/{id}', name: 'materiel_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Materiel $materiel): JsonResponse
    {
        return new JsonResponse([
            'id'          => $materiel->getId(),
            'nom'         => $materiel->getNom(),
            'prixHT'      => number_format($materiel->getPrixHT(), 2) . ' €',
            'tva'         => $materiel->getTva()->getLibelle(),
            'prixTTC'     => number_format($materiel->getPrixTTC(), 2) . ' €',
            'quantite'    => $materiel->getQuantite(),
            'dateCreation'=> $materiel->getDateCreation()->format('d/m/Y'),
        ]);
    }
}