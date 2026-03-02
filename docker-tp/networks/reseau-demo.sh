#!/bin/bash
# ============================================================
# RÉSEAU DOCKER — Section 11 & 15 du TP
# ============================================================
#
# RÉSEAUX DOCKER DISPONIBLES PAR DÉFAUT :
#
#   bridge  : réseau par défaut. Les conteneurs peuvent communiquer
#              entre eux via leur IP. Isolés de l'hôte.
#
#   host    : le conteneur partage le réseau de l'hôte directement.
#              Pas d'isolation réseau. Port du conteneur = port hôte.
#
#   none    : aucune interface réseau. Isolement total.
#
# COMMENT LES CONTENEURS COMMUNIQUENT :
#   - Sur le même réseau bridge personnalisé → par NOM de conteneur
#   - Sur le réseau bridge par défaut        → seulement par IP
#   - Via les ports exposés (-p)             → depuis l'hôte ou l'extérieur
#
# ============================================================

echo "=== RÉSEAU DOCKER ==="

# Lister les réseaux disponibles
echo "Réseaux existants :"
docker network ls
# Résultat attendu :
# NETWORK ID     NAME      DRIVER    SCOPE
# xxx            bridge    bridge    local
# xxx            host      host      local
# xxx            none      null      local

# ============================================================
# PARTIE 1 — Serveur web avec mapping de port
# ============================================================

echo ""
echo "=== Serveur web hello-app (Section 11) ==="

# Lancer sans mapping de port
echo "Sans mapping (inaccessible depuis l'hôte) :"
docker run -d --name hello-no-port \
  us-docker.pkg.dev/google-samples/containers/gke/hello-app:1.0

CONTAINER_IP=$(docker inspect --format '{{ .NetworkSettings.IPAddress }}' hello-no-port)
echo "IP interne du conteneur : $CONTAINER_IP"
# Résultat attendu : 172.17.0.X

# Test depuis l'intérieur du réseau Docker (via exec)
docker exec hello-no-port wget -qO- localhost:8080 | head -5
# Résultat attendu : "Hello, world!" version 1.0

docker stop hello-no-port && docker rm hello-no-port

# Lancer AVEC mapping de port
echo ""
echo "Avec mapping de port 7000:8080 :"
docker run -d --name hello-port \
  -p 7000:8080 \
  us-docker.pkg.dev/google-samples/containers/gke/hello-app:1.0

echo "Test sur localhost:7000 :"
curl -s localhost:7000 || echo "curl non disponible - ouvrir http://localhost:7000 dans le navigateur"
# Résultat attendu :
# Hello, world!
# Version: 1.0.0
# Hostname: <container-id>

docker stop hello-port && docker rm hello-port

# ============================================================
# PARTIE 2 — Réseau bridge personnalisé (Section 15)
# ============================================================

echo ""
echo "=== Architecture multi-conteneurs (Section 15) ==="
echo "Voir compose/docker-compose.yml pour la version Docker Compose"
echo ""

# Créer un réseau bridge personnalisé
docker network create tp-network
echo "Réseau tp-network créé."

# Inspecter le réseau
docker network inspect tp-network
# Résultat attendu : sous-réseau 172.x.x.x/16, driver=bridge

# Lancer NGINX avec le réseau personnalisé
docker run -d \
  --name nginx-server \
  --network tp-network \
  -v "$(pwd)/../web-server/index.html:/usr/share/nginx/html/index.html:ro" \
  nginx:latest

# Lancer un client curl sur le même réseau
echo ""
echo "Test depuis un conteneur client curl :"
docker run --rm \
  --network tp-network \
  radial/busyboxplus:curl \
  curl -s http://nginx-server/
# Résultat attendu : contenu de index.html ("Vive Grenoble !!!")

echo ""
echo "Les conteneurs sur le même réseau communiquent par NOM."
echo "nginx-server est résolu automatiquement par Docker DNS."

# Nettoyage
docker stop nginx-server && docker rm nginx-server
docker network rm tp-network
echo "Nettoyage réseau terminé."
