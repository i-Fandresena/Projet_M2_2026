# SOLUTION COMPLÈTE — TP DevOPS Docker et Docker Compose
# M2 2026 — Guide d'exécution complet
# ============================================================
#
# PRÉ-REQUIS : Docker Desktop est démarré (Windows)
# Toutes les commandes sont à exécuter dans PowerShell ou
# le Terminal intégré de VS Code.
#
# STRUCTURE DU PROJET :
# docker-tp/
# ├── exercices/
# │   ├── commandes-de-base.sh      ← Sections 2 & 3 du TP
# │   └── debug-conteneurs.sh       ← Section 9 du TP
# ├── images/
# │   ├── whalesay/                 ← Sections 5 & 6 du TP
# │   │   └── Dockerfile
# │   ├── image-simple/             ← Section 6 (with fortune)
# │   │   └── Dockerfile
# │   ├── with-copy/                ← Section 7 du TP
# │   │   ├── Dockerfile
# │   │   └── hello.txt
# │   ├── entrypoint/               ← Section 8 du TP
# │   │   └── Dockerfile
# │   └── multi-stage/              ← Section 10 du TP
# │       ├── Dockerfile
# │       └── hello.c
# ├── web-server/                   ← Section 14 du TP
# │   ├── Dockerfile
# │   └── index.html
# ├── volumes/
# │   └── volumes-demo.sh           ← Sections 12 & 13 du TP
# ├── networks/
# │   └── reseau-demo.sh            ← Sections 11 & 15 du TP
# └── compose/
#     └── docker-compose.yml        ← Sections 16 & 17 du TP

# ==============================================================
# SECTION 1 — INSTALLATION (déjà fait : Docker Desktop actif)
# ==============================================================

# Vérifier que Docker fonctionne :
docker version
docker compose version
docker run hello-world

# Résultat attendu docker version :
# Client: Docker Engine - Community
#  Version:           26.x.x
# Server: Docker Desktop
#  Engine: Version: 26.x.x

# Résultat attendu hello-world :
# Hello from Docker!
# This message shows that your installation appears to be working correctly.

# ==============================================================
# SECTION 2 — PREMIERS PAS
# ==============================================================

# Lancer hello-world
docker run hello-world

# Lister les conteneurs ACTIFS (hello-world sera absent car arrêté)
docker container ls
# Résultat : liste vide

# Lister TOUS les conteneurs
docker container ls -a
# Résultat :
# CONTAINER ID   IMAGE         COMMAND    CREATED          STATUS                     NAMES
# a1b2c3d4e5f6   hello-world   "/hello"   5 seconds ago    Exited (0) 4 seconds ago   quirky_morse

# RÉPONSES AUX QUESTIONS :
# Q1 - Les deux identifiants : CONTAINER ID (ex: a1b2c3d4e5f6) et NAMES (ex: quirky_morse)
# Q2 - hello-world s'arrête immédiatement car son seul processus (affichage message)
#      se termine avec exit code 0. Pas de processus en continu.

# Lister les images :
docker image ls
# Résultat :
# REPOSITORY    TAG       IMAGE ID       CREATED        SIZE
# hello-world   latest    d2c94e258dcb   ...            13.3kB

# Nettoyer :
docker container rm $(docker container ls -aq)
docker image rm hello-world:latest
# Résultat attendu : "Untagged: hello-world:latest" + "Deleted: sha256:..."

# ==============================================================
# SECTION 3 — MANIPULATION DES CONTENEURS (looper)
# ==============================================================

# Créer le conteneur looper
docker run -d --name looper ubuntu:16.04 sh -c 'while true; do date; sleep 1; done'
# Résultat attendu : ID du conteneur (ex: 7f3a9c2b1e4d...)

# 1. Vérifier qu'il tourne
docker ps
# Résultat : looper listé avec STATUS "Up X seconds"

# 2. Observer les logs en temps réel (Ctrl+C pour quitter)
docker logs -f looper
# Résultat :
# Mon Mar  2 10:00:01 UTC 2026
# Mon Mar  2 10:00:02 UTC 2026
# ...

# 3. Mettre en pause
docker pause looper
docker ps
# Résultat : STATUS "Up X minutes (Paused)"

# 4. Reprendre
docker unpause looper
docker ps
# Résultat : STATUS "Up X minutes"

