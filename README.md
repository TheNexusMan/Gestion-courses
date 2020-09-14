Projet BDW1 2019 : application web de gestion de course à pied

Cette application est utilisée par :
- un administrateur de l’association qui aura la charge de gérer la base de données
- les différents adhérents qui pourront visualiser les informations les concernant.

Profil d’utilisateur :
- Administrateur :
  Peut accéder à toutes les pages du site et modifier toutes les informations.
  
- Adhérent :
  Ne peut modifier que ses informations personnelles de sa page adherent.php.
  Il a accès aux pages : adherent.php, course.php, edition.php et epreuve.php en lecture seule.

Technos utilisées :
PHP, Bootstrap, SQL, JavaScript, JQuery et la bibliothèque d'icons FontAwesome.

Principaux fichiers.php :

index.php
Permet à l’utilisateur, qu’il soit administrateur, utilisateur ou nouvel utilisateur de se connecter au site.
Lors de la tentative de connexion si le pseudo entré n’existe pas dans la base de données un message indiquant que le pseudo n’existe pas s’affiche. Si le pseudo existe mais que le mot de passe est erroné, un message précisant que le mot de passe est incorrect s’affiche.

espaceperso.php
Page d’accueil de  l’administrateur.
Elle permet d’accéder à la page des adhérents : adherents.php ou à celle des courses : courses.php.

adherents.php
Affiche la liste de tous les adhérents.
La liste est triable et chaque adhérent peut être supprimé.
L’administrateur peut ajouter un nouvel utilisateur en cliquant sur le bouton “Ajouter un utilisateur”. Il entrera alors dans le formulaire un nouveau pseudo et mot de passe qui seront envoyés par mail à l’adhérent (l’envoi du mail n’est pas implémenté dans le site pour l’instant).
Le clic sur un adhérent mène vers sa page adherent.php

adherent.php
Page d’accueil des adhérents mais également visible par l’administrateur depuis adherents.php.
Permet d’afficher les informations et la liste des éditions participées par un adhérent.
Les informations sont toutes modifiables que ce soit par l’administrateur ou l’adhérent.
Si l’utilisateur connecté est un adhérent, c’est sa fiche qui sera affichée.
Si l’utilisateur est un nouvel adhérent, cette page sera affichée de sorte à ce qu’il soit invité à compléter son profil.
La liste des éditions est triable et le clic sur une édition mène à sa page edition.php

courses.php
Affiche la liste de toutes les courses.
La liste est triable et chaque course peut être supprimée.
L’administrateur peut ajouter une course en cliquant sur le bouton “Ajouter une course”. Il entrera alors dans le formulaire toutes les informations concernant la nouvelle course.
Le clic sur une course mène vers sa page course.php.

course.php
Affiche les informations d’une course et sa liste d’éditions.
Les informations sont toutes modifiables sauf le nom.
L’administrateur à la possibilité d’ajouter une édition en cliquant sur le bouton “Ajouter une édition”. Il entrera alors dans le formulaire toutes les informations concernant la nouvelle édition.
La liste des éditions est triable et chaque édition peut être supprimée.
Le clic sur une édition mène vers sa page edition.php.

edition.php
Affiche les informations d’une édition et sa liste d’épreuves.
Les informations sont toutes modifiables.
L’administrateur à la possibilité d’ajouter une épreuve en cliquant sur le bouton “Ajouter une épreuve”. Il entrera alors dans le formulaire toutes les informations concernant la nouvelle épreuve.
La liste des épreuves est triable et chaque épreuve peut être supprimée.
Le clic sur le nom de la course mène vers sa page course.php
Le clic sur une épreuve mène vers sa page epreuve.php.

epreuve.php
La page epreuve.php affiche toutes les informations sur l’épreuve choisie tel que le nom, la distance, dénivelée, plan, le type d’épreuve, l’adresse de départ, les tarifs. Toutes ces valeurs sont modifiables pour le profil ‘Administrateur’, il peut aussi ajouter et supprimer des tarifs.
Elle affiche aussi, en cas de résultats connus, des informations et statistiques sur les adhérents ayant participés à la course tel que le nombre de clubs, d’abandons, les moyennes de temps en fonction du genre (Homme/Femme), le meilleur et dernier temps effectué par un adhérent, les moyennes de temps des adhérents (tout genre confondu), le temps du vainqueur (Adhérent ou non) ainsi que le nombre d’adhérents et le meilleur et dernier rang qu’ils ont obtenus.
Un tableau de ces adhérents est aussi affiché avec un récapitulatif de leurs temps.
Cliquer sur les lignes redirige vers leur page d’adhérent. Si l’utilisateur est du type adhérent, les lignes ne sont pas cliquables, il n’a pas accès aux profils des autres adhérents.
Si les résultats ne sont pas encore enregistrés, l’utilisateur est invité à envoyer sur le serveur les fichiers csv résultats et temps. Une fois envoyés, la page est mise à jour avec les statistiques de la course et les résultats des adhérents.

404.php
S’affiche en cas d’accès à un fichier non existant du site.
