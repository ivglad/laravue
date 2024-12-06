FROM postgres:17-alpine

RUN apk add --no-cache postgresql-contrib

RUN chown -R $(id -u):$(id -g) /var/lib/postgresql/data \
    && chmod -R u+rwX /var/lib/postgresql/data \
    && chmod -R u+rwX /docker-entrypoint-initdb.d

ENV POSTGRES_USER=${POSTGRES_USER}
ENV POSTGRES_PASSWORD=${POSTGRES_PASSWORD}
ENV POSTGRES_DB=${POSTGRES_DB}

EXPOSE 5432