# 5. S'attacher au conteneur (Ctrl+C pour se détacher et ARRÊTER)
#    (utiliser Ctrl+P Ctrl+Q pour se détacher SANS arrêter)
docker attach looper

# 6. Se détacher sans arrêter : Ctrl+P puis Ctrl+Q

# 7. Créer un fichier dans le conteneur
docker exec -d looper touch fichier.txt
docker exec looper ls -la
# Résultat : fichier.txt présent dans /

# 8. Ouvrir un terminal interactif dans le conteneur
docker exec -it looper bash
# Résultat : prompt bash dans le conteneur
# exit  ← pour quitter

# Arrêt et nettoyage
docker stop looper
docker rm looper
docker container prune -f
# Résultat : "Total reclaimed space: X"

# ==============================================================
# SECTION 4 — WHALESAY (base)
# ==============================================================

# Tester whalesay directement
docker run docker/whalesay cowsay boo
# Résultat attendu :
#  _____
# < boo >
#  -----
#     \
#      \
#       ## ## ##       ==
#      ## ## ## ##      ===

# Chercher des images similaires
docker search whalesay
# Résultat : liste d'images avec docker/whalesay en premier

# ==============================================================
# SECTION 5 — IMAGE SIMPLE (mywhalesay)
# ==============================================================

# Se placer dans le dossier
cd docker-tp/images/whalesay

# Construire l'image
docker build -t mywhalesay .
# Résultat :
# [+] Building X.Xs (3/3) FINISHED
# => [1/2] FROM docker.io/docker/whalesay
# => [2/2] CMD cowsay "Meeuuuuh..."

# Tester
docker run mywhalesay
# Résultat :
#  ___________________________
# < Meeuuuh — M2 DevOPS 2026 >
#  ---------------------------
#       \   ^__^
#        \  (oo)\_______

# ==============================================================
# SECTION 6 — IMAGE AVEC FORTUNE (mywhalesay2)
# ==============================================================

cd docker-tp/images/image-simple

docker build -t mywhalesay2 .
# Résultat : build avec 3 couches supplémentaires

docker run mywhalesay2
# Résultat : proverbe aléatoire dans la bouche de la baleine

# Observer les couches
docker history mywhalesay2
# Résultat :
# IMAGE          CREATED BY                                      SIZE
# sha256:xxx     /bin/sh -c #(nop)  CMD ["/bin/sh" "-c" "...   0B
# sha256:xxx     /bin/sh -c apt-get install -y fortunes         X MB
# sha256:xxx     /bin/sh -c apt-get -y update                   X MB
# sha256:xxx     FROM docker/whalesay:latest                     X MB

# RÉPONSES QUESTIONS SECTION 6 :
# Couches ajoutées : 3
#  - apt-get update
#  - apt-get install fortunes
#  - CMD (métadonnée de commande)

# ==============================================================
# SECTION 7 — IMAGE AVEC COPY (mywhalesay-msg)
# ==============================================================

cd docker-tp/images/with-copy

docker build -t mywhalesay-msg .
# Résultat : build avec COPY + 2x RUN

docker run mywhalesay-msg
# Résultat : contenu de hello.txt affiché par la baleine

# Test interactif
docker run -it mywhalesay-msg bash
# → ls  : voir default_msg.txt dans /
# → cat default_msg.txt
# → exit

# DÉMONSTRATION DU CACHE :
# 1. Modifier hello.txt (ex: ajouter une ligne)
# 2. Rebuild → COPY invalide le cache, RUN re-exécutés
# 3. Ne PAS modifier hello.txt → rebuild ultra rapide (tout depuis cache)

# Modifier hello.txt et rebuilder
echo "Ligne modifiée !" >> hello.txt
docker build -t mywhalesay-msg .
# Observer : "CACHED" sur les layers non impactés

# ==============================================================
# SECTION 8 — CMD vs ENTRYPOINT
# ==============================================================

# RÉPONSE : Avec CMD, si on passe un argument à docker run,
# il REMPLACE CMD. Donc youtube-dl ne serait jamais appelé.
# ENTRYPOINT force le programme et accepte des arguments en plus.

# La construction du Dockerfile entrypoint/ prendrait beaucoup
# de temps (téléchargement youtube-dl). On simule avec echo :

