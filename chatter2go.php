<?
// FLOOBLE CHATTER2GO
// A Free Chat-Box PHP Script.
// Visit 
//       http://chatter.flooble.com/c2g/ 
// for more information and instructions on use.
// This script is copyright (c) 2003 by Animus Pactum Consulting Inc.
// 
// ----------------------------------------------------------------- 
// LISENCE:
// This work is licensed under the Creative Commons NoDerivs License. 
// To view a copy of this license, visit 
//       http://creativecommons.org/licenses/nd/1.0/ 
// or send a letter to 
//       Creative Commons, 559 Nathan Abbott Way, 
//       Stanford, California 94305, USA.
//
// -----------------------------------------------------------------
// If you forgot your password, change the next line to have '' after the
// equals sign to reset it.

$c2g_pass = '';
$c2g_filepath = '';

$c2g_version = '0.01';

$c2g_datafile = 'chatter2go.data';
$c2g_conffile = 'chatter2go.conf';

$c2g_conf = Array();
$c2g_conf['lines'] = 10;
$c2g_conf['defname'] = 'Anonymous';

$c2g_namecookie = 'c2gname';

$c2g_op = $_REQUEST['c2g_op'];

$c2g_js = $_REQUEST['c2g_js'];

if (!$PHP_SELF) { $PHP_SELF = $_ENV['SCRIPT_NAME']; }

c2g_loadconf();

if ($_REQUEST['c2g_login']) {
	header("P3P: CP=\"NOI DSP COR CURa ADMa DEVa TAIa OUR BUS IND UNI COM NAV INT STA PRE OTC\n\"");
	setCookie('c2g_upass', $_POST['c2g_upass']);
	$c2g_upass = $_POST['c2g_upass'];
}

if ($c2g_op == 'info') {
	echo("<html><head><title>Post Info</title></head><body bgcolor=\"#CCCCCC\">\n");
	echo("<div style=\"font-family: Arial; font-size:12px;\">");
	echo("<b>Post Info:</b><hr size=\"1\">");
	$c2g_info = $_REQUEST['c2g_info'];
	list($c2g_date, $c2g_ip) = explode('|', $c2g_info);
	echo(date("F j, Y, g:i a", $c2g_date));
	echo("<br>\n");
	list($c2g_ip1, $c2g_ip2, $c2g_ip3, $c2g_ip4) = c2g_getip($c2g_ip);
	if ($_REQUEST['c2g_full'] && time() - $_REQUEST['c2g_full'] < 1000) {
	} else {
		$c2g_ip4 = '***';
	}
	echo("IP: $c2g_ip1.$c2g_ip2.$c2g_ip3.$c2g_ip4 <br><br>\n");
	echo("(<a href=\"javascript:void(0);\" onclick=\"window.close();\">close</a>)");
	echo("</div></body></html>");
	exit();
}

if ($c2g_op == 'saveconf') {
	$c2g_conf['lines'] = $_REQUEST['c2g_l'];
	$c2g_conf['defname'] = $_REQUEST['c2g_dn'];
	$c2g_upass = $_REQUEST['c2g_upass'];
	c2g_saveconf($c2g_conf);
	$c2g_url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF . '?c2g_op=conf';
        header("Location: http://chatter.flooble.com/c2g/confframe.php?cu=" . urlencode($c2g_url));
	exit();
}

