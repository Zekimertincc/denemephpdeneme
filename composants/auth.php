<?php
require_once 'Connexion.php';

function requireActive()
{
    if (!isset($_SESSION['login'])) {
        header('Location: index.php?module=connexion&action=connexion');
        exit;
    }

    $statut = $_SESSION['statut'] ?? 'ACTIF';
    if ($statut !== 'ACTIF') {
        $_SESSION['flash_error'] = "Votre compte est en attente de validation.";
        header('Location: index.php');
        exit;
    }
}

function requireRole(array $roles)
{
    $role = $_SESSION['role'] ?? null;
    if (!$role || !in_array($role, $roles, true)) {
        $_SESSION['flash_error'] = "Accès refusé.";
        header('Location: index.php');
        exit;
    }
}

function getUserAssociations($userId)
{
    $bdd = Connexion::getBddPublic();
    if (!$bdd) {
        return [];
    }

    $query = $bdd->prepare("
        SELECT a.idAsso, a.nom
        FROM association a
        INNER JOIN detient d ON d.idAsso = a.idAsso
        WHERE d.IDCLIENT = ?
        ORDER BY a.nom ASC
    ");
    $query->execute([$userId]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function resolveAssociationSelection($userId)
{
    $associations = getUserAssociations($userId);
    $count = count($associations);

    if ($count === 1) {
        $_SESSION['idAsso'] = $associations[0]['idAsso'];
        return [
            'status' => 'assigned',
            'association' => $associations[0]
        ];
    }

    if ($count === 0) {
        return ['status' => 'none'];
    }

    return [
        'status' => 'choose',
        'associations' => $associations
    ];
}

function ensureAssociationSelected($userId)
{
    if (!isset($_SESSION['idAsso']) || empty($_SESSION['idAsso'])) {
        $selection = resolveAssociationSelection($userId);

        if ($selection['status'] === 'assigned') {
            return;
        }

        if ($selection['status'] === 'choose') {
            header('Location: index.php?module=connexion&action=choix_asso');
            exit;
        }

        $_SESSION['flash_error'] = "Aucune association liée à votre compte.";
        header('Location: index.php');
        exit;
    }
}

function getAssociationName($idAsso)
{
    $bdd = Connexion::getBddPublic();
    if (!$bdd) {
        return null;
    }

    $query = $bdd->prepare("SELECT nom FROM association WHERE idAsso = ?");
    $query->execute([$idAsso]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['nom'] ?? null;
}
