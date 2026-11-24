#!/bin/bash
MON=$(date +%Y-%m)
DUMP_FOLDER="${1%/}"
DUMP_NAME="00H"

containers=$(docker ps --format '{{.Names}}' | grep -E 'postgres|postgis|db')

#
# Cópia mensal do dump das bases no container
#
for container in $containers;
do
    filename="${DUMP_NAME}.sql.gz"
    dumpname="${container%-1*}"
    dumpfolder="${DUMP_FOLDER}/${dumpname}"
    if [ -f "$dumpfolder/$filename" ]; then
        cp "$dumpfolder/$filename" "$dumpfolder/$MON.sql.gz"
    fi
done
