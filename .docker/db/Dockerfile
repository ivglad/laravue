FROM postgres:17-alpine

RUN apk add --no-cache postgresql-contrib

ENV POSTGRES_USER=${POSTGRES_USER}
ENV POSTGRES_PASSWORD=${POSTGRES_PASSWORD}
ENV POSTGRES_DB=${POSTGRES_DB}
ENV PGDATA=/var/lib/postgresql/data/pgdata

EXPOSE 5432
