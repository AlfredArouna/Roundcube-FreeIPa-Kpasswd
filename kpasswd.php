<?php

/**
 * kpasswd Driver
 *
 * Driver that adds functionality to change the systems user password via
 * the 'kpasswd' command.
 *
 * For installation instructions please read the README file.
 *
 * @version 1.0
 * @author Alfred Arouna <alfred.arouna@gmail.com>
 *
 * Based on chpasswd roundcubemail password driver by
 * @author Alex Cartwright <acartwright@mutinydesign.co.uk>
 */

class rcube_kpasswd_password
{
    public function save($currpass, $newpass)
    {
        $host      		= rcmail::get_instance()->config->get('password_freeipa_host');
        $cert     		= rcmail::get_instance()->config->get('password_freeipa_cert');
	$admin      		= rcmail::get_instance()->config->get('password_freeipa_admin');
	$admin_passwd      	= rcmail::get_instance()->config->get('password_freeipa_admin_passwd');

	require_once(__DIR__ . "/freeipa/bootstrap.php");
	try {
    		$ipa = new \FreeIPA\APIAccess\Main($host, $cert);
	} catch (Exception $e) {
		rcube::raise_error(array(
                	'code' => 600,
                	'type' => 'php',
                	'file' => __FILE__, 'line' => __LINE__,
                	'message' => "Password plugin connexion: Message: {$e->getMessage()} , Code: {$e->getCode()}"
                	), true, false);
	}

	// Make authentication
	try {
    		$ret_aut = $ipa->connection()->authenticate($admin, $admin_passwd);
	} catch (Exception $e) {
    		rcube::raise_error(array(
                        'code' => 600,
                        'type' => 'php',
                        'file' => __FILE__, 'line' => __LINE__,
                        'message' => "Password plugin login: Message: {$e->getMessage()} , Code: {$e->getCode()}"
                        ), true, false);
	}	

	// Reset password
	$user = $_SESSION['username'];
	$username = explode('@',$user);
	$data_change_pass = array(
    		'userpassword' => $newpass,
	);

	try {
    		$change_pass = $ipa->user()->modify($username[0], $data_change_pass);
    		if ($change_pass) {
        		return PASSWORD_SUCCESS;
    		} else {
			rcube::raise_error(array(
                		'code' => 600,
                		'type' => 'php',
                		'file' => __FILE__, 'line' => __LINE__,
                		'message' => "Password plugin: Unable to change the password"
                	), true, false);
   	 	}
	} catch (\Exception $e) {
    		rcube::raise_error(array(
                        'code' => 600,
                        'type' => 'php',
                        'file' => __FILE__, 'line' => __LINE__,
                        'message' => "Password plugin change: Message: {$e->getMessage()} , Code: {$e->getCode()}"
                        ), true, false);
	}

        return PASSWORD_ERROR;
    }
}