docker build -t demo-entrypoint - <<'EOF'
FROM ubuntu:22.04
ENTRYPOINT ["/bin/echo", "Commande ENTRYPOINT avec argument :"]
EOF

# Sans argument → CMD par défaut (ici inexistant)
docker run demo-entrypoint
# Résultat : "Commande ENTRYPOINT avec argument :"

# Avec argument → ajouté APRÈS l'ENTRYPOINT
docker run demo-entrypoint "https://youtube.com/watch?v=example"
# Résultat : "Commande ENTRYPOINT avec argument : https://youtube.com/watch?v=example"

# ==============================================================
# SECTION 9 — DEBUG D'UNE IMAGE
# ==============================================================

# Construire l'image buguée
docker build -t testimage - <<'EOF'
FROM ubuntu
CMD echo "hello" > /workdir/hello.txt
EOF

# Lancer → va échouer
docker run --name debug-test testimage
# Exit code non-zéro

# Voir l'erreur
docker logs debug-test
# Résultat :
# /bin/sh: 1: cannot create /workdir/hello.txt: nonexistent

# Débugger interactivement
docker run -it testimage bash
# → mkdir /workdir
# → echo "hello" > /workdir/hello.txt
# → cat /workdir/hello.txt  → "hello"
# → exit

# Voir les différences
docker diff $(docker container ls -al -q)
# Résultat :
# A /workdir
# A /workdir/hello.txt

# Commit pour sauvegarder la correction
docker commit \
  --change='CMD bash -c "mkdir -p /workdir && echo hello > /workdir/hello.txt"' \
  $(docker container ls -al -q) \
  newimage

# Tester la nouvelle image
docker run --rm newimage cat /workdir/hello.txt
# Résultat attendu : hello

docker rm debug-test
docker rmi testimage newimage

# ==============================================================
# SECTION 10 — BUILD MULTI-STAGE
# ==============================================================

cd docker-tp/images/multi-stage

# ÉTAPE 1 : Builder avec multi-stage
docker build -t hello-multistage .
# Résultat : 2 stages compilés. Stage 2 est l'image finale légère.

# Tester
docker run --rm hello-multistage
# Résultat : Hello, world! — Compilé avec Docker multi-stage build

# Comparer les tailles
docker images | grep hello
# Résultat attendu :
# hello-multistage   latest   sha256:xxx   ...   ~77 MB (ubuntu de base)
# (sans multi-stage, ce serait ~400 MB avec build-essential)

# ==============================================================
# SECTION 11 — RÉSEAU DOCKER
# ==============================================================

# Lancer sans mapping de port
docker run -d --name hello-app \
  us-docker.pkg.dev/google-samples/containers/gke/hello-app:1.0
# (si l'image n'est pas accessible, utiliser nginx:latest à la place)

# Obtenir l'IP du conteneur
docker inspect --format '{{ .NetworkSettings.IPAddress }}' hello-app
# Résultat attendu : 172.17.0.X

# Sans mapping, inaccessible depuis l'hôte :
curl localhost:8080
# Résultat : Connection refused

docker stop hello-app && docker rm hello-app

# Avec mapping de port
docker run -d --name hello-app -p 7000:8080 \
  us-docker.pkg.dev/google-samples/containers/gke/hello-app:1.0

# Accessible !
curl localhost:7000
# Résultat :
# Hello, world!
# Version: 1.0.0
# Hostname: <container-id>

docker stop hello-app && docker rm hello-app

# ==============================================================
# SECTION 12 — BIND MOUNTS
# ==============================================================

# Créer le dossier sur Windows PowerShell :
New-Item -ItemType Directory -Force "$env:TEMP\test_docker"

# Construire l'image de test :
docker build -t workdir-image - <<'EOF'
FROM ubuntu
WORKDIR /workdir
CMD bash -c "echo 'hello depuis Docker' > /workdir/hello.txt && echo 'Fichier créé.'"
EOF

# Lancer avec bind mount (chemin Windows)
docker run --mount "type=bind,source=$env:TEMP/test_docker,target=/workdir" workdir-image

# Vérifier que le fichier existe sur l'hôte Windows :
Get-Content "$env:TEMP\test_docker\hello.txt"
# Résultat attendu : "hello depuis Docker"

