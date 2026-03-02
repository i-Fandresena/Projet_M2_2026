# ============================================================
# Dockerfile — ENTRYPOINT (Section 8 du TP)
# Démontre la différence entre CMD et ENTRYPOINT
# ============================================================
#
# CMD        : commande PAR DÉFAUT — peut être ÉCRASÉE par
#              `docker run IMAGE <autre-commande>`
#
# ENTRYPOINT : commande PRINCIPALE — ne peut PAS être facilement
#              écrasée. Les arguments passés à `docker run` sont
#              ajoutés À LA SUITE de l'ENTRYPOINT.
#
# EXEMPLE :
#   Si ENTRYPOINT ["/bin/echo"] et CMD ["Hello"]
#   → docker run IMAGE          → affiche "Hello"
#   → docker run IMAGE "Monde"  → affiche "Monde"  (remplace CMD)
#
# RÉPONSE à la question du TP (section 8) :
#   Avec CMD ["/usr/local/bin/youtube-dl"], si on fait :
#       docker run IMAGE https://youtube.com/...
#   Docker remplace CMD par l'URL → youtube-dl n'est jamais appelé !
#   Il faut utiliser ENTRYPOINT pour que l'URL soit un ARGUMENT.
#
FROM ubuntu:18.04

WORKDIR /mydir

# Installation des dépendances
RUN apt-get update && apt-get install -y curl python3

# Téléchargement de youtube-dl
RUN curl -L https://yt-dl.org/downloads/latest/youtube-dl \
    -o /usr/local/bin/youtube-dl

RUN chmod a+x /usr/local/bin/youtube-dl

ENV LC_ALL=C.UTF-8

# CORRECTION : utiliser ENTRYPOINT au lieu de CMD
# Ainsi : docker run IMAGE https://youtube.com/watch?v=XXX
# → appelle /usr/local/bin/youtube-dl https://youtube.com/watch?v=XXX
ENTRYPOINT ["/usr/local/bin/youtube-dl"]