if ($c2g_op == 'conf') {
	$c2g_cop = $_REQUEST['c2g_cop'];
	c2g_startpage();

	if (!$c2g_upass) { $c2g_upass = $_REQUEST['c2g_upass']; }
	if (!c2g_checkpass($c2g_upass)) {
		c2g_showlogin();
		c2g_endpage();
		exit();
	}

	if ($c2g_cop == 'del') {
		$c2g_delnum = $_REQUEST['c2g_delnum'];
		c2g_delentry($c2g_delnum);
	}

	if (!c2g_testconf() || $c2g_cop == 'path') {
		c2g_showpathdialog();
	} else if ($c2g_cop == 'code') {
		c2g_showcodedialog();
	} else {
		c2g_showconfdialog();
	}
	c2g_endpage();
	$c2g_dontshow = true;
} else if ($c2g_op == 'savepath') {
	$c2g_newp = $_REQUEST['c2g_p'];
	$c2g_p1 = $_REQUEST['c2g_p1'];
	$c2g_p2 = $_REQUEST['c2g_p2'];
	if (!$c2g_upass) { $c2g_upass = $_REQUEST['c2g_upass']; }
	if ($c2g_p1 != $c2g_p2) {
		$op = "&c2g_p1=$c2g_p1&c2g_p2=$c2g_p2";
	} else {
		c2g_setfilepath($c2g_newp, crypt($c2g_p1, 'chatter2go'), $c2g_p1);
	}
	$c2g_url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF . '?c2g_op=conf&c2g_cop=path' . $op;
	header("Location: http://chatter.flooble.com/c2g/confframe.php?cu=" . urlencode($c2g_url)); 
	exit();
} else if ($c2g_op == 'post') {
   	c2g_post();
	$c2g_url = $_REQUEST['c2g_url'];
	if ($c2g_url) {
		header("Location: $c2g_url");
		exit();
	}
}

if (!$c2g_dontshow) {
	c2g_writeHeader();
	c2g_show_content();
	c2g_show_form();
}

function c2g_printfunction($full = '') {
	global $PHP_SELF;
	if ($full == 1) {
		$full = '&c2g_full=' . time();
	}
	$url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF;
	$popup = "
	function c2g_showInfo(info) {
		window.open('$url?c2g_op=info$full&c2g_info=' + info, '', 'width=200, height=120');
	}
	";
	c2g_write_js($popup);
}

function c2g_show_content() {
        global $PHP_SELF, $c2g_datafile, $c2g_filepath, $c2g_configloaded;
	if (! $c2g_configloaded) {
		$c2g_url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF . '?c2g_op=conf';
		c2g_write("<b>chatter2go</b> must first be configured. <br>"
			. "<a href=\"http://chatter.flooble.com/c2g/confframe.php?cu=" . urlencode($c2g_url) 
			. "\">Configure now</a>");
		return;
	}
	c2g_printfunction();
        $fp = @fopen($c2g_filepath . $c2g_datafile, 'r');
        if (!$fp) {
                  c2g_write('Post messages here.');
                  return;
        }
        $line = 0;
        while ($str = fgets($fp, 1024)) {
              $msg[$line] = preg_replace("/\n/", '', $str);
              $line++;
              if ($line == 3) {
                 $line = 0;
                 c2g_write("[<a href=\"javascript:void();\" onclick=\"c2g_showInfo('$msg[1]');\">$msg[0]</a>] "
                           . "$msg[2]<br>\n");
              }
        }
        @fclose($fp);
}

function c2g_show_form() {
        global $PHP_SELF, $c2g_namecookie, $c2g_conf, $c2g_configloaded, $c2g_js;
	if (!$c2g_configloaded) {
		return;
	}
	$name = $_REQUEST[$c2g_namecookie];
	if (!$name) {
		$name = $c2g_conf['defname'];
	}

	$c2g_url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF . '?c2g_op=conf';
        $url = "http://chatter.flooble.com/c2g/confframe.php?cu=" . urlencode($c2g_url);
	
        c2g_write('<table border="0" width="100%"><form id="c2g_form" method="POST" action="http://' 
		. $_SERVER["HTTP_HOST"] . $PHP_SELF .'">');
	if ($c2g_js) {
		c2g_write('<input type="hidden" name="c2g_url" value="');
		c2g_write_js("var url = document.location.href; \n"
			. "document.write(url);\n");
		c2g_write('">');
	}
	c2g_write('<input type="hidden" name="c2g_op" value="post">'
                      . '<tr><td>Name:</td><td><input name="c2g_n" size="10" style="width:100%" value="' . $name . '"></td></tr>'
                      . '<tr><td colspan="2"><input name="c2g_m" size="12" style="width:100%" value=""></td></tr>'
                      . '<tr><td colspan="2" align="center"><input type="submit" value="post">'
		      . ' <a href="' . $url . '" target="_top">!</a></td></tr></form>'
                      . '</table>');
}

