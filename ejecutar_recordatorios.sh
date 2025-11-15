#!/bin/bash
# Script para enviar recordatorios de actividades
# Hacer ejecutable con: chmod +x ejecutar_recordatorios.sh

echo "============================================"
echo "  SISTEMA DE RECORDATORIOS - MI AGENDA"
echo "============================================"
echo ""

# Obtener directorio del script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR"

# Verificar si PHP está instalado
if ! command -v php &> /dev/null; then
    echo "[ERROR] PHP no está instalado"
    exit 1
fi

# Ejecutar el script
echo "Ejecutando script de recordatorios..."
echo ""
php enviar_recordatorios.php

# Guardar código de salida
EXIT_CODE=$?

echo ""
echo "============================================"
if [ $EXIT_CODE -eq 0 ]; then
    echo "✅ Script finalizado exitosamente"
else
    echo "❌ Script finalizado con errores (código: $EXIT_CODE)"
fi
echo "============================================"

exit $EXIT_CODE
