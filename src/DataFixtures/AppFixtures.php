<?php

namespace App\DataFixtures;

use App\Entity\TVA;
use App\Entity\Materiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tva20 = new TVA();
        $tva20->setLibelle('TVA 20%');
        $tva20->setValeur(0.2);
        $manager->persist($tva20);

        $tva10 = new TVA();
        $tva10->setLibelle('TVA 10%');
        $tva10->setValeur(0.1);
        $manager->persist($tva10);

        $tva55 = new TVA();
        $tva55->setLibelle('TVA 5.5%');
        $tva55->setValeur(0.055);
        $manager->persist($tva55);

        $materiels = [
            ['Ordinateur portable', 800.00, $tva20, 5],
            ['Souris sans fil', 25.00, $tva20, 15],
            ['Clavier mécanique', 60.00, $tva10, 8],
            ['Écran 27"', 300.00, $tva20, 3],
            ['Câble HDMI', 10.00, $tva55, 20],
        ];

        foreach ($materiels as [$nom, $prixHT, $tva, $quantite]) {
            $materiel = new Materiel();
            $materiel->setNom($nom);
            $materiel->setPrixHT($prixHT);
            $materiel->setTva($tva);
            $materiel->setPrixTTC(round($prixHT * (1 + $tva->getValeur()), 2));
            $materiel->setQuantite($quantite);
            $materiel->setDateCreation(new \DateTime());
            $manager->persist($materiel);
        }

        $manager->flush();
    }
}