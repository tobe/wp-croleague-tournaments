<div class="wrap">
<h2>Novi turnir</h2>
<form action="" method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="name">Ime turnira</label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="name"></input>
                    <p class="description">Maksimalna duljina imena jest 32 znaka.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="deadline">Datum zatvaranja</label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="deadline" value="<?php echo date('d.m.Y', strtotime('+5 days')); ?>"></input>
                    <p class="description">Datum <strong>mora</strong> biti u obliku DD.MM.YYYY, gdje je D dan, M mjesec, a Y godina. Primjer: 01.09.2014.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="deadline">Broj mjesta</label>
                </th>
                <td>
                    <input name="seats" type="number" step="1" min="1" value="16"/>
                    <p class="description">Onoliko koliko mjesta u turniru N ima, toliko se ljudi za nj. mogu prijaviti.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="deadline">Poslužitelj</label>
                </th>
                <td>
                    <select name="server">
                        <option value="EU West">EU West</option>
                        <option value="EU Nordic &amp; East">EU Nordic &amp; East</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit"><input id="submit" class="button button-primary" value="Dodaj" type="submit"></p>
</form>
</div>