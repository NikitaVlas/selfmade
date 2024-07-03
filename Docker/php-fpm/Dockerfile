FROM erkenes/php:8.1-fpm-alpine

RUN echo "date.timezone=Europe/Berlin" > $PHP_INI_DIR/conf.d/date_timezone.ini

COPY ./container-files /

RUN chmod +x /scripts/run.sh
RUN echo "date.timezone=Europe/Berlin" > $PHP_INI_DIR/conf.d/date_timezone.ini

CMD ["/scripts/run.sh"]
