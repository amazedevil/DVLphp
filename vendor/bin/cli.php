#!/usr/bin/env sh
SRC_DIR="`pwd`"
cd "`dirname "$0"`"
cd "../hafriedlander/php-peg"
BIN_TARGET="`pwd`/cli.php"
cd "$SRC_DIR"
"$BIN_TARGET" "$@"
