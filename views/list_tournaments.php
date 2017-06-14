<div class="wrap">
    <h2>Turniri</h2>
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col">ID</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Ime</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Status</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Napravljen</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Rok za prijavu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $k => $tournaments) {
                // if($k % 2 == 0) { $class = 'alternate'; }
                // echo '<tr class="' . $class . '">';
                echo ($k % 2) ? '<tr>' : '<tr class="alternate">';
                echo '<td class="column-columnname">' . $tournaments['id'] . '</td>';
                echo '<td class="column-columnname">' . $tournaments['name'] . '</td>';
                echo '<td class="column-columnname">';
                switch($tournaments['status']) {
                    case 0:
                        echo 'Nije započeo';
                        break;
                    case 1:
                        echo 'Aktivan';
                        break;
                    case 2:
                        echo 'Završen';
                        break;
                }
                echo '</td>';
                echo '<td class="column-columnname">' . date('d.m.Y', $tournaments['created']) . '</td>';
                echo '<td class="column-columnname">' . date('d.m.Y', $tournaments['deadline']) . '</td>';
                echo '</tr>';
            } ?>
        </tbody>
    </table>

    <h2 style="margin-top: 20px;">Promjena statusa</h2>
    <form action="" method="post">
    Promjeni status turnira
        <select name="id">
            <?php
                foreach($data as $tour) {
                    echo '<option value="' . $tour['id'] . '">' . $tour['name'] . '</option>';
                }
            ?>
        </select>
        u
        <select name="status">
            <option value="0">Nije započeo</option>
            <option value="1">Aktivan</option>
            <option value="2">Završen</option>
        </select>

        <input id="submit" class="button button-primary" value="Potvrdi" type="submit">
    </form>


</div>