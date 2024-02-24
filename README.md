# Hackathon ESGI

### Mission
> "Grad school student office events management app".
> 5 days

### Must haves
> - MVC (from scratch)
> - No Framework
> - User auth & CRUD
> - Email notifications
> - Unit tests

### Authors
> - Louis Cauvet
> - Mathieu Moyaerts
> - ``me`` Maxime de la Rocheterie

### Project Achievements 
**What we successfully added on top of mission requirements**
> - Custom ORM
> - Custom Factories
> - Custom Entity Management
> - Dependency Injection Service
> - Custom Param converter

## Start the project

### Install packages

```shell
composer install
```

### Launch server

```shell
composer ss
```

### Launch tests

```shell
composer tests
```

### Create ``/.env.local``

Include this.
```dotenv
DB_USER="username"
DB_PASSWORD="password"
DB_NAME="dbname"
```

