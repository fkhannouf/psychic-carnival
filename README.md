# psychic-carnival

Application de gestion de tâches (Todo) avec système d'utilisateurs.

## Prérequis

- Node.js (v14 ou supérieur)
- PostgreSQL (v12 ou supérieur)
- npm ou yarn

## Installation

1. Cloner le dépôt :
```bash
git clone https://github.com/fkhannouf/psychic-carnival.git
cd psychic-carnival
```

2. Installer les dépendances :
```bash
npm install
```

3. Configurer la base de données :
```bash
# Créer une base de données PostgreSQL
createdb psychic_carnival_dev

# Copier le fichier d'environnement exemple
cp .env.example .env

# Modifier .env avec vos paramètres de connexion
```

## Migrations de base de données

Ce projet utilise `node-pg-migrate` pour gérer les migrations de schéma de base de données.

### Structure des tables

#### Users (Utilisateurs)
- `id` : Identifiant unique (clé primaire)
- `email` : Adresse e-mail (unique)
- `username` : Nom d'utilisateur (unique)
- `password_hash` : Hash du mot de passe
- `first_name` : Prénom
- `last_name` : Nom de famille
- `created_at` : Date de création
- `updated_at` : Date de dernière modification

#### Todos (Tâches)
- `id` : Identifiant unique (clé primaire)
- `user_id` : Référence à l'utilisateur (clé étrangère)
- `title` : Titre de la tâche
- `description` : Description détaillée
- `status` : Statut (pending, in_progress, completed)
- `priority` : Priorité (low, medium, high)
- `due_date` : Date d'échéance
- `completed_at` : Date de complétion
- `created_at` : Date de création
- `updated_at` : Date de dernière modification

### Commandes de migration

```bash
# Exécuter toutes les migrations en attente
npm run migrate:up

# Annuler la dernière migration
npm run migrate:down

# Créer une nouvelle migration
npm run migrate:create nom-de-la-migration

# Afficher le statut des migrations
npm run migrate -- status
```

### Configuration de la base de données

La configuration de la base de données est dans `database.json`. Vous pouvez également utiliser la variable d'environnement `DATABASE_URL` :

```bash
DATABASE_URL=postgresql://user:password@localhost:5432/database_name npm run migrate:up
```

## Développement

Les migrations existantes incluent :
1. `1733740000000_create-users-table.js` - Crée la table des utilisateurs
2. `1733740001000_create-todos-table.js` - Crée la table des tâches

## Licence

ISC
