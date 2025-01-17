<?php
if(!defined('PMA_DISPLAY_HEADING'))
	define('PMA_DISPLAY_HEADING', 0);

require_once('./libraries/common.lib.php');

if(isset($db))
	unset($db);
if(isset($table))
	unset($table);

$show_query = '1';
require_once('./libraries/header.inc.php');

// Any message to display?
if(!empty($message)) {
	PMA_showMessage($message);
	unset($message);
}

$common_url_query = PMA_generate_common_url('', '');

// this div is required for containing divs can be 50%
echo '<div id="maincontainer">';
	echo '<div class="notice"><strong>Important:</strong> You should have a backup of your database before modifying any data.</div>';
	echo '<div class="warning"><strong>Note:</strong> This plugin should only be used for development purposes or by experienced users. If more users have access to the administration section, you should consider using the plugin <em>only when necessary</em>.</div>';

	if($server > 0) {
		require_once('./libraries/check_user_privileges.lib.php');
		$is_superuser = PMA_isSuperuser();

		if($cfg['Server']['auth_type'] == 'config')
			$cfg['ShowChgPassword'] = false;
	}
	?>
	<div id="mysqlmaininformation">
		<?php
		if($server > 0) {
			$server_info = '';
			if(!empty($cfg['Server']['verbose'])) {
				$server_info .= htmlspecialchars($cfg['Server']['verbose']);
				if($GLOBALS['cfg']['ShowServerInfo'])
					$server_info .= ' (';
			}
			if($GLOBALS['cfg']['ShowServerInfo'] || empty($cfg['Server']['verbose']))
				$server_info .= PMA_DBI_get_host_info();
			if(!empty($cfg['Server']['verbose']) && $GLOBALS['cfg']['ShowServerInfo'])
				$server_info .= ')';
			$mysql_cur_user_and_host = PMA_DBI_fetch_value('SELECT USER();');

			// should we add the port info here?
			$short_server_info = (!empty($GLOBALS['cfg']['Server']['verbose']) ? $GLOBALS['cfg']['Server']['verbose'] : $GLOBALS['cfg']['Server']['host']);
			echo '<h1 dir="ltr">' . $short_server_info .'</h1>' . "\n";
			unset($short_server_info);
		} else {
			// Case when no server selected
			echo '<h1 dir="ltr">MySQL</h1>' . "\n";
		}

		if($server > 0) {
			// Include WordPress functionality
			$wp_root = '../../../..';
			if(file_exists($wp_root.'/wp-load.php'))
				require_once($wp_root.'/wp-load.php');


			echo '<ul>' . "\n";
				if($GLOBALS['cfg']['ShowServerInfo']) {
					PMA_printListItem('Security key: ' . get_option('pma_key'), 'li_server_info');
					PMA_printListItem($strServerVersion . ': ' . PMA_MYSQL_STR_VERSION, 'li_server_info');
					PMA_printListItem($strProtocolVersion . ': ' . PMA_DBI_get_proto_info(), 'li_mysql_proto');
					PMA_printListItem($strServer . ': ' . $server_info, 'li_server_info');
					PMA_printListItem($strUser . ': ' . htmlspecialchars($mysql_cur_user_and_host), 'li_user_info');
				} else {
					PMA_printListItem($strServerVersion . ': ' . PMA_MYSQL_STR_VERSION, 'li_server_info');
					PMA_printListItem($strServer . ': ' . $server_info, 'li_server_info');
				}
				if($cfg['AllowAnywhereRecoding'] && $allow_recoding && PMA_MYSQL_INT_VERSION < 40100) {
					echo '<li id="li_select_mysql_charset">';
					?>
					<form method="post" action="index.php" target="_parent">
						<input type="hidden" name="server" value="<?php echo $server; ?>" />
						<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
						<?php echo $strMySQLCharset; ?>:
						<select name="convcharset"  xml:lang="en" dir="ltr" onchange="this.form.submit();">
							<?php
							foreach($cfg['AvailableCharsets'] as $tmpcharset) {
								if($convcharset == $tmpcharset)
									$selected = ' selected="selected"';
								else
									$selected = '';
								echo '            ' . '<option value="' . $tmpcharset . '"' . $selected . '>' . $tmpcharset . '</option>' . "\n";
							}
							?>
						</select>
						<noscript><input type="submit" value="<?php echo $strGo; ?>" /></noscript>
					</form>
					</li>
				<?php
				} elseif (PMA_MYSQL_INT_VERSION >= 40100) {
        echo '    <li id="li_select_mysql_charset">';
        echo '        ' . $strMySQLCharset . ': '
           . '        <strong xml:lang="en" dir="ltr">'
           . '           ' . $mysql_charsets_descriptions[$mysql_charset_map[strtolower($charset)]] . "\n"
           . '           (' . $mysql_charset_map[strtolower($charset)] . ')' . "\n"
           . '        </strong>' . "\n"
           . '    </li>' . "\n"
           . '    <li id="li_select_mysql_collation">';
        echo '        <form method="post" action="index.php" target="_parent">' . "\n"
           . PMA_generate_common_hidden_inputs(null, null, 4, 'collation_connection')
           . '            <label for="select_collation_connection">' . "\n"
           . '                ' . $strMySQLConnectionCollation . ': ' . "\n"
           . '            </label>' . "\n"
           . PMA_generateCharsetDropdownBox(PMA_CSDROPDOWN_COLLATION, 'collation_connection', 'select_collation_connection', $collation_connection, true, 4, true)
           . '            <noscript><input type="submit" value="' . $strGo . '" /></noscript>' . "\n"
           // put the doc link in the form so that it appears on the same line
           . PMA_showMySQLDocu('MySQL_Database_Administration', 'Charset-connection') . "\n"
           . '        </form>' . "\n"
           . '    </li>' . "\n";
    }

    if ($cfg['ShowCreateDb']) {
        echo '<li id="li_create_database">';
        require './libraries/display_create_database.lib.php';
        echo '</li>' . "\n";
    }

    PMA_printListItem($strMySQLShowStatus, 'li_mysql_status',
        './server_status.php?' . $common_url_query);
    PMA_printListItem($strMySQLShowVars, 'li_mysql_variables',
        './server_variables.php?' . $common_url_query, 'show-variables');
    PMA_printListItem($strProcesses, 'li_mysql_processes',
        './server_processlist.php?' . $common_url_query, 'show-processlist');

    if (PMA_MYSQL_INT_VERSION >= 40100) {
        PMA_printListItem($strCharsetsAndCollations, 'li_mysql_collations',
            './server_collations.php?' . $common_url_query);
    }

    PMA_printListItem($strStorageEngines, 'li_mysql_engines',
        './server_engines.php?' . $common_url_query);

    if ($is_reload_priv) {
        PMA_printListItem($strReloadPrivileges, 'li_flush_privileges',
            './server_privileges.php?flush_privileges=1&amp;' . $common_url_query, 'flush');
    }

    if ($is_superuser) {
        PMA_printListItem($strPrivileges, 'li_mysql_privilegs',
            './server_privileges.php?' . $common_url_query);
    }

    $binlogs = PMA_DBI_try_query('SHOW MASTER LOGS', null, PMA_DBI_QUERY_STORE);
    if ($binlogs) {
        if (PMA_DBI_num_rows($binlogs) > 0) {
            PMA_printListItem($strBinaryLog, 'li_mysql_binlogs',
                './server_binlog.php?' . $common_url_query);
        }
        PMA_DBI_free_result($binlogs);
    }
    unset($binlogs);

    PMA_printListItem($strDatabases, 'li_mysql_databases',
        './server_databases.php?' . $common_url_query);
    PMA_printListItem($strExport, 'li_export',
        './server_export.php?' . $common_url_query);
    PMA_printListItem($strImport, 'li_import',
        './server_import.php?' . $common_url_query);

    /**
     * Change password
     *
     * @todo ? needs another message
     */
    if ($cfg['ShowChgPassword']) {
        PMA_printListItem($strChangePassword, 'li_change_password',
            './user_password.php?' . $common_url_query);
    } // end if

    // Logout for advanced authentication
    if ($cfg['Server']['auth_type'] != 'config') {
        $http_logout = ($cfg['Server']['auth_type'] == 'http')
                     ? '<a href="./Documentation.html#login_bug" target="documentation">'
                        . ($cfg['ReplaceHelpImg'] ? '<img class="icon" src="' . $pmaThemeImage . 'b_info.png" width="11" height="11" alt="Info" />' : '(*)') . '</a>'
                     : '';
        PMA_printListItem('<strong>' . $strLogout . '</strong> ' . $http_logout,
            'li_log_out',
            './index.php?' . $common_url_query . '&amp;old_usr=' . urlencode($PHP_AUTH_USER), null, '_parent');
    } // end if

    echo '</ul>';
} // end of if ($server > 0)
?>
</div>
	<div id="pmamaininformation">
		<?php
		echo '<h1 dir="ltr">phpMyAdmin - ' . PMA_VERSION . '</h1>';
		echo '<ul>';

		// Display the MySQL servers choice form
		if(!$cfg['LeftDisplayServers'] && (count($cfg['Servers']) > 1 || $server == 0 && count($cfg['Servers']) == 1)) {
			echo '<li>';
				require_once('./libraries/select_server.lib.php');
				PMA_select_server(true, true);
			echo '</li>';
		}

		if($server > 0) {
			PMA_printListItem($strMysqlClientVersion . ': ' . PMA_DBI_get_client_info(), 'li_mysql_client_version');
			PMA_printListItem($strUsedPhpExtensions . ': ' . $GLOBALS['cfg']['Server']['extension'], 'li_used_php_extension');
		}

		// Display language selection combo
		if(empty($cfg['Lang'])) {
			echo '<li>';
				require_once('./libraries/display_select_lang.lib.php');
				PMA_select_language();
			echo '</li>';
		}

		if(isset($cfg['AllowAnywhereRecoding']) && $cfg['AllowAnywhereRecoding'] && $server != 0 && $allow_recoding && PMA_MYSQL_INT_VERSION < 40100) {
			echo '<li>';
				?>
				<form method="post" action="index.php" target="_parent">
					<input type="hidden" name="server" value="<?php echo $server; ?>">
					<input type="hidden" name="lang" value="<?php echo $lang; ?>">
					<?php echo $strMySQLCharset;?>:
					<select name="convcharset" dir="ltr" onchange="this.form.submit();">
						<?php
						foreach($cfg['AvailableCharsets'] as $id => $tmpcharset) {
							if($convcharset == $tmpcharset)
								$selected = ' selected';
							else
								$selected = '';

							echo '<option value="' . $tmpcharset . '"' . $selected . '>' . $tmpcharset . '</option>';
						}
						?>
					</select>
					<noscript><input type="submit" value="<?php echo $strGo; ?>"></noscript>
				</form>
			</li>
			<?php
		}

		echo '<li>';
			echo PMA_Config::getFontsizeForm();
		echo '</li>';

		if($cfg['ShowPhpInfo'])
			PMA_printListItem($strShowPHPInfo, 'li_phpinfo', './phpinfo.php?' . $common_url_query);

		echo '<li>';
			echo '<a href="http://getbutterfly.com/wordpress-plugins/portable-phpmyadmin/" rel="external" target"_blank">Portable phpMyAdmin</a> | ';
			echo '<a href="http://www.phpmyadmin.net/" rel="external" target"_blank">phpMyAdmin</a>';
		echo '</li>';
 		?>
    </ul>
