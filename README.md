# Data Crew
le projet du groupe Data Crew pour les TP du projet info306 à l'USMB

v2
Description du Schéma Entité-Association et Règles de Gestion
Ce schéma modélise le système d'information d'une plateforme de gestion pédagogique. Il structure les interactions entre les apprenants, les formateurs et l'organisation logistique des activités.

1. Description des Entités
Activity (Activité) : Représente le catalogue des matières proposées (ex: "Java Avancé"). Elle définit le contenu général (syllabus), le niveau et le coût.

Session : Représente l'instance concrète d'une activité planifiée dans le temps. C'est le cœur de l'organisation logistique.

Trainer (Formateur) : L'enseignant responsable de la session.

Learner (Apprenant) : L'étudiant qui utilise la plateforme.

Team (Équipe) : Le groupe d'apprenants constitué pour travailler ensemble.

Room (Salle) & Period (Période) : Définissent respectivement le lieu physique et le créneau temporel de la session.

Entités d'enrichissement : Le schéma inclut également la gestion des compétences (Skills), des évaluations (Mark), des commentaires (Comment), des statuts de connexion (State) et des rôles utilisateurs (Role).

2. Analyse des Relations et Cardinalités (Règles de Gestion)
Les relations entre ces entités imposent les règles de fonctionnement suivantes :

A. Organisation des Sessions (Relation Trainer / Activity / Room)

Un Formateur unique par session (1:1) : La relation dirige indique qu'une Session est supervisée par exactement un Trainer (1:1). En revanche, un Trainer peut diriger plusieurs sessions différentes (1:N).

Une Activité, plusieurs Sessions (1:N) : La relation organise montre qu'une Activity (le sujet) peut donner lieu à plusieurs Sessions (les cours réels), mais qu'une session ne concerne qu'un seul sujet d'activité à la fois.

Une Salle unique (0:1) : La relation est dans stipule qu'une Session se déroule impérativement dans une seule Room. La cardinalité 0:1 côté salle suggère qu'une salle peut accueillir une session à un instant T, ou être libre (0 session).

B. Gestion des Apprenants et Équipes

Appartenance aux équipes (M:N) : La relation appartient présente des cardinalités 1:N des deux côtés (ce qui équivaut à une relation Many-to-Many). Cela signifie qu'un Learner peut appartenir à plusieurs Teams, et qu'une Team est composée de plusieurs apprenants.

Participation aux sessions (M:N) : De la même manière, la relation participe indique qu'une Team peut participer à plusieurs Sessions, et qu'une Session peut accueillir plusieurs équipes simultanément (travail de groupe ou compétition).

C. Compétences et Commentaires

Hiérarchie des commentaires : L'entité Comment possède une relation réflexive (une boucle sur elle-même) parent/child. Cela permet de structurer les discussions : un commentaire peut avoir un commentaire "parent", permettant ainsi de créer des fils de discussion (réponses).

Compétences multiples : La relation s'inscrit (M:N) entre Skills et Learner (via le Use Case implicite) suggère qu'un étudiant possède plusieurs compétences et qu'une compétence peut être partagée par plusieurs étudiants.

v1 description 

Dans ce schéma entite association nous avons les entités les plus reconaissables qui sont presente dans le sujet du tp: \
_nous avons learner qui represente un etudiant\
_team qui represente les groupes d'activites compose d'etudiant\
_trainer qui represente le professeur d'une activite\
_session jsp ?\
_room qui represente l'endroit ou une activite a lieu\
_period represente la duree d'une activite\
_activity qui represente les activites proposées

Puis nous avons rajouté des entités grace a l'api:\
_skils represente les competences d'un etudiant\
_comments sert a commenter l'activite en tant qu'etudiant\
_role est relie au role du professeur lors d'une activite\
_state sert a informer les autres etudiants ou professeurs de votre presence sur le site \
_mark represente les notes qu'un etudiant peut avoir lors d'activités , et aussi les notes de l'activité par un etudiant
