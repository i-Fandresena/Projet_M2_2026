# TP1 DevOPS — Docker & Docker Compose
### M2 2026 

> Branche : `TP1_DevOPS`  
> Auteur : i-Fandresena  

---

## Contenu du TP

| Section | Thème |
|---------|-------|
| 1 | Installation et vérification |
| 2 | Premiers conteneurs (`hello-world`) |
| 3 | Manipulation des conteneurs (`looper`) |
| 4–7 | Construction d images Docker (`Dockerfile`) |
| 8 | `CMD` vs `ENTRYPOINT` |
| 9 | Débogage (`docker diff`, `docker commit`) |
| 10 | Build multi-stage |
| 11 | Réseau Docker |
| 12–13 | Bind mounts et Volumes |
| 14 | Serveur NGINX personnalisé |
| 15 | Application multi-conteneurs |
| 16–17 | Docker Compose et Scaling |

---

## Structure du projet

```
docker-tp/
├── README.md
├── SOLUTION-COMPLETE.sh
├── exercices/
│   ├── commandes-de-base.sh
│   ├── debug-conteneurs.sh
│   └── Dockerfile.debug
├── images/
│   ├── whalesay/Dockerfile
│   ├── image-simple/Dockerfile
│   ├── with-copy/Dockerfile + hello.txt
│   ├── entrypoint/Dockerfile + Dockerfile.demo
│   └── multi-stage/Dockerfile + hello.c
├── web-server/Dockerfile + index.html
├── volumes/volumes-demo.sh
├── networks/reseau-demo.sh
└── compose/docker-compose.yml
```

---

## Prérequis

### Obligatoires

- **Docker** (version 24+)
- **Docker Compose** (intégré à Docker Desktop ou plugin Docker)
- **Git**
- Connexion internet (téléchargement des images Docker)
- Minimum **4 Go de RAM** disponibles
- Minimum **5 Go d espace disque** libre

---

## Installation selon votre système d exploitation

### Windows

#### Docker Desktop (recommandée)

1. Télécharger : https://www.docker.com/products/docker-desktop/
2. Lancer l installateur `.exe` et suivre les instructions
3. Redémarrer si demandé
4. Lancer **Docker Desktop** depuis le menu Démarrer
5. Vérifier dans PowerShell :

```powershell
docker version
docker compose version
```

> Docker Desktop sur Windows utilise WSL2 (Windows Subsystem for Linux 2).  
> Si WSL2 n est pas présent, Docker Desktop l installera automatiquement.

**Activer WSL2 manuellement si nécessaire :**
```powershell
wsl --install
```

#### Vérification

```powershell
docker run hello-world
```

---

### Linux (Ubuntu / Debian)

```bash
# Installation via le script officiel Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Autoriser l utilisateur à utiliser Docker sans sudo
sudo usermod -aG docker $USER
newgrp docker

# Vérifier
docker version
docker compose version
docker run hello-world
```

**Si `docker compose` n est pas disponible :**
```bash
sudo apt-get install docker-compose-plugin
```

---

### macOS

#### Docker Desktop (recommandée)

1. Télécharger : https://www.docker.com/products/docker-desktop/
   - **Intel** : choisir "Mac with Intel chip"
   - **Apple Silicon (M1/M2/M3/M4)** : choisir "Mac with Apple Silicon"
2. Ouvrir le `.dmg` et glisser Docker dans `Applications`
3. Lancer depuis `Applications` et autoriser les permissions
4. Vérifier dans le Terminal :

```bash
docker version
docker compose version
docker run hello-world
```

**Via Homebrew :**
```bash
brew install --cask docker
open /Applications/Docker.app
```

---

## Cloner et lancer le TP

```bash
git clone https://github.com/i-Fandresena/Projet_M2_2026.git
cd Projet_M2_2026
git checkout TP1_DevOPS
cd docker-tp
```

---

## Exécution section par section

> Toutes les commandes sont identiques sur Windows (PowerShell), Linux et macOS.  
> Les différences spécifiques à Windows sont signalées explicitement.

---

### Section 2 — Premiers pas

```bash
docker run hello-world
docker container ls -a
docker image ls

# Nettoyer
docker container prune -f
docker image rm hello-world:latest
```

**Réponse Q1** : Les deux identifiants d un conteneur sont le `CONTAINER ID` (ex: `aed3d3d1f21e`) et le `NAMES` (ex: `busy_chatterjee`).

**Réponse Q2** : `hello-world` s arrête immédiatement car son processus se termine après avoir affiché le message. Il n y a pas de processus continu, donc le conteneur s arrête dès que le processus principal quitte.

---

### Section 3 — Conteneur looper

