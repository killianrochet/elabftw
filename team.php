<?php
/********************************************************************************
*                                                                               *
*   Copyright 2012 Nicolas CARPi (nicolas.carpi@gmail.com)                      *
*   http://www.elabftw.net/                                                     *
*                                                                               *
********************************************************************************/

/********************************************************************************
*  This file is part of eLabFTW.                                                *
*                                                                               *
*    eLabFTW is free software: you can redistribute it and/or modify            *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabFTW is distributed in the hope that it will be useful,                 *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabFTW.  If not, see <http://www.gnu.org/licenses/>.   *
*                                                                               *
********************************************************************************/
require_once('inc/common.php');
require_once('inc/locale.php');
$page_title= _('Team'); 
$selected_menu = 'Team';
require_once('inc/head.php');
require_once('inc/info_box.php');
?>
<menu>
<ul>
<li class='tabhandle' id='tab1'><?php echo _('Members');?></li>
<li class='tabhandle' id='tab2'><?php echo _('Statistics')?></li>
<li class='tabhandle' id='tab3'><?php echo _('Tips and tricks');?></li>
</ul>
</menu>
<!-- *********************** -->
<div class='divhandle' id='tab1div'>
<?php display_message('info_nocross', sprintf(_('You belong to the %s team.'), get_team_config('team_name')));?>
<table id='teamtable'>
    <tr>
        <th><?php echo _('Name');?></th>
        <th><?php echo _('Phone');?></th>
        <th><?php echo _('Mobile');?></th>
        <th><?php echo _('Website');?></th>
        <th><?php echo _('Skype');?></th>
    </tr>
<?php // SQL to get members info
$sql = "SELECT * FROM users WHERE validated = :validated AND team = :team_id";
$req = $pdo->prepare($sql);
$req->execute(array(
    'validated' => 1,
    'team_id' => $_SESSION['team_id']
));

while ($data = $req->fetch()) {
    echo "<tr>";
    echo "<td><a href='mailto:".$data['email']."'>".$data['firstname']." ".$data['lastname']."</a></td>";
        if (!empty($data['phone'])) { 
            echo "<td>".$data['phone']."</td>";
        } else {
            echo "<td>&nbsp;</td>"; // Placeholder
        }
        if (!empty($data['cellphone'])) { 
            echo "<td>".$data['cellphone']."</td>"; 
        } else {
            echo "<td>&nbsp;</td>";
        }
        if (!empty($data['website'])) { 
            echo "<td><a href='".$data['website']."'>www</a></td>"; 
        } else {
            echo "<td>&nbsp;</td>";
        }
        if (!empty($data['skype'])) { 
            echo "<td>".$data['skype']."</td>";
        } else {
            echo "<td>&nbsp;</td>";
        }
}
?>
</table>
</div>
<!-- *********************** -->
<div class='divhandle' id='tab2div'>
<?php
// show team stats
$count_sql="SELECT
(SELECT COUNT(users.userid) FROM users WHERE users.team = :team) AS totusers,
(SELECT COUNT(items.id) FROM items WHERE items.team = :team) AS totdb,
(SELECT COUNT(experiments.id) FROM experiments WHERE experiments.team = :team) AS totxp";
$count_req = $pdo->prepare($count_sql);
$count_req->bindParam(':team', $_SESSION['team_id']);
$count_req->execute();
$totals = $count_req->fetch(PDO::FETCH_ASSOC);
?>
    <p><?php echo _('There is a total of').' '.$totals['totxp'].' '._('experiments by').' '.$totals['totusers'].' '._('different users.');?></p>
    <p><?php echo _('There is a total of').' '.$totals['totdb'].' '._('items in the database');?></p>
</div>

<!-- *********************** -->
<div class='divhandle' id='tab3div'>
    <p>
        <ul>
        <li class='tip'><?php echo _("You can use a TODOlist by pressing 't'");?></li>
        <li class='tip'><?php printf(_('You can have experiments templates (%sControl Panel%s)'), "<a href='ucp.php?tab=3'>", "</a>");?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        <li class='tip'><?php echo _('Team');?></li>
        </ul>
    </p>
</div>

<script>
$(document).ready(function() {
    // TABS
    // get the tab=X parameter in the url
    var params = getGetParameters();
    var tab = parseInt(params['tab']);
    if (!isInt(tab)) {
        var tab = 1;
    }
    var initdiv = '#tab' + tab + 'div';
    var inittab = '#tab' + tab;
    // init
    $(".divhandle").hide();
    $(initdiv).show();
    $(inittab).addClass('selected');

    $(".tabhandle" ).click(function(event) {
        var tabhandle = '#' + event.target.id;
        var divhandle = '#' + event.target.id + 'div';
        $(".divhandle").hide();
        $(divhandle).show();
        $(".tabhandle").removeClass('selected');
        $(tabhandle).addClass('selected');
    });
    // END TABS
});
</script>

<?php require_once('inc/footer.php');?>
