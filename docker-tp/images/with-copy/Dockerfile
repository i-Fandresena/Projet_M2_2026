# ============================================================
# Dockerfile — Image avec COPY (Section 7 du TP)
# Démontre l'instruction COPY et le cache Docker
# ============================================================
#
# COPY    : copie un fichier depuis l'hôte vers l'image
#           COPY <source-hôte>  <destination-dans-image>
#
# COMPORTEMENT DU CACHE :
#   - Si hello.txt ne change pas → Docker réutilise le cache pour COPY
#   - Si hello.txt change        → Docker invalide le cache à partir de COPY
#     et re-exécute toutes les instructions suivantes
#
FROM python:3.11-slim

# Copier hello.txt depuis l'hôte vers /default_msg.txt dans l'image
COPY hello.txt /default_msg.txt

RUN pip install --no-cache-dir cowsay

# Lire le fichier copié et l'afficher via cowsay
CMD ["python", "-c", "import cowsay; msg=open('/default_msg.txt').read().strip(); cowsay.cow(msg)"]