```bash
docker run -d --name looper ubuntu:20.04 sh -c 'while true; do date; sleep 1; done'
docker ps
docker logs --tail 5 looper

# Mettre en pause
docker pause looper
docker ps    # STATUS: "Up X minutes (Paused)"

# Reprendre
docker unpause looper

# Créer un fichier dans le conteneur
docker exec -d looper touch fichier.txt
docker exec looper ls -la

# Terminal interactif
docker exec -it looper bash
# (taper 'exit' pour quitter)

# Nettoyage
docker stop looper
docker rm looper
```

---

### Section 5 — Image simple (mywhalesay)

```bash
cd docker-tp/images/whalesay
docker build -t mywhalesay .
docker run mywhalesay
```

**Contenu du Dockerfile :**
```dockerfile
FROM python:3.11-slim
RUN pip install --no-cache-dir cowsay
CMD ["python", "-c", "import cowsay; cowsay.cow('Meeuuuuh — M2 DevOPS 2026')"]
```

> Note : l image originale `docker/whalesay` est obsolète (manifest v1).  
> On utilise `python:3.11-slim` + `pip install cowsay` comme alternative.

---

### Section 6 — Image avec fortune

```bash
cd docker-tp/images/image-simple
docker build -t mywhalesay2 .
docker run mywhalesay2
docker history mywhalesay2
```

**Réponse** : `docker history` montre 3 couches ajoutées : `pip cowsay`, `pip fortune-python`, et `CMD`.  
Chaque instruction `RUN` et `CMD` crée une couche distincte.

---

### Section 7 — Cache et COPY

```bash
cd docker-tp/images/with-copy
docker build -t mywhalesay-msg .
docker run mywhalesay-msg

# Démonstration de l invalidation du cache
echo "Ligne ajoutee" >> hello.txt
docker build -t mywhalesay-msg .
# Les couches avant le COPY restent CACHED, les suivantes sont reconstruites
```

---

### Section 8 — CMD vs ENTRYPOINT

```bash
cd docker-tp/images/entrypoint
docker build -f Dockerfile.demo -t demo-entrypoint .

# CMD : l argument REMPLACE la commande entière
docker run demo-entrypoint

# ENTRYPOINT : l argument est AJOUTÉ après la commande
docker run demo-entrypoint "https://youtube.com/watch?v=example"
```

**Réponse** : Avec `CMD`, l argument passé à `docker run IMAGE <arg>` remplace entièrement `CMD`.  
Avec `ENTRYPOINT`, l argument est transmis comme paramètre du programme.

---

### Section 9 — Débogage

```bash
cd docker-tp/exercices
docker build -f Dockerfile.debug -t testimage .
docker run --name debug-test testimage
docker logs debug-test
# Erreur attendue : cannot create /workdir/hello.txt: Directory nonexistent

docker run -d --name debug-fix testimage sleep 30
docker exec debug-fix bash -c "mkdir /workdir && echo hello > /workdir/hello.txt"
docker diff debug-fix
# A /workdir
# A /workdir/hello.txt

docker commit debug-fix newimage
docker run --rm newimage bash -c "cat /workdir/hello.txt"

# Nettoyage
docker rm -f debug-test debug-fix
docker rmi testimage newimage
```

---

### Section 10 — Build multi-stage

```bash
cd docker-tp/images/multi-stage
docker build -t hello-multistage .
docker run --rm hello-multistage
# Résultat : Hello, world! — Compilé avec Docker multi-stage build

# Comparer les tailles
docker images | grep -E "hello-multistage|gcc"
```

**Résultat** : `gcc:latest` (compilateur) ~1.5 GB vs image finale ~109 MB. Facteur 14x de réduction.

---

### Section 11 — Réseau Docker

```bash
docker network ls
docker network inspect bridge

docker run -d --name hello-app -p 7000:8080 \
  us-docker.pkg.dev/google-samples/containers/gke/hello-app:1.0

docker inspect --format '{{ .NetworkSettings.IPAddress }}' hello-app
# Résultat : 172.17.0.X (sous-réseau bridge 172.17.0.0/16)

docker stop hello-app && docker rm hello-app
```

---

### Section 12 — Bind Mount

**Linux / macOS :**
```bash
mkdir -p /tmp/test_docker
docker run --rm \
  --mount type=bind,source=/tmp/test_docker,target=/workdir \
  ubuntu:20.04 \
  bash -c "echo 'hello depuis Docker' > /workdir/hello.txt"
cat /tmp/test_docker/hello.txt
```

**Windows (PowerShell) :**
```powershell
New-Item -ItemType Directory -Force "$env:TEMP\test_docker"
docker run --rm `
  --mount "type=bind,source=$env:TEMP\test_docker,target=/workdir" `
  ubuntu:20.04 `
  bash -c "echo 'hello depuis Docker' > /workdir/hello.txt"
