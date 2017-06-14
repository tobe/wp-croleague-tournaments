<h2>Ljestvica</h2>
<div class="wrap">
    <form action="" method="post">
        Sastavi ljestvicu za tim&nbsp;
        <select name="tour_id">
            <?php
                foreach($tournaments as $tournament) {
                    echo '<option value="' . $tournament['id'] . '">' . $tournament['name'] . '</option>';
                }
            ?>
        </select>
        i pomiješaj <input type="checkbox" name="shuffle" value="1" checked>
        <input id="submit" class="button button-primary" value="Sastavi" type="submit">
    </form>
</div>

<div class="wrap">
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = esc_sql($_POST['tour_id']);
        // Grab all the teams for the specific tournament.
        $teams = $wpdb->get_results('SELECT * FROM `teams` WHERE `tournament_id`=' . $id . ' AND `accepted`=1 AND `verified`=1 ORDER BY `created` DESC', ARRAY_A);
        if(!$teams || empty($teams)) {
            echo '<div class="error settings-error"><p><strong>Za ovaj turnir nema igrača.</strong></p></div>';
        }

        $length         = count($teams);
        if(isset($_POST['shuffle'])) {
            shuffle($teams);
        }
        $first_chunk    = array_slice($teams, 0, $length/2);
        $second_chunk   = array_slice($teams, $length / 2);

        // var_dump($first_chunk);

        // Now we need to loop and match the corresponding teams.
        $a = 1;
        $b = $length-1;
        foreach($teams as $teamlist) {
            echo $teamlist['name'] . ' igra s ' . $teams[$b]['name'] . "<br>";
            if($a == $b) { break; }
            $b--;
            $a++;
        }

    }
    ?>
</div>