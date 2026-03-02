# ============================================================
# Dockerfile — Build MULTI-STAGE (Section 10 du TP)
# ============================================================
#
# SOLUTION : utiliser gcc:latest comme image de compilation
# (GCC déjà installé → pas besoin d'apt-get build-essential)
#
# Stage 1 (compiler) : compile le programme avec gcc:latest
# Stage 2 (final)    : copie UNIQUEMENT le binaire compilé dans ubuntu
#
# ==============================
# Stage 1 — Compilation
# ==============================
FROM gcc:latest AS compiler

# Copier le source C dans l'image de compilation
COPY hello.c /hello.c

# Compiler (génère le binaire /hello)
RUN gcc -o /hello /hello.c && chmod +x /hello

# ==============================
# Stage 2 — Image finale légère
# ==============================
FROM ubuntu:20.04

# Copier UNIQUEMENT le binaire depuis le stage "compiler"
COPY --from=compiler /hello /hello

# Commande par défaut
CMD ["/hello"]

# COMPARAISON DE TAILLE :
#   Image gcc:latest (stage de compilation) : ~1.5 GB
#   Image finale (ubuntu + binaire seul)    : ~109 MB
# → docker images pour vérifier
