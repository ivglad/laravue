# Image
FROM nginx:1.25-alpine

# Копируем конфигурацию
COPY default.conf /etc/nginx/conf.d/default.conf

# Проверка конфигурации nginx
# RUN nginx -t

# Оптимизация логирования
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

# Добавление проверки работоспособности
HEALTHCHECK --interval=10s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:80 || exit 1

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
