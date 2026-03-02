#!/bin/bash
# ============================================================
# VOLUMES ET BIND MOUNTS — Section 12 & 13 du TP
# ============================================================
#
# DIFFÉRENCE FONDAMENTALE :
#
# BIND MOUNT :
#   - Lie un DOSSIER/FICHIER de l'hôte à un chemin dans le conteneur
#   - Le contenu vient directement de l'hôte (modifiable en temps réel)
#   - Chemin absolu obligatoire côté hôte
#   - Usage : développement, partage de configs, injection de fichiers
#   - Syntaxe : --mount type=bind,source=/chemin/hôte,target=/chemin/conteneur
#
# VOLUME DOCKER :
#   - Géré par Docker (stocké dans /var/lib/docker/volumes/ sur Linux)
#   - Persistant entre les re-créations de conteneur
#   - Partageable entre plusieurs conteneurs
#   - Indépendant de la structure de l'hôte
#   - Usage : données de bases de données, persistance applicative
#   - Syntaxe : --mount source=nom-volume,target=/chemin/conteneur
#
# ============================================================

# ============================================================
# PARTIE 1 — Bind Mount
# ============================================================

echo "=== BIND MOUNT ==="

# Créer le dossier sur l'hôte
mkdir -p /tmp/test_docker

# Lancer un conteneur qui écrit dans /workdir → monté sur /tmp/test_docker
# L'image debug-workdir est construite depuis images/debugimage/
docker build -t debug-workdir - <<'EOF'
FROM ubuntu
WORKDIR /workdir
CMD echo "hello depuis le conteneur" > /workdir/hello.txt && echo "Fichier créé."
EOF

docker run --mount type=bind,source=/tmp/test_docker,target=/workdir debug-workdir

echo "Contenu sur l'hôte après exécution :"
cat /tmp/test_docker/hello.txt
# Résultat attendu : "hello depuis le conteneur"

echo ""
echo "Inspecter le bind mount du conteneur :"
CONTAINER_ID=$(docker container ls -a --filter "ancestor=debug-workdir" -q | head -1)
docker container inspect $CONTAINER_ID | grep -A 15 '"Mounts"'
# Résultat attendu :
# "Type": "bind",
# "Source": "/tmp/test_docker",
# "Destination": "/workdir"

# ============================================================
# PARTIE 2 — Volumes Docker
# ============================================================

echo ""
echo "=== VOLUMES DOCKER ==="

# Lister les volumes existants
echo "Volumes existants :"
docker volume ls
# Résultat attendu : liste des volumes (peut être vide)

# Créer un volume nommé
docker volume create monvolume
echo "Volume créé."

# Utiliser le volume avec un conteneur
docker run --mount source=monvolume,target=/workdir ubuntu \
  bash -c "echo 'données persistantes' > /workdir/data.txt && echo 'Données écrites.'"

# Vérifier que les données persistent dans un NOUVEAU conteneur
echo "Vérification persistance (nouveau conteneur) :"
docker run --mount source=monvolume,target=/workdir ubuntu \
  cat /workdir/data.txt
# Résultat attendu : "données persistantes"

# Inspecter le volume
echo ""
echo "Inspection du volume :"
docker volume inspect monvolume
# Résultat attendu :
# "Name": "monvolume",
# "Mountpoint": "/var/lib/docker/volumes/monvolume/_data",

# Utilisation du volume avec testvol (comme dans le TP)
docker run --mount source=testvol,target=/workdir debug-workdir
docker volume inspect testvol

echo ""
echo "Nettoyage..."
docker volume rm monvolume testvol 2>/dev/null || true
