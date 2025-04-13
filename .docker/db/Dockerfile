FROM postgres:17-alpine

RUN apk add --no-cache postgresql-contrib

ENV POSTGRES_DB=${POSTGRES_DB:-laravue}
ENV POSTGRES_USER=${POSTGRES_USER:-postgres}
ENV POSTGRES_PASSWORD=${POSTGRES_PASSWORD:-postgres}
ENV PGDATA=/var/lib/postgresql/data/pgdata

COPY .docker/db/postgresql.conf /etc/postgresql.conf

HEALTHCHECK --interval=10s --timeout=5s --start-period=10s --retries=3 \
    CMD pg_isready -U ${POSTGRES_USER} -d ${POSTGRES_DB} || exit 1

EXPOSE 5432
