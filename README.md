# Module Matériel - Symfony

## Prérequis
- PHP 8.2+
- Composer
- Symfony CLI

## Installation

```bash
git clone <url-du-repo>
cd materiel
composer install
cp .env .env.local
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony serve
```

Accéder à : http://127.0.0.1:8000/materiel/

## Fonctionnalités
- Liste du matériel avec jQuery DataTables (serverSide)
- Recherche par nom en temps réel
- Popup "Voir" avec détail du produit
- Formulaire d'ajout et modification avec calcul automatique HT/TTC/TVA
- Décrémentation du stock (disparition à 0)
- Email automatique à l'admin quand un produit tombe à 0
- Génération PDF du détail produit
