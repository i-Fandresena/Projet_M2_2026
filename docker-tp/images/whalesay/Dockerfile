# ============================================================
# Dockerfile — Image simple (whalesay de base)
# Section 5 du TP
# ============================================================
#
# FROM    : image de base à utiliser
# CMD     : commande exécutée au démarrage du conteneur
#           (peut être écrasée par docker run IMAGE <ma-commande>)
#
# Image simple — équivalent whalesay
# Utilise python:3.11-slim + cowsay pip (pas de dépendance apt-universe)
FROM python:3.11-slim

# Installation de cowsay via pip
RUN pip install --no-cache-dir cowsay

# Commande par défaut
CMD ["python", "-c", "import cowsay; cowsay.cow('Meeuuuuh — M2 DevOPS 2026')"]