Get-Content "$env:TEMP\test_docker\hello.txt"
```

---

### Section 13 — Volumes Docker

```bash
docker volume ls

docker run --rm --mount source=testvol,target=/workdir ubuntu:20.04 \
  bash -c "echo 'donnees persistantes' > /workdir/data.txt"

# Vérifier la persistance dans un nouveau conteneur
docker run --rm --mount source=testvol,target=/workdir ubuntu:20.04 \
  cat /workdir/data.txt
# Résultat : donnees persistantes

docker volume inspect testvol
docker volume rm testvol
```

---

### Section 14 — Serveur NGINX personnalisé

```bash
cd docker-tp/web-server
docker build -t mon-nginx .
docker run -d --name nginx-web -p 7000:80 mon-nginx

# Linux / macOS
curl http://localhost:7000

# Windows (PowerShell)
Invoke-WebRequest -Uri "http://localhost:7000" -UseBasicParsing | Select-Object -ExpandProperty Content

# Ou ouvrir dans le navigateur : http://localhost:7000
# Résultat attendu : "Vive Grenoble !!!"

docker stop nginx-web && docker rm nginx-web
```

---

### Section 16 — Docker Compose

```bash
cd docker-tp/compose
docker compose up -d
docker compose ps
docker compose logs -f

# Tester NGINX
curl http://localhost:7000
```

**Services déployés :**
- `web` : NGINX personnalisé sur port 7000
- `whoami` : service d identification (containous/whoami)
- `redis` : base de données en mémoire
- `supervision` : monitoring des logs
- `client` : curl automatique vers whoami

---

### Section 17 — Scaling (réplication)

```bash
cd docker-tp/compose

# Lancer 3 instances de whoami
docker compose up -d --scale whoami=3
docker compose ps | grep whoami

# Vérifier le round-robin DNS depuis le client
docker exec compose-client-1 sh -c "curl -s http://whoami/"
docker exec compose-client-1 sh -c "curl -s http://whoami/"
docker exec compose-client-1 sh -c "curl -s http://whoami/"
# Les 3 appels retournent des hostnames différents => round-robin confirmé

# Arrêter tout
docker compose down -v
```

---

## Nettoyage global

```bash
docker container prune -f
docker image prune -a -f
docker volume prune -f
docker network prune -f

# Tout nettoyer d un coup (ATTENTION : irréversible)
docker system prune -a --volumes -f
```

---

## Problèmes connus et solutions

| Problème | Cause | Solution |
|----------|-------|----------|
| `docker/whalesay` inaccessible | Manifest Docker v1 obsolète | Utiliser `python:3.11-slim` + `pip install cowsay` |
| `radial/busyboxplus:curl` inaccessible | Manifest v1 obsolète | Utiliser `curlimages/curl:latest` |
| `jwilder/whoami` inaccessible | Manifest v1 obsolète | Utiliser `containous/whoami` |
| `ubuntu:16.04` TLS timeout | Image très ancienne | Utiliser `ubuntu:20.04` ou `ubuntu:22.04` |
| `apt-get` échoue dans les conteneurs | DNS instable sous Docker Desktop Windows | Utiliser des images avec dépendances pré-installées (`python:3.11-slim`, `gcc:latest`) |
| `curl` non disponible dans PowerShell | Alias de `Invoke-WebRequest` | Utiliser `Invoke-WebRequest -Uri ... -UseBasicParsing` |
| Warning `version` obsolète dans compose | Champ `version:` déprécié | Supprimer la ligne `version:` du docker-compose.yml |

---

## Commandes Docker — Référence rapide

```bash
# Images
docker pull IMAGE[:TAG]
docker build -t NOM .
docker image ls
docker image rm IMAGE
docker history IMAGE

# Conteneurs
docker run IMAGE
docker run -d IMAGE                   # arrière-plan
docker run -it IMAGE bash             # terminal interactif
docker run -p HOST:CONT IMAGE         # mapping de port
docker run -v SRC:DEST IMAGE          # volume/bind mount
docker run --mount type=...,source=...,target=... IMAGE
docker ps                             # actifs
docker ps -a                          # tous
docker stop / docker rm NOM
docker logs NOM
docker exec -it NOM bash
docker inspect NOM
docker diff NOM
docker commit NOM NEWIMAGE

# Volumes
docker volume ls / create / inspect / rm

# Réseaux
docker network ls / create / inspect / rm

# Docker Compose
docker compose up -d
docker compose down [-v]
docker compose logs -f
docker compose ps
docker compose up -d --scale SERVICE=N

# Nettoyage
docker container prune -f
docker image prune -a -f
docker volume prune -f
docker system prune -a --volumes -f
```