function c2g_post() {
        global $c2g_datafile, $c2g_filepath, $c2g_conf, $c2g_namecookie, $REMOTE_ADDR;
        $c2g_m = trim(stripslashes($_REQUEST['c2g_m']));
	$c2g_m = preg_replace("/</", "&lt;", $c2g_m);
	$c2g_m = preg_replace("/>/", "&gt;", $c2g_m);
        $c2g_n = trim(stripslashes($_REQUEST['c2g_n']));
	if (!$c2g_n || !$c2g_m) return;
        $num = 0;
        $lines = Array();
	if ($fp = @fopen($c2g_filepath . $c2g_datafile, 'r')) {
	        while ($str = fgets($fp, 1024)) {
        	    array_push($lines, $str);
       	     	    $num++;
        	}
		fclose($fp);
	}
	$info = time() . '|' . c2g_hideip($REMOTE_ADDR);
	if ($c2g_m == trim($lines[$num-1])) return;
        array_push($lines, $c2g_n . "\n");
        array_push($lines, $info . "\n");
        array_push($lines, $c2g_m . "\n");
        while ($num+3 > $c2g_conf['lines'] * 3) {
              array_shift($lines);
              $num--;
        }

        $fp = fopen($c2g_filepath . $c2g_datafile, 'w');
        foreach ($lines as $line) {
                fputs($fp, $line);
        }
        fclose($fp);

	setCookie($c2g_namecookie, $c2g_n);
}

function c2g_write($str) {
        global $c2g_js;
        if (! $c2g_js) {
                echo($str);
        } else {
                $str = ereg_replace('\\\\', '\\\\\\\\', $str);
                $str = ereg_replace("'", "\\'", $str);
                $str = ereg_replace("\r", "", $str);
                foreach (split("\n", $str) as $s) {
                        echo("document.write('$s".'\n'."');\n");
                }
        }
}

function c2g_write_js($param) {
        global $c2g_js;
        if ($c2g_js) {
                echo($param);
        } else {
                echo("<SCRIPT language=\"javascript\">$param</SCRIPT>");
        }
}

function c2g_writeHeader() {
        global $c2g_js;
        $ctype='text/html';
        if ($c2g_js) {
                $ctype='application/x-javascript';
        }
        header("Content-type: $ctype");
	header("P3P: CP=\"NOI DSP COR CURa ADMa DEVa TAIa OUR BUS IND UNI COM NAV INT STA PRE OTC\n\"");

        header("Expired: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Pragma: no-cache");
}

function c2g_testconf() {
	global $c2g_filepath, $c2g_conffile;
	if ($fp = @fopen($c2g_filepath . $c2g_conffile, 'a')) {
		fclose($fp);
		return true;
	}
	return false;
}

function c2g_testself() {
	if ($fp = @fopen($_SERVER["SCRIPT_FILENAME"], 'a')) {
		fclose($fp);
		return true;
	}
	return false;
}

function c2g_startpage($title = '') {
?>
	<html>
	<head>
	<title>flooble chatter2go</title>
	<link rel="stylesheet" href="http://chatter.flooble.com/style.css">
	</head>
	<body marginwidth="2" marginheight="2" class="content">
<?
}

function c2g_endpage() {
?>
	</body>
	</html>
<?
}

function c2g_showlogin() {
        global $PHP_SELF;
?>
	<table border="0" cellspacing="1" cellpadding="0" class="bar" width="90%" align="center">
		<form action="<?=$PHP_SELF?>" method="post">
		<input type="hidden" name="c2g_op" value="<?=$_REQUEST['c2g_op']?>">
		<input type="hidden" name="c2g_cop" value="<?=$_REQUEST['c2g_cop']?>">
		<input type="hidden" name="c2g_login" value="true">
		<tr>
		<td class="altcontent">
			In order to access configuration options, please first log in to 
			your chatter2go by entering your password. If you have forgotten your
			password, you will have to manually edit the <b>chatter2go.php</b> script,
			and change the first line with text to say <b>$c2g_pass = '';</b>.
			<p>
			<div align="center">
<? 	if ($_REQUEST['c2g_login']) { ?>
				<span class="alert">
					The password you entered is incorrect.
				</span>
				<p>
<?	} ?>
				Password:
				<input type="password" name="c2g_upass">
				<input type="submit" value="Login">
			</div>
		</td>
		</tr>
		</form>
	</table>
<?
}

