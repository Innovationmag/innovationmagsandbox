<?php
/***************************************************************************

Copyright: Benjamin Pick, 2012-2013

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at http://www.gnu.org/copyleft/gpl.html

**************************************************************************/

function wpcf7_SelectBoxEditorButton_formatValues($name, $email)
{
	$url = '#' . str_replace("%20", "+", urlencode($name));
	$label = $name . " <" . $email . ">";

	return array('url' => $url, 'email' => $email, 'name' => $name, 'label' => $label);
}

interface Wpcf7_SelectBoxEditorButton_Parser
{
	public function __construct($formatValuesCallback = null);
	public function getAdressesFromFormText($text);
}

abstract class Wpcf7_SelectBoxEditorButton_AbstractParser
{
	protected $formatValuesCallback;
	
	public function __construct($formatValuesCallback = null) {
		if (!is_callable($formatValuesCallback))
			$formatValuesCallback = 'wpcf7_SelectBoxEditorButton_formatValues';
		$this->formatValuesCallback = $formatValuesCallback;
	}
}

class Wpcf7_SelectBoxEditorButton_Wpcf7_Shortcode_Parser extends Wpcf7_SelectBoxEditorButton_AbstractParser implements  Wpcf7_SelectBoxEditorButton_Parser
{
	public function getAdressesFromFormText($text)
	{
		$wpcf7_shortcode_manager = new WPCF7_ShortcodeManager();
		$wpcf7_shortcode_manager->add_shortcode( 'select', array($this, 'selectShortcodeCallback'), true);
		$wpcf7_shortcode_manager->add_shortcode( 'select*', array($this, 'selectShortcodeCallback'), true);
	
		$text = $wpcf7_shortcode_manager->normalize_shortcode($text);
	
		$this->adresses = array();
		$wpcf7_shortcode_manager->do_shortcode( $text, true );
		if (empty($this->adresses))
			return (string) $this->last_error_message;
		return $this->adresses;
	}
	
	protected $adresses;
	protected $last_error_message;
	
	public function selectShortcodeCallback($tag)
	{
		$options = (array) $tag['options'];
		$raw_values = (array) $tag['raw_values'];
	
		$id_att = null;
		foreach ( $options as $option )
		{
			if ( preg_match( '%^id:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
				$id_att = $matches[1];
			}
		}
		if (is_null($id_att))
			return _log($this->last_error_message = 'Select element needs id:recipient !');
		else if ($id_att != 'recipient')
			return _log($this->last_error_message = 'Select element id needs to be id:recipient (currently is id:' . $id_att. ') !');
			
		foreach($raw_values as $value)
		{
			$exploded = explode('|', $value);
			if (count($exploded) >= 2)
				list($name, $email) = $exploded;
			else
				$name = $email = $exploded[0];
			if (!is_email($email))
				continue;
				
			$this->adresses[] = call_user_func($this->formatValuesCallback, $name, $email);
		}
	}
}

class Wpcf7_SelectBoxEditorButton_SimpleRegexParser extends Wpcf7_SelectBoxEditorButton_AbstractParser implements Wpcf7_SelectBoxEditorButton_Parser {
	public function getAdressesFromFormText($text)
	{
		$res = preg_match_all('/\[select\*? .* id:([a-z]+)[^"]* (".*")[^"]*\]/i', $text, $select_matches, PREG_SET_ORDER);
		if ($res == 0)
			return _log('No select box found.');

		$ret = null;
		foreach ($select_matches as $select_match)
		{
			$id = $select_match[1];
			if ($id != 'recipient')
			{
				if (!is_array($ret))
					$ret = _log('Invalid id, needs to be id:recipient (currently is id:' . $id. ')'); // Currently hardcoded to #recipient
				continue;
			}
			$adresses = $select_match[2];
		
			preg_match_all('/"([^"|]+)\|([^"|]+@[^"|]+)"/', $adresses, $matches, PREG_SET_ORDER);
		
			foreach($matches as $match)
			{
				$name = $match[1];
				$email = $match[2];
		
				if (!is_array($ret))
					$ret = array();
				$ret[] = call_user_func($this->formatValuesCallback, $name, $email);;
			}
		}
		return $ret;
	}
}