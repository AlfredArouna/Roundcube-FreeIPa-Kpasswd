# Roundcube-FreeIPA-Kpasswd
Custom Password update driver for FreeIPa based on kpasswd

This driver for Roundcube password plugin allow to reset user password against FreeIPA. The old kpasswd plugin required interactive script which need to back and forth request from mail server to FreeIPA server.

With this plugin, one request is send: from roundcube server to FreeIPA one, which reply back.



## Setup

 1. Get CA certificate from FreeIPA server:

	```bash
 	# The certificate can be obtained in https://$host/ipa/config/ca.crt
 	wget --no-check-certificate https://ipa.demo1.freeipa.org/ipa/config/ca.crt -O certs/ipa.demo1.freeipa.org_ca.crt
	```

 2. Copy `freeipa` folder in `$ROUNDCUBE_ROOT/plugins/password/drivers/`
 3. Backup `kpasswd.php` and copy `kpasswd.php` in `$ROUNDCUBE_ROOT/plugins/password/drivers/`
 4. Update Roundcube configuration in `$ROUNDCUBE_ROOT/config/config.inc.php`:

	```bash
 	$config['password_freeipa_host'] = 'ipa.demo1.freeipa.org' : 
 	$config['password_freeipa_cert'] = '$ROUNDCUBE_ROOT/plugins/password/drivers/freeipa/certs/ipa.demo1.freeipa.org_ca.crt'; 
	$config['password_freeipa_admin'] = 'admin';
	$config['password_freeipa_admin_passwd'] = 'password';
	```

## Folder structure
At the end, you will have structure like:

	tree $ROUNDCUBE_ROOT/plugins/password/drivers/freeipa/
	/var/www/html/roundcubemail/plugins/password/drivers/freeipa/
	├── bootstrap.php
	├── certs
	│   └── ipa0.demo1.freeipa.org_ca.crt
	└── src
	    ├── APIAccess
	    │   ├── Base.php
	    │   ├── Connection.php
	    │   ├── Group.php
	    │   ├── Main.php
	    │   └── User.php
	    └── autoload.php

# Credits
Php-FreeIPA from [gnumoksha](https://github.com/gnumoksha/php-freeipa)