function c2g_showpathdialog() {
	global $PHP_SELF, $c2g_filepath, $c2g_pass, $c2g_p1, $c2g_p2, $c2g_upass;
	if (!c2g_testconf()) {
		$warning = '<li><span class="alert">Current configuration path is not writable! (' 
			. '<a href="http://chatter.flooble.com/c2g/help/index.php?f=confperm.php&p=' . $c2g_filepath . '" target="c2g_help">More info</a>'
			. ')</span>' . "\n";
		
	}
	if (!c2g_testself()) {
		$warning .= '<li><span class="alert">Chatter2go does not have the right to change itself. ' 
			. 'Cannot change file path or password. ('
			. '<a href="http://chatter.flooble.com/c2g/help/index.php?f=selfperm.html" target="c2g_help">More info</a>'
			. ')</span>' . "\n";
	}
	if ($c2g_p1 != $c2g_p2) {
		$warning .= '<li><span class="alert">The password you entered did not match confirmation. '
			. '</span>' . "\n";
	}
?>
	<table border="0" cellspacing="1" cellpadding="0" class="bar" width="90%" align="center">
		<tr>
		<td class="altcontent">
			<a href="http://chatter.flooble.com/c2g/help/index.php?f=path.php" target="c2g_help"><img src="http://chatter.flooble.com/images/chatter/question.gif" align="right" border="0" title="Help" alt="Help"></a>
			Please select a path to which chatter2go can save its configuration and data files.
			Note that this must be a directory on disk, in which chatter2go will have the
			permission to create files.
			<p>
			<b>Note:</b> Changing this path will cause all your settings to be discarded,
			and messages to be lost.
			<p>
			<?=$warning?>
			<p>
			<table border="0" class="altcontent">
			<form method="post" target="_top" action="<?=$PHP_SELF?>">
			<input type="hidden" name="c2g_upass" value="<?=$c2g_upass?>">
                        <input type="hidden" name="c2g_op" value="savepath">
				<tr>
				<td>File Path:</td>
				<td>
					<input size="30" name="c2g_p" value="<?=$c2g_filepath?>">
				</td>
				</tr>
<? 	if ($c2g_pass) { ?>
				<tr>
                                <td class="content" colspan="2">
					If you wish, you may change the administrative password
					by entering a new password twice. Leave the fields blank
					if you do not wish to change the password.
                                </td>
<?	} else { ?>
				<tr>
				<td class="content" colspan="2">
					You need to define a password to use to access chatter2go's 
					administrative features (such as set-up, deleting posts, etc)
					Please enter a password below:
				</td>
				</tr>
<? 	} ?>
				<tr>
				<td>Password:</td>
				<td><input type="password" name="c2g_p1"></td>
				</tr>
				<tr>
				<td>Confirm:</td>
				<td><input type="password" name="c2g_p2"></td>
				</tr>
				<tr>
				<td align="center" colspan="2">
					<input type="submit" value="Save">
					<input type="reset" value="Reset">
<? 		if (!$warning || c2g_testconf()) { ?>
					<a href="<?=$PHP_SELF?>?c2g_op=conf">Proceed to Set-Up &gt;&gt;</a>
<? 		} ?>
				</td>
				</tr>
			</form>
			</table>
		</td>
		</tr>
	</table>
<?
}

function c2g_setfilepath($new_path, $new_pass, $plain_pass) {
	if (!c2g_testself()) return;
	$new_script = Array();
	$fp = @fopen($_SERVER["SCRIPT_FILENAME"], 'r');
	while ($line = fgets($fp, 1024)) {
		if (!$found_path) {
			if (preg_match("/\\\$c2g_filepath = '[^']*';/", $line)) {
				$line = preg_replace("/\\\$c2g_filepath = '[^']*';/", "\$c2g_filepath = '$new_path';", $line);
				$found_path = true;
			}
		}
		if ($plain_pass && !$found_pass) {
			if (preg_match("/\\\$c2g_pass = '[^']*';/", $line)) {
				$line = preg_replace("/\\\$c2g_pass = '[^']*';/", "\$c2g_pass = '$new_pass';", $line);
				$found_pass = true;
			}
		}
		$new_script[] = $line;
	}
	fclose($fp);
	$fp = fopen($_SERVER["SCRIPT_FILENAME"], 'w');
	foreach ($new_script as $line) {
		fputs($fp, $line);
	}
	fclose($fp);
}


