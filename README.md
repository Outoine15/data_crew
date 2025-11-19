# Data Crew
le projet du groupe Data Crew pour les TP du projet info306 à l'USMB

diagrame (dev)
![image](Diagramme%20tp1%20info306(dev).drawio.svg)

diagrame (final)
![image](Diagramme%20tp1%20info306(rendu).drawio.svg)

description (final)
Ce schéma Entité-Association modélise le système d'information d'une plateforme de gestion pédagogique collaborative. Il est structuré pour répondre à trois besoins majeurs : la gestion administrative des utilisateurs, la planification logistique des cours (sessions) et le suivi pédagogique (compétences et évaluations).

1. Gestion des Acteurs et des Groupes
Le système distingue deux types d'acteurs principaux et une entité de regroupement :

Learner (Apprenant) : Cette entité représente l'utilisateur final (étudiant). Elle stocke les données d'identification (email, password) et d'identité (firstName, lastName). Chaque apprenant est caractérisé par un état de connexion via l'entité State (relation 1:1, ex: "En ligne", "Absent") et peut se voir attribuer une note globale via l'entité Mark (relation 1:N).

Trainer (Formateur) : Il s'agit de l'enseignant ou de l'intervenant. L'entité stocke ses informations de contact. Un formateur possède un rôle spécifique dans le système, défini par l'entité Role (relation 1:1), ce qui permet de gérer ses permissions d'accès.

Team (Équipe) : C'est une entité pivot du modèle collaboratif. Plutôt que de gérer des inscriptions individuelles aux cours, le système gère des équipes. La relation appartient est de type Many-to-Many (M:N) : un apprenant peut appartenir à plusieurs équipes (ex: "Groupe Projet Java" et "Groupe Sport"), et une équipe est constituée de plusieurs apprenants.

2. Le Cœur du Système : Activités et Sessions
Le modèle fait une distinction nette entre le sujet enseigné (l'abstraction) et le cours réel (l'instance).

Activity (Activité) : Elle représente le catalogue de formation (ex: "Cours de Base de Données"). Elle contient le syllabus et définit les règles générales. Elle est liée à :

Skills (Compétences) : Via la relation enseigne (1:1), chaque activité est dédiée à l'enseignement d'une compétence technique précise (définie par un nom, un niveau et une couleur pour l'affichage).

Period (Période) : Via la relation pendant (0:N), une activité est disponible sur certaines périodes de l'année scolaire, indépendamment des sessions planifiées.

Session : C'est la concrétisation d'une activité à un moment donné. C'est l'objet central de la planification. Pour qu'une session existe, elle doit satisfaire quatre contraintes simultanées (relations 1:1) :

Être dirigée par un Trainer.

Concerner une Activity.

Avoir lieu durant une Period (date de début/fin).

Se dérouler dans une Room (salle physique identifiée par un numéro et un bâtiment).

3. La Logique de Participation et de Feedback
Le schéma intègre des mécanismes de suivi de l'expérience utilisateur :

Participation par Équipe : La relation participe relie Team à Session (et non l'étudiant directement). C'est une cardinalité 1:N (côté Team vers Session) dans votre schéma actuel*, signifiant qu'une équipe participe à des sessions, et qu'une session accueille des équipes.

Acquisition de Compétences : L'entité Learner est reliée à Skills par la relation apprend (M:N). Cela permet de suivre la progression pédagogique d'un étudiant indépendamment des activités : un étudiant peut acquérir plusieurs compétences, et une compétence peut être maîtrisée par plusieurs étudiants.

Système de Commentaires : L'entité Comment agit comme une table de liaison enrichie entre Learner et Activity. Un étudiant peut poster plusieurs commentaires (0:N) sur une activité spécifique, permettant de laisser un avis ou de poser des questions sur le contenu du cours.

4. Analyse des Règles de Gestion (Cardinalités Critiques)
Les cardinalités présentes sur le schéma imposent des règles strictes au développement de l'application :

Contrainte d'unicité des lieux et formateurs :

Une Session est liée à 1:1 Room. Une salle ne peut pas être divisée pour deux sessions simultanées via cette relation simple.

Une Session est dirigée par 1:1 Trainer. Le co-enseignement n'est pas modélisé directement sur une seule session (il faudrait plusieurs formateurs pour une session, or ici c'est 1 seul).

Relation Activité - Compétence :

La relation enseigne en 1:1 indique une spécialisation forte : une activité enseigne une et une seule compétence principale. Si un cours enseigne Java et SQL, il faudrait soit créer deux activités, soit une compétence générique "Backend".







description (dev)
Dans ce schéma entite association nous avons les entités les plus reconaissables qui sont presente dans le sujet du tp: \
_nous avons learner qui represente un etudiant\
_team qui represente les groupes d'activites compose d'etudiant\
_trainer qui represente le professeur d'une activite\
_session qui représente un cours pour une activité\
_room qui represente l'endroit ou une activite a lieu\
_period represente la duree d'une activite\
_activity qui represente les activites proposées

Puis nous avons rajouté des entités grace a l'api:\
_skills represente les competences d'un etudiant\
_comments sert a commenter et noter l'activite en tant qu'etudiant\
_role est relie au role du professeur lors d'une activite\
_state sert a informer les autres etudiants ou professeurs de votre presence sur le site \
_mark represente les notes qu'un etudiant peut avoir lors d'activités
