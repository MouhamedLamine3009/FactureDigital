<?php

use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Middleware\AuthenticateSession;

return [

    /*
    |--------------------------------------------------------------------------
    | Jetstream Stack
    |--------------------------------------------------------------------------
    |
    | This configuration value informs Jetstream which "stack" you will be
    | using for your application. In general, this value is set for you
    | during installation and will not need to be changed after that.
    |
    */

    'stack' => 'livewire',

    /*
    |--------------------------------------------------------------------------
    | Jetstream Route Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware Jetstream will assign to the routes
    | that it registers with the application. When necessary, you may modify
    | these middleware; however, this default value is usually sufficient.
    |
    */

    'middleware' => ['web'],

    'auth_session' => AuthenticateSession::class,

    /*
    |--------------------------------------------------------------------------
    | Jetstream Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify the authentication guard Jetstream will use while
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */

    'guard' => 'sanctum',

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can even remove all of these if you need to.
    |
    */

    'features' => [
        Features::termsAndPrivacyPolicy(),
        Features::profilePhotos(),
        Features::teams(['invitations' => true]),
        Features::accountDeletion(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Terms & Privacy Policy (français)
    |--------------------------------------------------------------------------
    |
    | Texte affiché sur les pages « Conditions d'utilisation » et
    | « Politique de confidentialité ».
    |
    */

    'terms' => <<<'MD'
# Conditions d'utilisation

Bienvenue sur **DigiFact**, une application de facturation destinée aux entrepreneurs et PME.

En utilisant ce service, vous acceptez les présentes conditions d'utilisation. Si vous ne acceptez pas ces conditions, veuillez ne pas utiliser l'application.

## 1. Utilisation du service

- Vous devez fournir des informations exactes et à jour lors de la création de votre compte.
- Vous êtes seul responsable de la confidentialité de vos identifiants de connexion.
- Vous ne pouvez pas utiliser le service à des fins illicites.

## 2. Vos données

- Vos données (clients, documents, paiements) sont conservées telles que vous les saisissez.
- Nous ne partageons ni ne vendons vos données à des tiers.
- Vous pouvez à tout moment exporter ou supprimer les données de votre compte.

## 3. Facturation et paiement

Les abonnements éventuels sont facturés selon la grille tarifaire en vigueur. Les prix sont indiqués en FCFA. Aucun remboursement ne peut être demandé pour une période déjà consommée.

## 4. Responsabilité

DigiFact est fourni « en l'état ». Nous nous efforçons d'assurer la disponibilité du service, mais ne sommes pas responsables des dommages liés à une interruption, une perte de données ou une utilisation impropre.

## 5. Modification des conditions

Ces conditions peuvent être modifiées à tout moment. Les modifications seront publiées sur cette page et entreront en vigueur dès leur publication.

## 6. Contact

Pour toute question, contactez-nous via la page de contact de l'application.
MD,

    'policy' => <<<'MD'
# Politique de confidentialité

DigiFact respecte votre vie privée. Cette page explique quelles informations nous collectons et comment nous les utilisons.

## Informations collectées

- **Données de compte** : nom, adresse e-mail et mot de passe (haché).
- **Données de facturation** : informations de vos clients et de vos documents.
- **Données techniques** : adresse IP et type de navigateur lors de la connexion.

## Utilisation des données

Vos données sont utilisées exclusivement pour vous fournir le service : création de documents, envoi de factures et suivi des paiements. Elles ne sont jamais cédées à des tiers à des fins commerciales.

## Partage et sous-traitance

Nous ne partageons vos données avec aucun tiers, sauf lorsque la loi nous y oblige. Nos hébergeurs et prestataires de traitement agissent conformément à cette politique.

## Sécurité

- Les mots de passe sont stockés sous forme hachée (bcrypt).
- Les connexions sont protégées par HTTPS.
- Les documents PDF sont générés côté serveur et stockés de manière sécurisée.

## Vos droits

Vous pouvez à tout moment :
- modifier les informations de votre profil ;
- exporter l'intégralité de vos données ;
- supprimer définitivement votre compte et l'ensemble de ses données.

## Contact

Pour toute question relative à la confidentialité, contactez-nous via la page de contact de l'application.
MD,

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically
    | this will be the "public" disk but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

];