</div>
<br class="clearfloat" />
</div>

<?php
if(!empty($GLOBALS['PMA_errors']) && is_array($GLOBALS['PMA_errors'])) {
	foreach ($GLOBALS['PMA_errors'] as $error) {
		echo '<div class="error">' . $error . '</div>';
	}
}
if($server != 0 && $cfg['Server']['user'] == 'root' && $cfg['Server']['password'] == '') {
	echo '<div class="warning">' . $strInsecureMySQL . '</div>';
}

/**
 * Warning for PHP 4.2.3
 * modified: 2004-05-05 mkkeck
 */
if(PMA_PHP_INT_VERSION == 40203 && @extension_loaded('mbstring')) {
	echo '<div class="warning">' . $strPHP40203 . '</div>';
}

/**
 * Nijel: As we try to hadle charsets by ourself, mbstring overloads just
 * break it, see bug 1063821.
 */
if(@extension_loaded('mbstring') && @ini_get('mbstring.func_overload') > 1) {
	echo '<div class="warning">' . $strMbOverloadWarning . '</div>' . "\n";
}

/**
 * Nijel: mbstring is used for handling multibyte inside parser, so it is good
 * to tell user something might be broken without it, see bug #1063149.
 */
if ($GLOBALS['using_mb_charset'] && !@extension_loaded('mbstring')) {
    echo '<div class="warning">' . $strMbExtensionMissing . '</div>' . "\n";
}

