# **Application du Principe SRP (Single Responsibility Principle**)

# Refactorisation selon le principe SRP

## 📌 Objectif

Cette refonte applique le **principe de responsabilité unique (SRP)** en réorganisant l'architecture selon ces bonnes pratiques :

- ✅ Migration de la logique métier hors des contrôleurs
- ✅ Création d'une couche service spécialisée :
- ✅ Séparation claire des responsabilités

## � Architecture des Services

### `CongeService`

**Responsabilité unique** :  
Gère exclusivement toute la logique métier relative aux congés

### `NotificationService`

**Responsabilité unique** :  
Centralise l'ensemble du système de notifications :
