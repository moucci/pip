<?php

namespace app\class ;

class Validator
{
    /**
     * Valider le nom ou le prénom
     * @param string $name Le nom du champ
     * @param string $value La valeur à valider
     * @return bool|string True si la valeur est valide, sinon le message d'erreur
     */
    public function checkName(string $name, string $value): bool|string
    {
        // Vérifier la variable vide
        if (empty($value)) {
            return "Le champ $name est requis.";
        }
        // Vérifier la longueur
        if (strlen($value) < 2 || strlen($value) > 50) {
            return "La longueur du $name est incorrecte. Le $name doit comporter entre 2 et 50 caractères.";
        }
        // Vérifier les caractères spéciaux
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/', $value)) {
            return "Le $name contient des caractères spéciaux non autorisés.";
        }
        return true;
    }

    /**
     * Valider l'adresse email
     * @param string $value La valeur à valider
     * @return bool|string True si la valeur est valide, sinon le message d'erreur
     */
    public function checkEmail(string $value): bool|string
    {
        // Vérifier la variable vide
        if (empty($value)) {
            return "Le champ adresse email est requis.";
        }
        // Vérifier la validité de l'adresse email
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "L'adresse email est invalide.";
        }
        return true;
    }

    /**
     * Valider le mot de passe
     * @param string $value La valeur à valider
     * @return bool|string True si la valeur est valide, sinon le message d'erreur
     */
    public function checkPass(string $value): bool|string
    {
        // Vérifier la variable vide
        if (empty($value)) {
            return "Le champ mot de passe est requis.";
        }
        // Vérifier la longueur
        if (strlen($value) < 16) {
            return "La longueur du mot de passe est incorrecte. Le mot de passe doit comporter au moins 16 caractères.";
        }
        // Vérifier les caractères requis
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$&_\-^%])[A-Za-z\d@$&_\-^%]{16,}$/', $value)) {
            return "Le mot de passe est invalide. Il doit contenir au moins 16 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
        }
        return true;
    }
}