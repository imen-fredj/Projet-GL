Objectif
Cette refonte architecturale applique le principe de responsabilité unique (SRP) en réorganisant la logique métier dans une structure de services spécialisés.

Solution mise en œuvre :
J'ai créé une couche service dédiée contenant :
CongeService : Centralise exclusivement la logique de gestion des congés
NotificationService : Gère l'ensemble des fonctionnalités de notification
