#!/bin/bash
DAY=$(date +%d)
DUMP_FOLDER="${1%/}"
DUMP_NAME="00H"

containers=$(docker ps --format '{{.Names}}' | grep -E 'postgres|postgis|db')

#
# Cópia diária do dump das bases no container
#
for container in $containers;
do
    filename="${DUMP_NAME}.sql.gz"
    dumpname="${container%-1*}"
    dumpfolder="${DUMP_FOLDER}/${dumpname}"
    if [ -f "$dumpfolder/$filename" ]; then
        cp "$dumpfolder/$filename" "$dumpfolder/$DAY.sql.gz"
    fi
done