function c2g_showcodedialog() {
	global $PHP_SELF,  $c2g_conf, $c2g_upass;	
	$c2g_url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF;
?>
	<table border="0" cellspacing="1" cellpadding="0" class="bar" width="90%" align="center">
		<tr>
		<td class="altcontent">
			<a href="http://chatter.flooble.com/c2g/help/index.php?f=code.php" target="c2g_help"><img src="http://chatter.flooble.com/images/chatter/question.gif" align="right" border="0" title="Help" alt="Help"></a>
			Your <b>chatter2go</b> code: <br>
			Use this HTML code to insert the chatter2go into your web page.
			<form>
			<textarea rows="9" cols="40" style="font-family: arial;" name="code">
<!-- Chatter2go Code Start -->
<SCRIPT language="Javascript" 
src="<?=$c2g_url?>?c2g_js=1">
</SCRIPT>
<!-- Chatter2go Code End   -->
			</textarea>	
			<p>
			<div align="right">
				<input type="button" value="Select Code" onclick="this.form.code.select(); this.form.code.focus();">
			</div>
			</form>
			<div align="center"> (<a href="<?=$PHP_SELF?>?c2g_op=conf">Back to Config</a>)
			</div>
		</td>
		</tr>
	</table>
		

<?
}

function c2g_showconfdialog() {
	global $PHP_SELF, $c2g_filepath, $c2g_conffile, $c2g_conf, $c2g_version, $c2g_upass;
	if (!c2g_testconf) {
		$warning = '<li><span class="alert">Current configuration path is not writable! Make sure that' 
			. '<blockquote>' . $c2g_filepath . $c2g_conffile 
			. '</blockquote> can be written by the script user.</span><br>' 
			. '(<a href="' . $PHP_SELF . '?c2g_op=setup&c2g_cop=path">Change config path</a>' . "\n";
	}
	c2g_printfunction(1);
?>
	
	<table border="0" cellspacing="1" cellpadding="0" class="bar" width="90%" align="center">
                <tr>
                <td class="altcontent">
			<a href="http://chatter.flooble.com/c2g/help/index.php?f=general.php" target="c2g_help"><img src="http://chatter.flooble.com/images/chatter/question.gif" align="right" border="0" title="Help" alt="Help"></a>
			Configure your chatter2go using the form below: <br> 
			(<a href="<?=$PHP_SELF?>?c2g_op=conf&c2g_cop=code">Get Insertion Code</a> |
			<a href="<?=$PHP_SELF?>?c2g_op=conf&c2g_cop=path">Change data path / password</a>)
			<p>
			<form method="post" target="_top" action="<?=$PHP_SELF?>">
			<input type="hidden" name="c2g_upass" value="<?=$c2g_upass?>">
			<input type="hidden" name="c2g_op" value="saveconf">
				<table border="0" align="center" class="altcontent">
					<tr>
					<td>Lines:</td>
					<td>
						 <input name="c2g_l" size="3" maxlength="2" value="<?=$c2g_conf['lines']?>">
					</td>
					</tr>
					<tr>
					<td>Default name:</td>
					<td>
						<input name="c2g_dn" value="<?=$c2g_conf['defname']?>">
					</td>
					</tr>
					<tr>
					<td colspan="2" align="center">
						<input type="submit" value="Save">
						<input type="reset" value="Reset">
					</td>
					</tr>
				</table>
			</form>
			<? c2g_listentries(); ?>
			<p>
			<hr size="1">
			<b>Version <?=$c2g_version?>:</b>
			<i>
			<script src="http://chatter.flooble.com/c2g/versioncheck.php?version=<?=$c2g_version?>"></script>
			</i>
		</td>
		</tr>
	</table>
				
<?
}

