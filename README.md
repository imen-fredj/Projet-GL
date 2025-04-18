## Validation des Avances sur Salaire

### Fonctionnalité
Le système limite le nombre d'avances sur salaire à **2 par mois et par employé**. Cette restriction est implémentée via un service de validation.

### Service `AvanceSalaireValidator`

**Emplacement**: `src/Service/AvanceSalaireValidator.php`

**Fonctionnalités**:
- Vérifie qu'un employé ne dépasse pas la limite de 2 avances par mois
- Exclut automatiquement les demandes rejetées du décompte
- Prend en compte la date d'avance pour déterminer le mois concerné

**Méthode principale**:

public function canRequestAdvance(Rh $user, \DateTimeInterface $date): bool
