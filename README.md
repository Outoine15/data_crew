# Data Crew
le projet du groupe Data Crew pour les TP du projet info306 à l'USMB

diagrame (dev)
![image](Diagramme%20tp1%20info306(dev).drawio.svg)

diagrame (final)
![image](Diagramme%20tp1%20info306(rendu).drawio.svg)

Learner(<u>id</u>,firstName,lastName,email,password,\$stateId,\$teamId,\$skillId);\
Skill(<u>id</u>,name,level,color,icon,\$learnerId);\
Mark(<u>id</u>,mark,\$learnerId);\
State(<u>id</u>,title,color,icon);\
Team(<u>id</u>,name,coins,\$acivityId);\
Session(<u>i</u>,date,maxTeams,name,sylabus,\$trainerId,\$activityId,\$periodId,\$batiment,\$numSalle);\
Trainer(<u>id</u>,firstName,lastname,email);\
Role(<u>name</u>,\$<u>trainerId</u>);\
Activity(<u>id</u>,nom,sylabus,maxTeams,coinsCost,\$skillId,\$periodId,$commentId,$teamId);\
Period(<u>name</u>,dateStart,dateEnd,color);\
Room(<u>numero</u>,<u>batiment</u>,num_ordi,date);\
Comment(<u>id</u>,date,message,mark,\$learnerId);

description

Dans ce schéma entite association nous avons les entités les plus reconaissables qui sont presente dans le sujet du tp: \
_nous avons learner qui represente un etudiant\
_team qui represente les groupes d'activites compose d'etudiant\
_trainer qui represente le professeur d'une activite\
_session qui représente un cours pour une activité\
_room qui represente l'endroit ou une activite a lieu\
_period represente la duree d'une activite\
_activity qui represente les activites proposées

Puis nous avons rajouté des entités grace a l'api:\
_skill represente les competences d'un etudiant (le skill du learner est une copie du skill enseigné par l'activity avec une autre id)\
_comments sert a commenter et noter l'activite en tant qu'etudiant\
_role est relie au role du professeur lors d'une activite\
_state sert a informer les autres etudiants ou professeurs de votre presence sur le site \
_mark represente les notes qu'un etudiant peut avoir lors d'activités
