# Plan: Adapter le login et le register au projet

## Information Gathered

### Fichiers analysés:
1. `resources/views/auth/login.blade.php` - Page de connexion actuelle (défaut Jetstream)
2. `resources/views/auth/register.blade.php` - Page d'inscription actuelle
3. `resources/views/components/authentication-card-logo.blade.php` - Logo actuel (SVG violet par défaut)
4. `resources/views/layouts/guest.blade.php` - Layout invité avec thème bleu/indigo
5. `resources/views/components/authentication-card.blade.php` - Carte d'authentification
6. `app/Actions/Fortify/CreateNewUser.php` - Création d'utilisateur (crée une équipe mais pas d'entreprise)

### Contexte du projet:
- Application SaaS de facturation ("Facturation SaaS")
- Chaque utilisateur doit avoir une entreprise (table `companies`)
- Thème visuel: dégradé bleu/indigo (#3B82F6 à #4F46E5)
- Le layout invité contient un en-tête et un pied de page mais les balises sont mal fermées

## Plan

### Étape 1: Mettre à jour le logo d'authentification ✅
- Remplacer le SVG violet par défaut par un logo adapté au projet
- Utiliser un ícone de facture ou le texte "Facturation SaaS"
- Couleurs: bleu/indigo (#3B82F6)

### Étape 2: Adapter les pages login et register ✅
- Ajouter le fond dégradé bleu comme dans le layout guest
- Utiliser les mêmes couleurs et styles
- Garder la structure du formulaire mais améliorer le design

### Étape 3: Corriger le layout guest
- Ajouter les balises de fermeture manquantes
- Améliorer la mise en page globale

### Étape 4: Optionnel - Ajouter le champ entreprise à l'inscription
- Ajouter un champ "Nom de l'entreprise" au formulaire d'inscription
- Modifier `CreateNewUser.php` pour créer une entreprise automatiquement

## Dependent Files

1. `resources/views/components/authentication-card-logo.blade.php` - ✅ Modifié
2. `resources/views/auth/login.blade.php` - ✅ Modifié
3. `resources/views/auth/register.blade.php` - ✅ Modifié
4. `resources/views/layouts/guest.blade.php` - À corriger (optionnel)

## Followup Steps

1. Tester l'affichage des pages login et register
2. Vérifier que le thème est cohérent avec le reste de l'application
3. Tester l'inscription et vérifier la création de l'utilisateur

