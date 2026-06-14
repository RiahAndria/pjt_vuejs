# Projet Gestion Enseignants - VueJS 3 & PHP / PostgreSQL

Bienvenue dans le dépôt du projet ! Ce document fait le point sur l'avancement actuel de l'application et explique la marche à suivre pour installer et faire tourner le projet sur ta machine afin qu'on puisse développer ensemble.

---

## État actuel du projet

### Front-End (Vue.js 3 / Vite)
* **Connexion / Inscription (`LoginView.vue`) :** **OK** (Formulaire fonctionnel, bascule fluide, validations HTML/JS en temps réel, notifications Toasts animées en bas à gauche sans alertes natives).
* **Accueil (`AccueilView.vue`) :** **OK** (Structure en place avec menu de déconnexion et modale de confirmation, mais **quelques ajustements visuels et finitions restent à faire**).
* **Les 3 pages principales (Ajout, Liste, Bilan) :** **À faire**. Les bases, les routes et les fichiers de vues sont créés, mais le contenu reste à développer.

### Back-End (PHP 8 & PostgreSQL)
* **Authentification (`apis/validation.php`) :** **OK** (Vérification des données, hachage sécurisé des mots de passe, connexion automatique après inscription, renvoi des réponses au format JSON propre).
* **Base de données (`apis/config/db.php`) :** **OK** (Connexion PDO PostgreSQL fonctionnelle). *Note : Tu devras simplement modifier le mot de passe pour mettre le tien.*
* **Autres fichiers d'API :** **À faire**. Les dossiers et l'arborescence sont prêts à accueillir tes futurs scripts PHP pour la gestion des enseignants.

---

## Procédure d'installation (Pour ton PC)

Suis précisément ces étapes pour recréer l'environnement de développement sur ta machine :

### Clonage et initialisation de Vue.js
1. Télécharge ou clone ce projet dans ton répertoire local.
2. Assure-toi d'avoir **Node.js** installé sur ton PC.
3. Ouvre ton terminal à la racine du projet et génère la structure Vue propre en tapant :
   ```bash
   npm create vue@latest
4. Reste plus qu'à remplacer index.html et le contenu du dossier scr par ceux das ce dépôt

### Azzy moi je vais me reposer un peu aussi frère... Bon rétablissement à toi
