# ============================================================
# Dockerfile — Serveur NGINX personnalisé (Section 14 du TP)
# ============================================================
#
# Objectif :
#   - Utiliser l'image officielle nginx
#   - Remplacer la page par défaut par index.html personnalisé
#   - Exposer le port 80
#
FROM nginx:latest

# Copier notre page HTML dans le répertoire racine de NGINX
COPY index.html /usr/share/nginx/html/index.html

# Déclarer que le conteneur écoute sur le port 80
# (information documentaire pour docker run -p)
EXPOSE 80

# nginx est démarré automatiquement par l'image de base.
# CMD est déjà défini par l'image nginx : ["nginx", "-g", "daemon off;"]
