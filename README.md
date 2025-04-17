# Implémentation du principe OCP (Open/Closed Principle)

Ce projet démontre l'application du principe **Open/Closed** (OCP) de SOLID pour la gestion des types (congés, documents, modifications) dans une application Symfony.

## 📂 Structure du code

Le code implémentant le pattern OCP se trouve dans :  
`/src/ocp/`

## 🛠 Ce qui a été implémenté

- **AbstractTypeController**  
  Classe parente abstraite contenant la logique CRUD générique :

**Pattern** :  
Abstract parent controller (`AbstractTypeController`) + child controllers

**Key Features**:
- ✅ CRUD logic centralized
- ✅ Easy to add new types
- ✅ Standardized templates
- ✅ SOLID compliant
