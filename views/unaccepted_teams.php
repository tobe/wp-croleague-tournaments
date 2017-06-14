<div class="wrap">
    <h2>Neprihvaćeni timovi (zadnjih 5 turnira)</h2>
    <p>Ovdje se nalaze timovi koje osoblje Croleaguea nije prihvatilo iz određenih razloga, no isti su putem pošte <span style="color: #FF0000;font-weight: bold;">potvrdili</span> svoju registraciju.</p>
    <?php
        foreach($tournaments as $tournament) {
            $teams = $wpdb->get_results('SELECT * FROM `teams` WHERE `tournament_id`=' . $tournament['id'] . ' AND `accepted`=0 AND `verified`=1 ORDER BY `created` DESC', ARRAY_A);
            echo '<h3>' . $tournament['name'] . '</h3>';
            if(!empty($teams)):
            ?>
                <table class="widefat" cellspacing="0">
                <thead>
                    <tr>
                        <th id="columnname" class="manage-column column-columnname" scope="col">ID</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Ime</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Tag</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Leader</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Leader E-Mail</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Poslužitelj</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Divizija</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Top</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Jungle</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Mid</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">ADC</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Support</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Sub A</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Sub B</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Logo</th>
                    </tr>
                </thead>
                    <?php
                        foreach($teams as $a_team) { ?>
                        <tbody>
                            <td class="column-columnname"><?php echo $a_team['id']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['name']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['tag']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['leader']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['leader_email']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['server']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['division']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['top']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['jungle']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['mid']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['adc']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['support']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['sub_a']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['sub_b']; ?></td>
                            <td class="column-columnname"><?php echo $a_team['logo']; ?></td>
                        </tbody>
                        <?php } ?>
                </table>
        <?php
        else:
            echo '<div class="update-nag">Za ovaj turnir još se nitko nije prijavio, odnosno nije potvrdio registraciju putem e-pošte.</div>';
        endif;
        }
    ?>
</div>

<div class="wrap">
<form action="" method="post">
    <h3 class="title">Ažuriraj status tima</h3>
    Promijeni status tima
        <select name="id">
            <?php
                if(empty($teams)) {
                    echo '<option disabled="disabled">(Nema dostupnih timova)</option>';
                }else {
                    foreach($teams as $a_team) {
                        echo '<option value="' . $a_team['id'] . '">' . $a_team['name'] . '</option>';
                    }
                }
            ?>
        </select>
        u&nbsp;
        <select name="status">
            <option value="0">Neprihvaćen (kako jest)</option>
            <option value="1">Prihvaćen</option>
        </select><br>
        Izbriši ga ukoliko je neprihvaćen? <input type="checkbox" name="delete" value="1"><br>
        <textarea name="message" cols="100" rows="5"></textarea>
        <p style="font-size: 12px;">* Ukoliko navedeno tekstualno polje nije prazno sadržaj istog bit će poslan e-poštom.</p>
        <input style="margin-top: 20px;" id="submit" class="button button-primary" value="Potvrdi" type="submit">
    </form>
</div>