# Apotheosis project Oclock Back

Projet réaliser lors de l'apothéose durant la formation chez [O'Clock](https://oclock.io/)

Déjà retrouvé à dînez chez des amis et leurs lasagnes sont tout simplement délicieuses ?!?

Avec O’Miam le réseau social des recettes de cuisine, retrouverez facilement la recettes de vos amis !!!

[Demo](https://omiam-v1.surge.sh/)

### Fonctionnalités du Projet

- Création d’un utilisateur
- Edition du compte Utilisateur
- Connexion d’un utilisateur
- Liste des recettes de l'utilisateur
- Ajout d’un commentaire à une recette
- Recherche simple d'une recette
- Recherche par catégories
- Contact via un formulaire
- Page d’erreur (404)
- Miamer* une recette

*Miamer : similaire aux favoris

## Pré-requis:

- [Apache](https://httpd.apache.org/)
- [Composer](https://getcomposer.org/)

## Langages utilisés 

- [Symfony](https://fr.reactjs.org/)


# Installer les dépendances :

	$ composer install
	
# Lancez le serveur :

	$ php -S 0.0.0.0:8080 -t public


### Commandes utiles :

``` php bin/console cache:clear ``` : videz les caches 
	
``` php bin/console lexik:jwt:generate-keypair --overwrite``` : à la première installation



-------


## Auteurs

* **Theneau Maxime** _alias_ [@MaximeTheneau](https://github.com/MaximeTheneau)

* **Loïc Maurin** _alias_ [@LoicMaurin](https://github.com/LoicMAURIN)

* **Pauline Thomelin** _alias_ [@paulinethomelin ](https://github.com/paulinethomelin)

* **Nathalie Tournon** _alias_ [@NathalieTournon ](https://github.com/NathalieTournon)

* **Adrien Pinilla** _alias_ [@AdrienPinilla ](https://github.com/AdrienPinilla)
