<?php
/**
 * @author Alex10336
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
**/
if (!SCRIPT_IN) die('Need by included');

/*
$tpl = new tpl_cartedetails;
$tpl->Setheader(intval($_GET["ID"]));
$tpl->AddRow($ligne);
$tpl->DoOutput();
*/

class tpl_cartedetails extends output {
	protected $BASE_FILE = '';
	private $bulle1="Faire une recherche sur ce nom de joueur";
	private $bulle2="Faire une recherche sur ce nom d'empire";
	private $lngtype;

	public function __construct() {
		$this->BASE_FILE = ROOT_URL."Cartedetail.php";
		$this->bulle1 = bulle($this->bulle1);
		$this->bulle2 = bulle($this->bulle2);
                $this->lngtype = language::getinstance()->GetLngBlock('dataengine');
                $this->lngtype = $this->lngtype['types']['imgurl'];
		parent::__construct();
	}

	public function Setheader($ID) {
$out = <<<EOF
<CarteDetails><content><![CDATA[
<table width="500px">
<tr>
	<td><font size="+1" color="#FFFFFF" width="450px">Syst&egrave;me N° $ID</font></td>
	<td><a onclick="Navigateur.SetStart($ID); return Carte.DetailsShow(false);" href=''>Départ</a></td>
	<td><a onclick="Navigateur.SetEnd($ID); return Carte.DetailsShow(false);" href=''>Arrivée</a></td>
	<td><a onclick="return Carte.DetailsShow(false);" href=''>Fermer</a></td>
</tr>
		<tr bgcolor='#222222'>
			<td><Font color='#FFFFFF'>Type</font></td>
			<td><Font color='#FFFFFF'>Coordonn&eacute;es</font></td>
			<td><Font color='#FFFFFF'>Joueur<br/>Empire</font></td>
			<td><Font color='#FFFFFF'>Infos</font></td>
			<td><Font color='#FFFFFF'>Notes</font></td>
		</tr>
EOF;
		$this->PushOutput($out);
	}

	public function AddRow($ligne) {
		$ligne["USER"]   = htmlspecialchars($ligne["USER"], ENT_QUOTES, 'utf-8');
		$ligne["EMPIRE2"] = addslashes(DataEngine::xml_fix51($ligne["EMPIRE"]));
		$ligne["EMPIRE"] = DataEngine::xml_fix51(htmlspecialchars($ligne["EMPIRE"], ENT_QUOTES, 'utf-8'));
		$ligne["INFOS"]  = htmlspecialchars($ligne["INFOS"], ENT_QUOTES, 'utf-8');
		$ligne["NOTE"]   = htmlspecialchars($ligne["NOTE"], ENT_QUOTES, 'utf-8');

		$Image = $this->lngtype['types']['imgurl'][$ligne["TYPE"]];
		$posout = ($ligne["POSOUT"] !="") ? "<br>".$ligne["POSOUT"]."-".$ligne["COORDET"]: "";
		$user = ($ligne["USER"]=="" ? "-" : $ligne["USER"]);
		$info = ($ligne["INFOS"]=="" ? "-" : $ligne["INFOS"]);
		$note = ($ligne["NOTE"]=="" ? "-" : $ligne["NOTE"]);
		$empire = '';
		if ($ligne["EMPIRE"] != "") {
		$empire.= "<br><a href='javascript:void(0);' {$this->bulle2} OnClick=\"Navigateur.InitSearch('";
		$empire.= ($ligne["EMPIRE2"])."',0); return false;\">{$ligne["EMPIRE"]}</a>";
		}
$out = <<<EOF
		<tr bgcolor='#222222'>
			<td><img width=48 height=48 src="%IMAGES_URL%{$Image}"></img></td>
			<td><Font color='#FFFFFF'>{$ligne["POSIN"]}-{$ligne["COORDET"]}{$posout}</font></td>
			<td>
				<a href='javascript:void(0);' {$this->bulle1} Onclick="Navigateur.InitSearch('{$ligne["USER"]}',1);">{$user}</a>
				{$empire}
			</td>
			<td><Font color='#FFFFFF'>{$info}</font></td>
			<td><Font color='#FFFFFF'>{$note}</font></td>
		</tr>

EOF;
		$this->PushOutput($out);
	}

	public function DoOutput($include_menu=true, $include_header=true) {
		$this->PushOutput("</TABLE>]]></content></CarteDetails>");
		parent::DoOutput();
	}

/**
 *
 * @return tpl_cartedetails
 */
	static public function getinstance() {
		if ( ! DataEngine::_tpl_defined(get_class()) )
			DataEngine::_set_tpl(get_class(),new self());

			return DataEngine::tpl(get_class());
	}
}