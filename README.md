# seenovia_0
base de donnée et site web pour seenovia

# Cahier des charges 
## Problématique : 
Fournir une interface poour enregistrer et modifier les données des champs des agriculteurs au cours de l'année. Cela doit être visible des deux coté (par les administrateurs et par les agriculteurs) et peut être chargé avec des fichiers csv extérieur 

## Besoin spécifiques 
### Besoin agriculteurs 
- voir leur base de donnée 
- modifier les éléments de leur base 
- interface simple et méthode de connection simple
- (conserver les données ?)

### Besoin admin (utilisateur seenovia)
- voir les données des agriculteurs 
- modifier les données des agriculteurs
- ajouter de nouveaux agriculteurs a la bases facilement 
- conserver les données sur plusieurs années
- faire évoluer le tableau de la base  
- grouper les agriculeurs 
- définir des chefs de groupes 
- ajouter des lignes (différents types de champs, maïs, blé, colza ...)
- supprimer des agriculeurs 
- importer des données depuis des fichiers externes (CSV, powerbUI, etc... ?)
- interface simple a prendre en main 
- possibilité de retrouver facilement des données dans la table (recherche par nom de l'utilisateur, de son groupe, de son type de champ, etc...)
- 


## Identificationd des réponses
### agriculteurs 
- Une page simple avec leur tableau personnel et des outils de modification des données de celui ci 
- Une option d'enregistrement au format CSV ? 

### administrateurs
- Une page d'observation, d'édition et de suppression des données des agriculeurs 
- Une page d'observation, d'édition, de création et de suppression des groupes d'agriculteurs 
- Une page d'ajout de nouveaux agriculteurs ainsi que leurs données : 
Plusieurs options ici 
-- création de l'agriculteurs 
-> création a la main en insérant toutes les données connues manuellement 
-> création a partir de fichier CSV (un fichier pour le "compte" et un autre pour les champs associé)
-> création de plusieurs comtpe agri / plusieurs association de champs a ce compte d'un coup avec un fichier CSV 
-> donner l'option "remplacer la base actuelle a partir du fichier" pour réadapter la base et ses colonnes a partir du fichier 
--> créer des alertes dans le cas ou les colonnes n'ont pas le même nom / ne sont pas les mêmes 
-> si l'utilisateur veux modifier => confirmer sinon, bloquer 

- Une page d'observation des anciennes version de la table en conservant les fichiers CSV avec les anciennes infos 
Avec un bouton "sauvegarder la base actuelle" pour tout conserver 



# TODO
- se renseigner sur les intégration powerbUI 
- intégrer les options de groupes
- intégrer les options de modification de la table 
- 