# Inspecter le montage
CONTAINER_ID=$(docker container ls -al -q)
docker container inspect $CONTAINER_ID
# Chercher "Mounts" dans la sortie JSON

docker rmi workdir-image

# ==============================================================
# SECTION 13 — VOLUMES DOCKER
# ==============================================================

# Lister les volumes
docker volume ls
# Résultat : liste (probablement vide)

# Créer un volume automatiquement en le référençant
docker run --mount source=testvol,target=/workdir ubuntu \
  bash -c "echo 'données persistantes' > /workdir/data.txt"

# Vérifier la persistance dans un NOUVEAU conteneur
docker run --mount source=testvol,target=/workdir ubuntu \
  cat /workdir/data.txt
# Résultat attendu : "données persistantes"

# Inspecter le volume
docker volume inspect testvol
# Résultat attendu :
# [
#   {
#     "Name": "testvol",
#     "Mountpoint": "/var/lib/docker/volumes/testvol/_data",
#     "Driver": "local"
#   }
# ]

# Nettoyage
docker volume rm testvol

# ==============================================================
# SECTION 14 — SERVEUR NGINX PERSONNALISÉ
# ==============================================================

cd docker-tp/web-server

# Option A : via Dockerfile (image construite)
docker build -t mon-nginx .
docker run -d --name nginx-web -p 7000:80 mon-nginx

# Tester
curl localhost:7000
# Résultat attendu : code HTML avec "Vive Grenoble !!!"
# Ou ouvrir http://localhost:7000 dans le navigateur

# Option B : via bind mount (sans Dockerfile)
# Sur PowerShell Windows :
$INDEX_PATH = (Resolve-Path ".\index.html").Path -replace "\\", "/" -replace "^C:", "/c"
docker run -d --name nginx-bind -p 7001:80 `
  --mount "type=bind,source=$(Resolve-Path '.\index.html'),target=/usr/share/nginx/html/index.html" `
  nginx:latest

curl localhost:7001
# Résultat attendu : "Vive Grenoble !!!"

docker stop nginx-web nginx-bind
docker rm nginx-web nginx-bind

# ==============================================================
# SECTION 15 — APPLICATION MULTI-CONTENEURS
# ==============================================================
# (Voir docker-compose.yml pour version orchestrée)

# Créer le réseau
docker network create tp-network

# 1. Lancer NGINX avec volume de logs
docker run -d \
  --name nginx-server \
  --network tp-network \
  -p 7000:80 \
  -v "$(pwd)/docker-tp/web-server/index.html:/usr/share/nginx/html/index.html:ro" \
  -v nginx-logs:/var/log/nginx \
  nginx:latest

# 2. Lancer le conteneur de supervision (lit les logs)
docker run -d \
  --name supervision \
  --network tp-network \
  -v nginx-logs:/var/log/nginx:ro \
  ubuntu:22.04 \
  bash -c "tail -f /var/log/nginx/access.log"

# 3. Lancer le client curl (génère du trafic)
docker run -d \
  --name client-curl \
  --network tp-network \
  radial/busyboxplus:curl \
  sh -c "while true; do curl -s http://nginx-server/; sleep 3; done"

# Voir les logs de supervision
docker logs -f supervision
# Résultat attendu :
# 172.x.x.x - - [02/Mar/2026:10:00:00 +0000] "GET / HTTP/1.1" 200 XXX

# Nettoyage
docker stop nginx-server supervision client-curl
docker rm nginx-server supervision client-curl
docker volume rm nginx-logs
docker network rm tp-network

# ==============================================================
# SECTION 16 — DOCKER COMPOSE (simple)
# ==============================================================

cd docker-tp/compose

# Démarrer le service whoami
docker compose up -d
# Résultat :
# [+] Running 5/5
# ✔ Network compose_tp-network    Created
# ✔ Container compose-redis-1     Started
# ✔ Container compose-web-1       Started
# ✔ Container compose-whoami-1    Started
# ✔ Container compose-supervision-1 Started
# ✔ Container compose-client-1    Started

# Tester whoami
docker compose port --index 1 whoami 8000
# Résultat : 0.0.0.0:XXXXX (port choisi par Docker)

PORT=$(docker compose port --index 1 whoami 8000 | cut -d: -f2)
curl localhost:$PORT
# Résultat :
# I'm <container-id>

