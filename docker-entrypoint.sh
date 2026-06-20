#!/bin/bash
set -e

# Disable conflicting MPMs and enable the one required for PHP/Apache
a2dismod mpm_event mpm_worker || true
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true
a2enmod mpm_prefork || true

# Execute the main command passed to the container (usually apache2-foreground)
exec "$@"
