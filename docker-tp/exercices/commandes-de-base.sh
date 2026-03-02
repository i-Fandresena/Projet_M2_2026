#!/bin/bash
# ============================================================
# EXERCICES DE BASE DOCKER — Commandes essentielles
# TP DevOPS — Section 2 & 3
# ============================================================

# ------------------------------------------------------------
# SECTION 1 — Vérification de l'installation
# ------------------------------------------------------------
echo "=== Vérification installation Docker ==="
docker version
docker info

# ------------------------------------------------------------
# SECTION 2 — Premier conteneur
# ------------------------------------------------------------
echo ""
echo "=== Premier conteneur hello-world ==="
docker run hello-world

# Résultat attendu :
# Hello from Docker!
# This message shows that your installation appears to be working correctly.

# ------------------------------------------------------------
# SECTION 3 — Lister les conteneurs
# ------------------------------------------------------------
echo ""
echo "=== Conteneurs actifs ==="
docker container ls
# Résultat attendu : liste vide (hello-world s'arrête immédiatement)

echo ""
echo "=== Tous les conteneurs (inclus arrêtés) ==="
docker container ls -a
# Résultat attendu : le conteneur hello-world en status "Exited"

# RÉPONSE QUESTION 1 : Les deux identifiants d'un conteneur sont :
#   - CONTAINER ID  → ex: a1b2c3d4e5f6  (identifiant hexadécimal court)
#   - NAMES         → ex: quirky_morse  (nom généré automatiquement)
#
# RÉPONSE QUESTION 2 : hello-world s'arrête immédiatement car il n'a pas
#   de processus qui tourne en continu. Il exécute son script, affiche le
#   message et termine avec exit code 0.

# ------------------------------------------------------------
# SECTION 4 — Lister les images
# ------------------------------------------------------------
echo ""
echo "=== Images disponibles ==="
docker image ls
# Résultat attendu : hello-world:latest listé

# ------------------------------------------------------------
# SECTION 5 — Supprimer conteneur et image
# ------------------------------------------------------------
# Récupérer l'ID du conteneur hello-world arrêté :
CONTAINER_ID=$(docker container ls -a --filter "ancestor=hello-world" -q | head -1)

if [ -n "$CONTAINER_ID" ]; then
  echo "Suppression du conteneur : $CONTAINER_ID"
  docker container rm $CONTAINER_ID
fi

echo "Suppression de l'image hello-world"
docker image rm hello-world:latest

# Résultat attendu :
# Untagged: hello-world:latest
# Deleted: sha256:...

# ------------------------------------------------------------
# SECTION 6 — Conteneur looper (Section 3 du TP)
# ------------------------------------------------------------
echo ""
echo "=== Création du conteneur looper ==="
docker run -d --name looper ubuntu:16.04 sh -c 'while true; do date; sleep 1; done'
# Résultat attendu : ID du conteneur affiché sur une ligne

echo "Vérification que looper tourne..."
docker ps
# Résultat attendu : looper listé avec status "Up X seconds"

echo ""
echo "=== Logs du looper (5 dernières lignes) ==="
docker logs --tail 5 looper
# Résultat attendu : 5 lignes avec date/heure (ex: Mon Mar  2 10:00:01 UTC 2026)

echo ""
echo "=== Pause du conteneur ==="
docker pause looper
docker ps
# Résultat attendu : looper avec status "Up X minutes (Paused)"

echo ""
echo "=== Reprise du conteneur ==="
docker unpause looper
docker ps
# Résultat attendu : looper avec status "Up X minutes"

echo ""
echo "=== Créer un fichier dans le conteneur ==="
docker exec -d looper touch fichier.txt
docker exec looper ls -la
# Résultat attendu : fichier.txt listé dans le répertoire /

echo ""
echo "=== Arrêt et suppression du conteneur ==="
docker stop looper
docker rm looper
docker container prune -f
echo "Nettoyage terminé."
