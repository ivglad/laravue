FROM postgres:17-alpine

RUN apk add --no-cache postgresql-contrib

ENV POSTGRES_DB=${POSTGRES_DB}
ENV POSTGRES_USER=${POSTGRES_USER}
ENV POSTGRES_PASSWORD=${POSTGRES_PASSWORD}
ENV PGDATA=/var/lib/postgresql/data/pgdata

COPY .docker/db/postgresql.conf /etc/postgresql.conf

EXPOSE 5432
