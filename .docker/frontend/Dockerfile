FROM node:22-alpine

RUN apk add --no-cache --virtual .build-deps tini

RUN mkdir -p /var/www/frontend && \
    chown -R node:node /var/www/frontend

USER node

WORKDIR /var/www/frontend

COPY --chown=node:node frontend/package*.json ./

RUN npm i && npm cache clean --force

CMD ["npm", "run", "dev"]