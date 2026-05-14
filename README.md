# Module Matériel - Symfony

Application de gestion de matériel avec suivi de stock, développée avec Symfony 7.

## Prérequis

- PHP 8.2+
- Composer
- Symfony CLI

## Installation

```bash
git clone <url-du-repo>
cd Module_materiel
composer install
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony serve
```

Accéder à : http://127.0.0.1:8000/materiel/

## Configuration

### Base de données
Le projet utilise SQLite, aucune installation requise.
Le fichier de base de données est créé automatiquement dans `var/data.db` au moment de la migration.

### Emails (notifications de stock épuisé)
Le projet utilise [Mailpit](https://github.com/axllent/mailpit) comme serveur mail local.

**Installation de Mailpit :**
```bash
curl -L https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz -o /tmp/mailpit.tar.gz
tar -xzf /tmp/mailpit.tar.gz -C /tmp
mkdir -p ~/.local/bin
cp /tmp/mailpit ~/.local/bin/
chmod +x ~/.local/bin/mailpit
export PATH="$HOME/.local/bin:$PATH"
```

**Lancer Mailpit dans un terminal séparé avant de tester :**
```bash
mailpit
```

Interface web des emails reçus : http://localhost:8025

Les emails sont envoyés automatiquement à `ADMIN_EMAIL` (défini dans `.env`) quand un produit tombe à 0 en stock.

## Fonctionnalités

- Liste du matériel avec jQuery DataTables (serverSide)
- Recherche par nom en temps réel
- Popup "Voir" avec détail du produit
- Formulaire d'ajout et modification avec calcul automatique HT/TTC/TVA
- Décrémentation du stock (le produit disparaît de la liste à 0)
- Email automatique à l'admin quand un produit tombe à 0
- Génération PDF du détail produit

## Données de test

Des fixtures sont incluses avec 3 taux de TVA (5.5%, 10%, 20%) et 5 produits d'exemple.
Pour les recharger :
```bash
php bin/console doctrine:fixtures:load
```