/**
 * Warning for old PHP version
 * modified: 2004-05-05 mkkeck
 */

if (PMA_PHP_INT_VERSION < 40100) {
    echo '<div class="warning">' . sprintf($strUpgrade, 'PHP', '4.1.0') . '</div>' . "\n";
}

/**
 * Warning for old MySQL version
 * modified: 2004-05-05 mkkeck
 */
// not yet defined before the server choice
if (defined('PMA_MYSQL_INT_VERSION') && PMA_MYSQL_INT_VERSION < 32332) {
    echo '<div class="warning">' . sprintf($strUpgrade, 'MySQL', '3.23.32') . '</div>' . "\n";
}

if (defined('PMA_WARN_FOR_MCRYPT')) {
    echo '<div class="warning">' . PMA_sanitize(sprintf($strCantLoad, 'mcrypt')) . '</div>' . "\n";
}

/**
 * prints list item for main page
 *
 * @param   string  $name   displayed text
 * @param   string  $id     id, used for css styles
 * @param   string  $url    make item as link with $url as target
 * @param   string  $mysql_help_page  display a link to MySQL's manual
 * @param   string  $target special target for $url
 */
function PMA_printListItem($name, $id = null, $url = null, $mysql_help_page = null, $target = null)
{
    echo '<li id="' . $id . '">';
    if (null !== $url) {
        echo '<a href="' . $url . '"';
        if (null !== $target) {
           echo ' target="' . $target . '"';
        }
        echo '>';
    }

    echo $name;

    if (null !== $url) {
        echo '</a>' . "\n";
    }
    if (null !== $mysql_help_page) {
        echo PMA_showMySQLDocu('', $mysql_help_page);
    }
    echo '</li>';
}

/**
 * Displays the footer
 */
require_once './libraries/footer.inc.php';
?>
