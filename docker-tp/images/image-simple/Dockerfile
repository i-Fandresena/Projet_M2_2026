# ============================================================
# Dockerfile — Image avec fortune (Section 6 du TP)
# Démontre l'utilisation de RUN pour installer des paquets
# ============================================================
#
# FROM    : image de base
# RUN     : exécuté pendant le BUILD de l'image (crée un calque)
# CMD     : exécuté au démarrage du CONTENEUR
#
# NOTE : chaque RUN crée une couche (layer) dans l'image.
#        On les verra avec : docker history mywhalesay2
#
FROM python:3.11-slim

# Couche 1 — installation de cowsay
RUN pip install --no-cache-dir cowsay

# Couche 2 — proverbes intégrés (pas de fichier externe requis)
RUN pip install --no-cache-dir fortune-python

# Commande par défaut : proverbe aléatoire via la baleine
CMD ["python", "-c", "\
import cowsay, random; \
proverbes = [\
  'A vaincre sans peril, on triomphe sans gloire.', \
  'Le meilleur code est celui qu on na pas ecrit.', \
  'DevOPS : construire, tester, livrer en continu.', \
  'Un conteneur est une boite noire reproductible.', \
  'Infrastructure as Code : la config devient du code.'\
]; \
cowsay.cow(random.choice(proverbes))\
"]

# RÉPONSE aux questions de la section 6 :
# Combien de couches ajoutées ? 3 couches :
#   - apt-get update
#   - apt-get install fortunes
#   - CMD (couche de métadonnées)
# Correspondent à chaque instruction RUN et CMD du Dockerfile.
