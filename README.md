

# Installation de LDAP

sudo apt install php-ldap

## Rédémarrage de Apache 2

sudo service apache2 restart

# Dépendance symfony/ldap

composer require symfony/ldap

## Configuration dans servces.yaml

Utilisation du serveur ldap de test
https://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/


---

# Installation de PostgreSQL

sudo apt install postgresql postgresql-client php-pgsql

## Rédémarrage de Apache 2

sudo service apache2 restart


## Configuratiion du mot de passe, et création de la base

### On passe en root
sudo -i

### On se fait passer pour l'utilisateur postgres
su - postgres


### Ouvrir le client postgres et changer le mot de passe

psql -c "ALTER USER postgres WITH password 'stage'"

createdb formation

### On revient à notre utilisateur stage

exit
exit

## Configuration du fichier .env

DATABASE_URL="postgresql://postgres:stage@127.0.0.1:5432/formation?serverVersion=13&charset=utf8"

## Vérification avec php bin/console

php bin/console doctrine:schema:validate


