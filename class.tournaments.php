<?php

class Tournaments {
    private $wpdb;

    /**
     * Inits all the hooks needed.
     */
    public function __construct()  {
        $this->wpdb = $GLOBALS['wpdb']; // Assign the globals var to a private one so we can use this without making a mess. Also Wordpress sucks.

        add_action('admin_menu', function() {
            // The main toplevel menu
            add_menu_page('Turniri', 'Turniri', 'manage_options', 'tr-tournaments-handle', array($this, 'list_tournaments'));

            add_submenu_page('tr-tournaments-handle', 'Dodaj turnir', 'Dodaj turnir', 'manage_options', 'tr-dodaj-turnir', array($this, 'add_tournament'));

            add_submenu_page('tr-tournaments-handle', 'Prihvaćeni timovi', 'Prihvaćeni timovi', 'manage_options', 'tr-timovi-accepted', array($this, 'accepted_teams'));

            add_submenu_page('tr-tournaments-handle', 'Neprihvaćeni timovi', 'Neprihvaćeni timovi', 'manage_options', 'tr-timovi-unaccepted', array($this, 'unaccepted_teams'));

            add_submenu_page('tr-tournaments-handle', 'Nepotvrđeni timovi', 'Nepotvrđeni timovi', 'manage_options', 'tr-timovi-unverified', array($this, 'unverified_teams'));

            add_submenu_page('tr-tournaments-handle', 'Ljestvica', 'Ljestvica', 'manage_options', 'tr-timovi-ljestvica', array($this, 'ladder'));
        });

        add_action('admin_head', function() {
            echo '<style>.column-columnname { font-size: 12px !important; } .update-nag { margin:0; }</style>';
        });
    }

    public function add_tournament() {
        if(isset($_POST['name'])) {
            $errors     = array();
            $name       = htmlspecialchars($_POST['name']);
            $seats      = esc_sql($_POST['seats']);
            $server     = esc_sql($_POST['server']);
            $deadline   = htmlspecialchars($_POST['deadline']);

            if(strlen($name) >= 33) { $errors[] = 'Ime je predugačko.'; }
            if(!preg_match('/([0-9]{2})\.([0-9]{2})\.([0-9]{4})/', $deadline)) {
                $errors[] = 'Datum nije u kompatibilnom obliku';
            }

            if(!empty($errors)) {
                echo 'Došlo je do sljedećih pogrešaka: ';
                print_r($errors);
            }else {
                // Dodaj u bazu.
                $this->wpdb->insert('tournaments', array(
                    'name'      =>  $name,
                    'seats'     =>  $seats,
                    'server'    =>  $server,
                    'status'    =>  0,
                    'created'   =>  time(),
                    'deadline'  =>  strtotime($deadline),
                ));

                echo '<div class="updated"><p><strong>Turnir <em>' . $name . '</em> uspješno dodan.</strong></p></div>';
            }
        }

        echo $this->render('add_tournament.php');
    }

    public function list_tournaments() {
        // Try to fetch the tournaments.
        $data = $this->wpdb->get_results('SELECT * FROM `tournaments` ORDER BY `created` DESC', ARRAY_A);
        if(!isset($data) || is_null($data)) {
            echo '<div class="error settings-error"><p><strong>Došlo je do pogreške prilikom preuzimanja podataka.</strong></p></div>';
        }

        echo $this->render('list_tournaments.php', array('data' => $data));

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Run a query and see if it works!.
            if($this->wpdb->update('tournaments', array('status' => $_POST['status']), array('id' => $_POST['id']))) {
                echo '<div class="updated"><p><strong>Status turnira ažuriran! Osvježavam stranicu...</strong></p></div>';
                echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '?page=tr-tournaments-handle"</script>';
            }
        }
    }

    public function unaccepted_teams() {
        // Get all tournaments
        $tournaments = $this->wpdb->get_results('SELECT `id`,`name` FROM `tournaments` ORDER BY `created` DESC', ARRAY_A);
        // Now for each of the tournaments fetch the teams which haven't been accepted yet.
        echo $this->render('unaccepted_teams.php', array('tournaments' => $tournaments, 'wpdb' => $this->wpdb));

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!empty($_POST['message'])) {
                // Get that leader email first.
                $leader_email = $this->wpdb->get_row('SELECT `leader_email` FROM `teams` WHERE `id`=' . $_POST['id'] . ' LIMIT 1', ARRAY_A);
                if(!$leader_email) { die('Something went terribly wrong.'); }
                // Let's send that email
                $headers = 'From: Croleague team <myname@example.com>' . "\r\n"; // Change this.
                wp_mail($leader_email['leader_email'], 'Croleague registracija', $_POST['message'], $headers);
            }
            // Let's accept the team shall we.
            $this->wpdb->update('teams', array('accepted' => $_POST['status']), array('id' => $_POST['id']));
            // Did we delete them?
            if($_POST['delete'] == '1') {
                $this->wpdb->delete('teams', array('id' => $_POST['id']), array('%d'));
            }

            echo '<div class="updated"><p><strong>Status tima ažuriran! Osvježavam stranicu...</strong></p></div>';
            echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '?page=tr-timovi-unaccepted"</script>';
        }
    }

    public function accepted_teams() {
        // Get all tournaments
        $tournaments = $this->wpdb->get_results('SELECT `id`,`name` FROM `tournaments` ORDER BY `created` DESC', ARRAY_A);
        // Now for each of the tournaments fetch the teams which haven't been accepted yet.
        echo $this->render('accepted_teams.php', array('tournaments' => $tournaments, 'wpdb' => $this->wpdb));

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Demote them (unaccept them)
            if($this->wpdb->update('teams', array('accepted' => 0), array('id' => $_POST['id']))) {
                echo '<div class="updated"><p><strong>Status tima ažuriran! Osvježavam stranicu...</strong></p></div>';
                echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '?page=tr-timovi-accepted"</script>';
            }
        }
    }

    public function unverified_teams() {
        // Get all tournaments
        $tournaments = $this->wpdb->get_results('SELECT `id`,`name` FROM `tournaments` ORDER BY `created` DESC', ARRAY_A);
        // Now for each of the tournaments fetch the teams which haven't been accepted yet.
        echo $this->render('unverified_teams.php', array('tournaments' => $tournaments, 'wpdb' => $this->wpdb));

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Let's verify the team manually, shan't we not ehehh
            if($_POST['status'] == 0 || $_POST['status'] == 1) {
                $this->wpdb->update('teams', array('verified' => $_POST['status']), array('id' => $_POST['id']));
            }else {
                // Delete here
                $this->wpdb->delete('teams', array('id' => $_POST['id']), array('%d'));
            }
            echo '<div class="updated"><p><strong>Status tima ažuriran! Osvježavam stranicu...</strong></p></div>';
            echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '?page=tr-timovi-unverified"</script>';
        }
    }

    public function ladder() {
        // Get all tournaments first.
        $tournaments = $this->wpdb->get_results('SELECT `id`,`name` FROM `tournaments` ORDER BY `created` DESC', ARRAY_A);
        echo $this->render('ladder.php', array('tournaments' => $tournaments, 'wpdb' => $this->wpdb));
    }

    private function render($filename, $args = array()) {
        $file = TOURNAMENTS_PATH . '/views/' . $filename;
        // Check whether the file exists and it's readable.
        if(!file_exists($file) || !is_readable($file)) {
            die('Cannot access the view file "' . $filename . '"');
        }

        ob_start();
            if(!empty($args)) extract($args);
            require $file;
            $d = ob_get_contents();
        ob_end_clean();

        return $d;
    }

}

?>