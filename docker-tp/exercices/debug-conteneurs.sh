#!/bin/bash
# ============================================================
# DEBUG DES CONTENEURS — Section 9 & 11 du TP
# ============================================================
#
# OUTILS DE DIAGNOSTIC :
#   docker logs      : affiche la sortie standard du conteneur
#   docker exec      : exécute une commande dans un conteneur actif
#   docker inspect   : retourne toutes les métadonnées JSON du conteneur
#   docker diff      : montre les fichiers modifiés depuis la création
#   docker stats     : monitoring CPU/RAM en temps réel
#
# ============================================================

echo "=== DEBUG — Reproduction du problème Section 9 ==="

# Construire l'image buguée du TP
docker build -t testimage - <<'EOF'
FROM ubuntu
CMD echo "hello" > /workdir/hello.txt
EOF

# Lancer → ça va échouer car /workdir n'existe pas
echo "Lancement du conteneur (va échouer) :"
docker run --name debug-test testimage
echo "Exit code : $?"

# Voir les logs pour comprendre l'erreur
echo ""
echo "=== Logs du conteneur ==="
docker logs debug-test
# Résultat attendu :
# /bin/sh: 1: cannot create /workdir/hello.txt: nonexistent

# Inspecter les différences dans le système de fichiers
echo ""
echo "=== Modifications dans le conteneur (docker diff) ==="
docker diff debug-test
# Résultat attendu : rien ou erreur → /workdir n'a pas pu être créé

# ============================================================
# DÉBOGAGE INTERACTIF
# ============================================================
echo ""
echo "=== Débogage interactif ==="

# Lancer un nouveau conteneur en mode interactif
FIXED_CONTAINER=$(docker run -d --name debug-fix testimage sleep 60)

# Corriger à l'intérieur
docker exec debug-fix bash -c "mkdir /workdir && echo 'hello' > /workdir/hello.txt"
echo "Correction appliquée."

# Vérifier
docker exec debug-fix cat /workdir/hello.txt
# Résultat attendu : hello

# Observer les modifications
echo ""
echo "=== docker diff (après correction) ==="
docker diff debug-fix
# Résultat attendu :
# A /workdir           (A = Added)
# A /workdir/hello.txt

# ============================================================
# CRÉER UNE NOUVELLE IMAGE depuis le conteneur corrigé
# ============================================================
echo ""
echo "=== Créer une image corrigée avec docker commit ==="
docker commit \
  --change='CMD bash -c "mkdir -p /workdir && echo hello > /workdir/hello.txt"' \
  debug-fix \
  newimage

echo "Test de la nouvelle image :"
docker run --rm newimage cat /workdir/hello.txt
# Résultat attendu : hello

# ============================================================
# INSPECTER UN CONTENEUR
# ============================================================
echo ""
echo "=== docker inspect (extraits) ==="
docker inspect debug-fix | python3 -c "
import json, sys
data = json.load(sys.stdin)[0]
print('Nom       :', data['Name'])
print('Status    :', data['State']['Status'])
print('IP        :', data['NetworkSettings']['IPAddress'])
print('Image     :', data['Config']['Image'])
print('CMD       :', data['Config']['Cmd'])
"
# Résultat attendu :
# Nom       : /debug-fix
# Status    : running
# IP        : 172.17.0.X
# Image     : testimage
# CMD       : ['sleep', '60']

# Nettoyage
docker stop debug-fix debug-test 2>/dev/null || true
docker rm debug-fix debug-test 2>/dev/null || true
docker rmi testimage newimage 2>/dev/null || true
echo "Nettoyage terminé."