# Tester le serveur web NGINX
curl localhost:7000
# Résultat : HTML avec "Vive Grenoble !!!"

# Voir les logs de tous les services
docker compose logs -f

# ==============================================================
# SECTION 17 — RÉPLICATION (scaling)
# ==============================================================

# PROBLÈME avec port fixe → conflit si scale > 1
# SOLUTION → on utilise expose: "8000" sans ports: dans whoami

# Lancer 3 instances de whoami
docker compose up -d --scale whoami=3
# Résultat :
# [+] Running 7/7
# ✔ Container compose-whoami-1   Running
# ✔ Container compose-whoami-2   Starting
# ✔ Container compose-whoami-3   Starting

# Voir les ports de chaque instance
docker compose port --index 1 whoami 8000
docker compose port --index 2 whoami 8000
docker compose port --index 3 whoami 8000
# Résultat : 3 ports différents choisis par Docker

# Interroger chaque instance
PORT1=$(docker compose port --index 1 whoami 8000 | cut -d: -f2)
PORT2=$(docker compose port --index 2 whoami 8000 | cut -d: -f2)
PORT3=$(docker compose port --index 3 whoami 8000 | cut -d: -f2)

curl localhost:$PORT1  # → I'm compose-whoami-1
curl localhost:$PORT2  # → I'm compose-whoami-2
curl localhost:$PORT3  # → I'm compose-whoami-3

# Revenir à 1 instance
docker compose up -d --scale whoami=1

# Arrêter et nettoyer TOUT
docker compose down -v
# Résultat :
# [+] Running 7/7
# ✔ Container compose-client-1      Removed
# ✔ Container compose-supervision-1 Removed
# ✔ Container compose-whoami-1      Removed
# ✔ Container compose-web-1         Removed
# ✔ Container compose-redis-1       Removed
# ✔ Volume compose_nginx-logs       Removed
# ✔ Network compose_tp-network      Removed

# ==============================================================
# NETTOYAGE GLOBAL FINAL
# ==============================================================

# Supprimer tous les conteneurs arrêtés
docker container prune -f

# Supprimer toutes les images non utilisées :
docker image prune -a -f

# Supprimer les volumes orphelins :
docker volume prune -f

# Supprimer les réseaux non utilisés :
docker network prune -f

# OU tout en une seule commande :
docker system prune -a --volumes -f
# ATTENTION : supprime TOUT (images, conteneurs, volumes, réseaux)

# ==============================================================
# RÉSUMÉ DES COMMANDES ESSENTIELLES
# ==============================================================
#
# IMAGES
# docker pull IMAGE[:TAG]          → télécharger une image
# docker build -t NOM .             → construire depuis Dockerfile
# docker image ls                   → lister les images
# docker image rm IMAGE             → supprimer une image
# docker history IMAGE              → voir les couches
#
# CONTENEURS
# docker run IMAGE                  → créer + démarrer
# docker run -d IMAGE               → en arrière-plan
# docker run -it IMAGE bash         → interactif
# docker run -p HOST:CONT IMAGE     → mapper un port
# docker run -v SRC:DEST IMAGE      → monter un volume/bind
# docker ps                         → conteneurs actifs
# docker ps -a                      → tous les conteneurs
# docker stop NOM                   → arrêter proprement
# docker rm NOM                     → supprimer
# docker logs NOM                   → voir les logs
# docker exec -it NOM bash          → terminal interactif
# docker inspect NOM                → métadonnées complètes
# docker diff NOM                   → fichiers modifiés
# docker commit NOM NEWIMAGE        → créer image depuis conteneur
#
# VOLUMES
# docker volume ls                  → lister
# docker volume create NOM          → créer
# docker volume inspect NOM         → inspecter
# docker volume rm NOM              → supprimer
#
# RÉSEAUX
# docker network ls                 → lister
# docker network create NOM         → créer
# docker network inspect NOM        → inspecter
# docker network rm NOM             → supprimer
#
# DOCKER COMPOSE
# docker compose up -d              → démarrer
# docker compose down               → arrêter + supprimer
# docker compose logs -f            → suivre les logs
# docker compose ps                 → état des services
# docker compose up -d --scale S=N  → scaler un service
# docker compose port --index N S P → port d'une instance