function c2g_checkpass($pass) {
	global $c2g_pass;
	if (! $c2g_pass) { return true; }
	//echo ("$pass <br> $c2g_pass <br> " . crypt($pass, 'chatter2go'));
	return ($c2g_pass == crypt($pass, 'chatter2go'));
}

function c2g_saveconf($props) {
	global $c2g_filepath, $c2g_conffile;
	if (! $fp = @fopen($c2g_filepath . $c2g_conffile, 'w')) {
		//	echo("CANNOT SAVE!");
		return;
	}
	foreach(array_keys($props) as $prop) {
		fputs($fp, $prop . " = " . $props[$prop] . "\n");
	}
	fclose($fp);
}

function c2g_loadconf() {
	global $c2g_filepath, $c2g_conffile, $c2g_conf, $c2g_configloaded;
        if (! $fp = @fopen($c2g_filepath . $c2g_conffile, 'r')) {
		//	echo("CANNOT LOAD! $c2g_falepath" . $c2g_conffile);
                return;
        }
	while ($line = fgets($fp, 1024)) {
		if (preg_match("/^([^= ]*)\s*=\s*(.*)$/", $line, $matches)) {
			list(, $name, $val) = $matches;
		//	echo("[$name] -> [$val] <br>\n");
			$c2g_conf[$name] = $val;
		//	echo($c2g_conf[$name] . "<br>\n");
		}
	}
        fclose($fp);
	$c2g_configloaded = 1;
}

function c2g_listentries() {
        global $PHP_SELF, $c2g_datafile, $c2g_filepath, $c2g_configloaded, $c2g_upass;
        if (! $c2g_configloaded) {
                $c2g_url = 'http://' . $_SERVER["HTTP_HOST"] . $PHP_SELF . '?c2g_op=conf';
                c2g_write("<b>chatter2go</b> must first be configured. <br>"
                        . "<a href=\"http://chatter.flooble.com/c2g/confframe.php?cu=" . urlencode($c2g_url)
                        . "\">Configure now</a>");
                return;
        }
        $fp = @fopen($c2g_filepath . $c2g_datafile, 'r');
        if (!$fp) {
                  c2g_write('Post messages here.');
                  return;
        }
        $line = 0;
	$i=0;
        while ($str = fgets($fp, 1024)) {
              $msg[$line] = preg_replace("/\n/", '', $str);
              $line++;
              if ($line == 3) {
		 $i++;
                 $line = 0;
		 echo("<a href=\"$PHP_SELF?c2g_op=conf&c2g_cop=del&c2g_delnum=$i\"><img src=\"http://chatter.flooble.com/images/chatter/trash.gif\" border=\"0\"></a> ");
                 echo("[<a href=\"javascript:void();\" onclick=\"c2g_showInfo('$msg[1]');\">$msg[0]</a>] "
                        . "$msg[2]<br>\n");
              }
        }
        @fclose($fp);
}

function c2g_delentry($delnum) {
	global $c2g_datafile, $c2g_filepath, $c2g_conf;
        $num = 0;
        $lines = Array();
        if ($fp = @fopen($c2g_filepath . $c2g_datafile, 'r')) {
                while ($str = fgets($fp, 1024)) {
                    array_push($lines, $str);
                    $num++;
                }
                fclose($fp);
        }

        $fp = fopen($c2g_filepath . $c2g_datafile, 'w');
	$i = 0;
        foreach ($lines as $line) {
		if ($i < 3*($delnum-1) || $i >= 3*$delnum) {
                	fputs($fp, $line);
		} 
		$i++;
        }
        fclose($fp);
}

function c2g_hideip($addr) {
	list($a,$b,$c,$d) = explode('.', $addr);
	$val = $d +($c*256) + ($b*65536) + ($a*16777216);
	return $val;
}

function c2g_getip($val) {
	$vorig = $val;
	$val =  floor($val / 256);
	$d = $vorig - ($val*256);
	$c = $val % 256;
	$val = floor($val/256);
	$b = $val % 256;
	$a = floor($val/256);
	return array($a, $b, $c, $d);
}
?>
